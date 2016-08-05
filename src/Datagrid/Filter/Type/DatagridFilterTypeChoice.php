<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridFilterTypeText
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
class DatagridFilterTypeText extends DatagridFilterType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // add optional other stuff

    }

    /**
     * @inheritdoc
     */
    public function getFormType()
    {
        return ChoiceType::class;
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
        return 'eq';
    }
}
