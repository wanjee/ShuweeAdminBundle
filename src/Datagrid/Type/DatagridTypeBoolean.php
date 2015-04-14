<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;


class DatagridTypeBoolean extends DatagridType
{
    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_boolean';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_boolean';
    }

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
            'value' => (bool) $field->getData($entity),
        );
    }
}