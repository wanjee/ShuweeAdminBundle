<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridFieldTypeImage
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type
 */
class DatagridFieldTypeImage extends DatagridFieldType
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
                    'base_path' => 'uploads',
                )
            )
            ->setAllowedTypes('base_path', 'string');
    }

    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_image';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_image';
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

        $base_path = $field->getOption('base_path', 'uploads');
        $image = $defaults['value'];

        $value = null;
        if (!empty($image)) {
            $value = $base_path . '/' . $image;
        }

        return array(
            'value' => $value,
        ) + $defaults;
    }
}
