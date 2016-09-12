<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridFieldTypeText
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type
 */
class DatagridFieldTypeText extends DatagridFieldType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(
                [
                    'truncate' => 80,
                    'escape' => true,
                ]
            )
            ->setAllowedTypes('truncate', ['null', 'integer'])
            ->setAllowedTypes('escape', 'bool');
    }

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
        $defaults = parent::getBlockVariables($field, $entity);

        return [
            'value' => $defaults['value'],
            'truncate' => $field->getOption('truncate', 80),
            'escape' => $field->getOption('escape', true),
        ] + $defaults;
    }
}
