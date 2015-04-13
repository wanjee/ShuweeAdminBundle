<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Symfony\Component\HttpFoundation\Request;
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
     * @var array List of entities for this datagrid
     */
    protected $entities = array();

    /**
     *
     */
    function __construct(AdminInterface $admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Admin\Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
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
     * Set all entities
     * FIXME datagrid should be responsible for the entities retrieval as it will be for paging, sortering, filtering
     */
    public function setEntities($entities = array())
    {
        $this->entities = $entities;
    }

    /**
     * Return entities
     *
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request)
    {

    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getDatagridManager() {
        return $this->admin->getDatagridManager();
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getEntityClass() {
        return $this->admin->getEntityClass();
    }
}