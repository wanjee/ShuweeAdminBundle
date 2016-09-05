<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridFilterTypeChoice
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
class DatagridFilterTypeChoice extends DatagridFilterType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(array(
                'choices',
            ))
            ->setDefault('placeholder', 'All')
            ->setAllowedTypes('choices', ['array'])
            ->setAllowedTypes('placeholder', ['string']);
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
        return $rawValue;
    }

    /**
     * @inheritdoc
     */
    public function getCriteriaExpression()
    {
        return 'eq';
    }
}
