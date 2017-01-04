<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridEntityAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridListAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridField;
use Wanjee\Shuwee\AdminBundle\Datagrid\Filter\DatagridFilter;
use Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeEntity;

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
    private $actions = [];

    /**
     * @var array List of available fields for this datagrid
     */
    private $fields = [];

    /**
     * @var array|DatagridFilter[] List of available filters for this datagrid
     */
    private $filters = [];

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var array
     */
    private $options;

    /**
     * @var null | \Symfony\Component\Form\FormInterface
     */
    private $filtersForm;

    /**
     * @var array
     */
    private $filterValues = [];

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
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * Datagrid constructor.
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\Form\FormFactory $factory
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $authorizationChecker
     */
    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $entityManager, FormFactory $factory, AuthorizationChecker $authorizationChecker)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
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
                [
                    'limit_per_page' => 10,
                    'default_sort_column' => 'id',
                    'default_sort_order' => 'asc',
                    'show_actions_column' => true,
                ]
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
     * @param string $typeClass A valid DatagridFieldType implementation name
     * @param array $options List of options for the given DatagridFieldType
     * @return $this
     */
    public function addField($name, $typeClass, $options = [])
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
     * @param string $typeClass A valid DatagridFilterType implementation name
     * @param array $options List of options for the given DatagridFilterType
     * @return $this
     */
    public function addFilter($name, $typeClass, $options = [])
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
     * @param string $type
     * @param string $route A valid DatagridActionType implementation name
     * @param array $options List of options for the given DatagridActionType
     * @return DatagridInterface
     */
    public function addAction($type, $route, $options = [])
    {
        // instanciate new field object of given type
        $action = new $type($route, $options);

        $this->actions[] = $action;

        return $this;
    }

    /**
     * Return list of all fields configured for this datagrid
     *
     * @return array
     */
    public function getListActions()
    {
        $entityClass = $this->getAdmin()->getEntityClass();
        $dummyObject = new $entityClass;
        return array_filter($this->actions, function($action) use ($dummyObject) {
            return $action instanceof DatagridListAction && $this->authorizationChecker->isGranted([get_class($action)], $dummyObject);
        });
    }

    /**
     * Return list of all fields configured for this datagrid
     *
     * @param $entity
     * @return array
     */
    public function getEntityActions($entity)
    {
        return array_filter($this->actions, function($action) use ($entity) {
            return $action instanceof DatagridEntityAction && $this->authorizationChecker->isGranted([get_class($action)], $entity);
        });
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
                ['defaultSortFieldName' => 'e.'.$this->options['default_sort_column'], 'defaultSortDirection' => $this->options['default_sort_order']]
            );
        }

        return $this->pagination;
    }

    /**
     * Bind the Request to the Datagrid
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
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

        $this->applyFilters();
    }

    /**
     * Prepare filters form, bind filter values to filters
     */
    private function applyFilters()
    {
        // Manage filters
        // Filter values come either from the form (GET)
        // Or from session
        $form = $this->getFiltersForm();

        if (!$form) {
            // No filters are configured for this admin
            return;
        }

        $this->loadFilterValues();
        // Init form with current data if any
        $form->setData($this->filterValues);

        $form->handleRequest($this->request);

        if ($form->isSubmitted()) {

            if ($form->get('reset')->isClicked()) {
                // Submitted for reset
                // Reset stored content
                $this->clearFilterValues();
                // Reset displayed values by rebuilding the form
                // yes, it's a pity we cannot simply change content of it
                $this->getFiltersForm(true);
            }
            else {
                // Submitted for filtering
                // Overwrite any stored values with the submitted ones
                // Update storage for subsequent requests
                $this->storeFilterValues($form->getData());
            }
        }

        // Map values, if any, to filters
        if (is_array($this->filterValues)) {
            foreach ($this->filters as $filter) {
                if (array_key_exists($filter->getName(), $this->filterValues)) {
                    $filter->setValue($this->filterValues[$filter->getName()]);
                }
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
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * Get the form used to filter the list of entities displayed in the datagrid
     *
     * @param bool $reset Force rebuild ?
     * @return null | \Symfony\Component\Form\FormInterface
     */
    public function getFiltersForm($reset = false)
    {
        if (!$this->filtersForm || $reset) {
            if (empty($this->filters)) {
                return null;
            }

            $form = $this->factory->createNamedBuilder(
                'filter',
                FormType::class,
                null,
                [
                    'csrf_protection' => false,
                    'method' => 'GET',
                ]
            );

            /** @var DatagridFilter $filter */
            foreach ($this->filters as $filter) {
                $filter->buildForm($form);
            }

            $form->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Filter',
                    'attr' => [
                        'class' =>'btn-success',
                    ]
                ]
            );

            $form->add(
                'reset',
                SubmitType::class,
                [
                    'label' => 'Reset'
                ]
            );

            $this->filtersForm = $form->getForm();
        }

        return $this->filtersForm;
    }

    /**
     * Get QueryBuilder to populate Datagrid
     *
     * @return \Doctrine\ORM\QueryBuilder;
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
        $i = 0;
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

    /**
     * Store values for filters so user can change page and keep his filters
     * Values are stored per admin
     */
    private function storeFilterValues(array $data)
    {
        $this->filterValues = $data;

        $session = new Session();

        foreach($this->filters as $datagridFilter) {
            if ($datagridFilter->getType() instanceof DatagridFilterTypeEntity) {
                if (!empty($this->filterValues[$datagridFilter->getName()])) {
                    $this->filterValues[$datagridFilter->getName()] = $this->filterValues[$datagridFilter->getName()]->getId();
                }
            }
        }

        $session->set($this->getStorageNamespace(), $this->filterValues);
    }

    /**
     * Retrieve values previously stored for filters
     * Values are stored per admin
     */
    private function loadFilterValues()
    {
        $session = new Session();
        $this->filterValues = $session->get($this->getStorageNamespace());
        // Because entities saved into the session are not currently managed,
        // we retrieve the reference id previously set by the storeFilterValues function
        // to load the expected entity
        foreach($this->filters as $datagridFilter) {
            if ($datagridFilter->getType() instanceof DatagridFilterTypeEntity) {
                if (!empty($this->filterValues[$datagridFilter->getName()])) {
                    $filterOptions = $datagridFilter->getOptions();
                    $this->filterValues[$datagridFilter->getName()] = $this->entityManager->getReference(
                        $filterOptions['class'],
                        $this->filterValues[$datagridFilter->getName()]
                    );
                }
            }
        }
    }

    /**
     * Clear values previously stored for filters
     * Values are stored per admin
     */
    private function clearFilterValues()
    {
        $session = new Session();
        $session->remove($this->getStorageNamespace());

        $this->filterValues = [];
    }

    /**
     * @return string
     */
    private function getStorageNamespace()
    {
        return 'datagrid:' . $this->admin->getAlias();
    }
}
