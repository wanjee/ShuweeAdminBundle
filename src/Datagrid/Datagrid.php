<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridField;
use Wanjee\Shuwee\AdminBundle\Datagrid\Filter\DatagridFilter;
use Wanjee\Shuwee\AdminBundle\Datagrid\Filter\DatagridFilterInterface;

/**
 * Class Datagrid
 * @package Wanjee\Shuwee\AdminBundle\Datagrid
 */
class Datagrid implements DatagridInterface
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    private $admin;

    /**
     * @var array List of available actions for this datagrid
     */
    private $actions = array();

    /**
     * @var array List of available fields for this datagrid
     */
    private $fields = array();

    /**
     * @var array List of available fields for this datagrid
     */
    private $filters = array();

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var array
     */
    private $options;

    /**
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * @var \Knp\Component\Pager\Pagination\PaginationInterface
     */
    private $pagination;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $factory;

    /**
     * @var null | FormInterface
     */
    private $filtersForm;

    /**
     * Datagrid constructor.
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $entityManager, FormFactory $factory)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                array(
                    'limit_per_page' => 10,
                    'default_sort_column' => 'id',
                    'default_sort_order' => 'asc',
                    'show_actions_column' => true,
                )
            )
            ->setAllowedTypes('limit_per_page', 'integer')
            ->setAllowedTypes('default_sort_column', 'string')
            ->setAllowedTypes('default_sort_order', 'string')
            ->setAllowedTypes('show_actions_column', 'boolean')
            ->setAllowedValues('default_sort_order', ['asc', 'desc']);
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Admin\Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param string $name
     * @param string $type A valid DatagridFieldType implementation name
     * @param array $options List of options for the given DatagridFieldType
     * @return $this
     */
    public function addField($name, $typeClass, $options = array())
    {
        $type = new $typeClass;
        $field = new DatagridField($name, $type, $options);

        $this->fields[] = $field;

        return $this;
    }

    /**
     * Return list of all fields configured for this datagrid
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param string $type A valid DatagridFilterType implementation name
     * @param array $options List of options for the given DatagridFilterType
     * @return $this
     */
    public function addFilter($name, $typeClass, $options = array())
    {
        $type = new $typeClass;
        $filter = new DatagridFilter($name, $type, $options);

        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Return list of all filters configured for this datagrid
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     *
     * @return bool
     */
    public function hasFilters()
    {
        return !empty($this->filters);
    }

    /**
     * @param string $name
     * @param string $type A valid DatagridActionType implementation name
     * @param array $options List of options for the given DatagridActionType
     */
    public function addAction($type, $route, $options = array())
    {
        // instanciate new field object of given type
        $action = new $type($route, $options);

        $this->actions[] = $action;

        return $this;
    }

    /**
     * Return list of all fields configured for this datagrid
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPagination()
    {
        if (!$this->pagination) {
            $this->pagination = $this->paginator->paginate(
                $this->getQueryBuilder(),
                $this->request->query->getInt('page', 1),
                $this->options['limit_per_page'],
                array('defaultSortFieldName' => 'e.'.$this->options['default_sort_column'], 'defaultSortDirection' => $this->options['default_sort_order'])
            );
        }

        return $this->pagination;
    }

    /**
     * Bind the Request to the Datagrid
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function bind(AdminInterface $admin, Request $request)
    {
        if ($this->request) {
            throw new \RuntimeException('A datagrid can only be bound once to a request');
        }

        // configure our datagrid
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($admin->getDatagridOptions());

        $this->admin = $admin;
        $this->request = $request;

        // attach our fields, filters and actions
        $this->admin->buildDatagrid($this);

        // Manage filters
        $this->buildFiltersForm();

        if ($this->filtersForm) {
            $this->filtersForm->handleRequest($this->request);

            /** @var DatagridFilter $filter */
            foreach ($this->filters as $filter) {
                $filter->setValue($this->filtersForm->get($filter->getName())->getData());
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     *
     */
    private function buildFiltersForm()
    {
        if (empty($this->filters)) {
            return;
        }

        $form = $this->factory->createBuilder(
            FormType::class,
            null,
            array(
                'method' => 'GET',
                'csrf_protection' => false,
            )
        );

        /** @var DatagridFilter $filter */
        foreach ($this->filters as $filter) {
            $filter->buildForm($form);
        }

        $form->add(
            'submit',
            SubmitType::class,
            ['label' => 'Filter']
        );

        $this->filtersForm = $form->getForm();
    }

    /**
     * Get the form used to filter the list of entities displayed in the datagrid
     *
     * @return null | FormInterface
     */
    public function getFiltersForm()
    {
        return $this->filtersForm;
    }

    /**
     * Get QueryBuilder to populate Datagrid
     *
     * @return QueryBuilder;
     */
    public function getQueryBuilder()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('e')
            ->from($this->admin->getEntityClass(), 'e')
            ->orderBy('e.id', 'DESC');

        $expr = $queryBuilder->expr()->andX();

        /** @var DatagridFilter $filter */
        $i=0;
        foreach ($this->filters as $filter) {
            $filterExprName = $filter->getExpression();
            // Value can be false but valid, it is only invalid when null.
            if ($filterExprName && !is_null($filter->getValue())) {
                $filterExpr = $queryBuilder->expr()->{$filterExprName}('e.'.$filter->getName(), ':param_'.$i);
                $queryBuilder->setParameter('param_'.$i, $filter->getValue());
                $expr->add($filterExpr);
                $i++;
            }
        }

        if ($expr->count() > 0) {
            $queryBuilder->where($expr);
        }

        return $queryBuilder;
    }
}
