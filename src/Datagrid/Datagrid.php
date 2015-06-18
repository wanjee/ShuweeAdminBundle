<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Symfony\Component\HttpFoundation\Request;
use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridField;
use Wanjee\Shuwee\AdminBundle\Datagrid\Datasource\DatasourceInterface;

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
     * @var \Wanjee\Shuwee\AdminBundle\Datagrid\Datasource\DatasourceInterface
     */
    protected $dataSource;

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

        return $this;
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Datasource\DatasourceInterface $datasource
     */
    public function setDatasource(DatasourceInterface $datasource)
    {
        $this->dataSource = $datasource;

        return $this;
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\Datasource\DatasourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @return int
     */
    public function getPaginator()
    {
        return $this->dataSource->getPaginator();
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function bind(Request $request)
    {
        $query = $request->query->all();

        if (isset($query['page'])) {
            $this->dataSource->setPage($query['page']);
        }
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getDatagridManager() {
        return $this->admin->getDatagridManager();
    }
}