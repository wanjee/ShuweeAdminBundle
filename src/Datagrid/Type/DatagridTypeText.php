<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;


/**
 * Class DatagridTypeText
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
class DatagridTypeText extends DatagridType
{
    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_text';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_text';
    }
}