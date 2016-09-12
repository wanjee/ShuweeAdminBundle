<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatagridFieldTypeCollection
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type
 */
class DatagridFieldTypeCollection extends DatagridFieldType
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
                ]
            )
            ->setAllowedTypes('truncate', ['null', 'integer']);
    }

    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_collection';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_collection';
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

        $data = $defaults['value'];

        if ($data instanceof \Traversable) {
            $dataArray = [];
            foreach ($data as $element) {
                $dataArray[] = $element->__toString();
            }

            $string = implode(', ', $dataArray);
        } else {
            $string = 'Unsupported.';
        }

        return [
            'value' => $string,
            'truncate' => $field->getOption('truncate', 80),
        ] + $defaults;
    }
}
