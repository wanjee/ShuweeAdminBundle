<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridField;

/**
 * Class Datagrid
 * @package Wanjee\Shuwee\AdminBundle\Datagrid
 */
class Datagrid implements DatagridInterface
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    protected $admin;

    /**
     * @var array List of available actions for this datagrid
     */
    protected $actions = array();

    /**
     * @var array List of available fields for this datagrid
     */
    protected $fields = array();

    /**
     * @var array List of available fields for this datagrid
     */
    protected $filters = array();

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * @var \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected $pagination;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Datagrid constructor.
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
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
        $admin->configureOptions($resolver);
        $this->options = $resolver->resolve();

        // attach our fields, filters and actions
        $admin->buildDatagrid($this);

        $this->admin = $admin;
        $this->request = $request;
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
     * Get basic QueryBuilder to populate Datagrid
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

        return $queryBuilder;
    }
}
