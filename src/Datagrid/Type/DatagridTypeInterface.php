<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;

/**
 * Interface DatagridTypeInterface
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
interface DatagridTypeInterface
{
    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName();

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName();

    /**
     * Get prepared view parameters
     *
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface $field
     * @param mixed $entity Instance of a model entity
     *
     * @return mixed
     */
    public function getBlockVariables($field, $entity);
}