<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridFieldTypeDate
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type
 */
class DatagridFieldTypeDate extends DatagridFieldType
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
                    'date_format' => 'F j, Y',
                )
            )
            ->setAllowedTypes('date_format', 'string');
    }

    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_date';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_date';
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

        // Date_format should be any format supported by http://php.net/manual/en/function.date.php
        $date_format = $field->getOption('date_format', 'c');

        $date = $defaults['value'];

        if (!$date instanceof \DateTime) {
            $variables = array(
                'value' => null,
                'datetime' => null,
            );
        }
        else {
            $variables = array(
                'value' => $date->format($date_format),
                'datetime' => $date->format(\DateTime::RFC3339),
            );
        }

        return $variables + $defaults;
    }
}
