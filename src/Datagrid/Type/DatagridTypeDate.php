<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridTypeDate
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
class DatagridTypeDate extends DatagridType
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
            ->setAllowedTypes(
                array(
                    'date_format' => array('string'),
                )
            );
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

        $date = $field->getData($entity);

        if (!$date instanceof \DateTime) {
            throw new \Exception(sprintf('Datagrid \'date\' type expects an instance of Datetime. \'%s\' given for field \'%s\'.', gettype($date), $field->getName()));
        }

        return array(
          'value' => $date->format($date_format),
          'datetime' => $date->format(\DateTime::RFC3339),
        ) + $defaults;
    }
}