<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;


class DatagridTypeText extends DatagridType
{
    public function getName()
    {
        return 'datagrid_text';
    }

    public function getBlockName()
    {
        return 'datagrid_text';
    }
}