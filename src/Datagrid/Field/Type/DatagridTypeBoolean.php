<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;

class DatagridTypeBoolean extends DatagridType
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
                    'label_true' => 'Yes',
                    'label_false' => 'No'
                )
            )
            ->setAllowedTypes('label_true', ['null', 'string'])
            ->setAllowedTypes('label_false', ['null', 'string']);
    }

    /**
     * Get administrative name of this type
     * @return string Name of the type
     */
    public function getName()
    {
        return 'datagrid_boolean';
    }

    /**
     * Get template block name for this type
     * @return string Block name (must be a valid block name as defined in Twig documentation)
     */
    public function getBlockName()
    {
        return 'datagrid_boolean';
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
            'value' => (bool) $defaults['value'],
            'label_true' => $field->getOption('label_true', 'Yes'),
            'label_false' => $field->getOption('label_false', 'No'),
        ) + $defaults;
    }
}
