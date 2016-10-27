<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatagridFilterTypeEntity
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
class DatagridFilterTypeEntity extends DatagridFilterType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(
                [
                    'class',
                ]
            )
            ->setDefined(
                [
                    'choice_label'
                ]
            )
            ->setDefault('placeholder', 'All')
            ->setAllowedTypes('placeholder', ['string'])
            ->setAllowedTypes('class', ['string'])
            ->setAllowedTypes('choice_label', ['string', 'callable'])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getFormType()
    {
        return EntityType::class;
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
