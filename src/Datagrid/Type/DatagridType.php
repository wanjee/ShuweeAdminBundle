<?php
namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridType
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
abstract class DatagridType implements DatagridTypeInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                array(
                    'default_value' => null,
                )
            )
            ->setDefined(
                array(
                    'callback'
                )
            )
            ->setAllowedTypes('callback', 'callable');
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
        if ($field->getOption('callback')) {
            $value = call_user_func($field->getOption('callback'), $entity);
        }
        else {
            $value = $field->getData($entity);
        }

        return array(
            'value' => $value,
            'default_value' => $field->getOption('default_value', 'null'),
        );
    }
}
