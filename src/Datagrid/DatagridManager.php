<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Wanjee\Shuwee\AdminBundle\Datagrid\Type\DatagridTypeInterface;

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
     * @param string $alias
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Type\DatagridTypeInterface $type
     */
    public function registerType($alias, DatagridTypeInterface $type)
    {
        $this->types[$alias] = $type;
    }

    /**
     * @param $alias
     * @return \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getType($alias)
    {
        if (!array_key_exists($alias, $this->types)) {
            throw new \InvalidArgumentException(sprintf('The type %s has not been registered with the datagrid', $alias));
        }
        return $this->types[$alias];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}