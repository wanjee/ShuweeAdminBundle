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
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface $type
     * @param string $alias
     */
    public function registerType(DatagridFieldTypeInterface $type)
    {
        $this->types[get_class($type)] = $type;
    }

    /**
     * @param $alias
     * @return \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getType($type)
    {
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
