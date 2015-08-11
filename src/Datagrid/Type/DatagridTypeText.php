<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridTypeText
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
class DatagridTypeText extends DatagridType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(
                array(
                    'truncate' => 80,
                    'escape' => true,
                )
            )
            ->setAllowedTypes(
                array(
                    'truncate' => array('null', 'integer'),
                    'escape' => array('bool'),
                )
            );
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

        return array(
            'value' => $defaults['value'],
            'truncate' => $field->getOption('truncate', 80),
            'escape' => $field->getOption('escape', true),
        ) + $defaults;
    }
}