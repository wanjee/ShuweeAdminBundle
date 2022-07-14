<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectManager as LegacyObjectManager;

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
                    'choice_label',
                    'group_by',
                    'em',
                    'query_builder',
                ]
            )
            ->setDefaults([
                'placeholder' => 'All',
                'group_by' => null,
                'em' => null,
                'query_builder' => null,
            ])
            ->setAllowedTypes('placeholder', ['string'])
            ->setAllowedTypes('class', ['string'])
            ->setAllowedTypes('choice_label', ['string', 'callable'])
            ->setAllowedTypes('group_by', ['null', 'string'])
            ->setAllowedTypes('em', ['null', 'string', ObjectManager::class, LegacyObjectManager::class])
            ->setAllowedTypes('query_builder', ['null', 'callable', 'Doctrine\ORM\QueryBuilder']);
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
