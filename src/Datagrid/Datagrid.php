<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

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
     * @var array List of available fields for this datagrid
     */
    protected $fields = array();

    /**
     * @var \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected $pagination;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var array
     */
    protected $options;

    /**
     *
     */
    public function __construct(AdminInterface $admin, $options = array())
    {
        $this->admin = $admin;

        // Manage options
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        return $this;
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
                )
            )
            ->setAllowedTypes(
                array(
                    'limit_per_page' => array('integer'),
                    'default_sort_column' => array('string'),
                    'default_sort_order' => array('string'),
                )
            )
            ->setAllowedValues(
                array(
                    'default_sort_order' => array('asc', 'desc'),
                )
            );
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
     * @param string $type A valid DatagridType implementation name
     * @param array $options List of options for the given DatagridType
     */
    public function addField($name, $type, $options = array())
    {
        // instanciate new field object of given type
        $type = $this->getDatagridManager()->getType($type);

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
     * @return
     */
    public function getPagination()
    {
        $this->initialize();

        return $this->pagination;
    }

    /**
     * Load the collection
     *
     */
    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        // Handle pagination & order
        $paginator  = $this->admin->getKnpPaginator();

        $this->pagination = $paginator->paginate(
            $this->admin->getQueryBuilder(),
            $this->request->query->getInt('page', 1),
            $this->options['limit_per_page'],
            array('defaultSortFieldName' => 'e.'.$this->options['default_sort_column'], 'defaultSortDirection' => $this->options['default_sort_order'])
        );

        $this->initialized = true;
    }

    /**
     * Bind the Request to the Datagrid
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function bind(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getDatagridManager() {
        return $this->admin->getDatagridManager();
    }
}
