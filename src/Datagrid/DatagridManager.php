<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface;

/**
 * Class DatagridManager
 * @package Wanjee\Shuwee\AdminBundle\Datagrid
 */
class DatagridManager
{

    /**
     * @var array list of field types
     */
    private $types = array();

    /**
     * @var array list of alias of field types
     * @deprecated
     */
    private $alias = array();

    /**
     * @param string $alias
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface $type
     */
    public function registerType($alias, DatagridFieldTypeInterface $type)
    {
        $this->types[get_class($type)] = $type;
        $this->alias[$alias] = $type;
    }

    /**
     * @param $alias
     * @return \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getType($type)
    {
        // is $type an alias?
        if (array_key_exists($type, $this->alias)) {
            $type = $this->alias[$type];
        }

        if (!array_key_exists($type, $this->types)) {
            throw new \InvalidArgumentException(sprintf('The type %s has not been registered with the datagrid', $type));
        }

        return $this->types[$type];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
