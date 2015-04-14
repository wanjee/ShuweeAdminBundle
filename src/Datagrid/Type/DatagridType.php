<?php
namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;


/**
 * Class DatagridType
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
abstract class DatagridType implements DatagridTypeInterface
{
    /**
     * Get prepared view parameters
     *
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface $field
     * @param mixed $entity Instance of a model entity
     *
     * @return mixed
     */
    public function getBlockVariables($field, $entity)
    {
        return array(
            'value' => $field->getData($entity),
        );
    }
}