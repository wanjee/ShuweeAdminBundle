<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * Class DatagridFilterTypeText
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
class DatagridFilterTypeText extends DatagridFilterType
{
    /**
     * @inheritdoc
     */
    public function getFormType()
    {
        return TextType::class;
    }

    /**
     * @inheritdoc
     */
    public function formatValue($rawValue)
    {
        return '%'.$rawValue.'%';
    }

    /**
     * @inheritdoc
     */
    public function getCriteriaExpression()
    {
        return 'like';
    }
}
