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
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface $type
     * @param string $alias
     */
    public function registerType(DatagridFieldTypeInterface $type, $alias = null)
    {
        $this->types[get_class($type)] = $type;
        if (null !== $alias) {
            $this->alias[$alias] = get_class($type);
        }
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
            @trigger_error('Alias is deprecated and should be replaced by it\'s FQN', E_USER_DEPRECATED);
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
