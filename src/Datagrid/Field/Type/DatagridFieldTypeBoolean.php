<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;

class DatagridFieldTypeBoolean extends DatagridFieldType
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
                    'label_true' => 'Yes',
                    'label_false' => 'No',
                    'toggle' => false,
                ]
            )
            ->setAllowedTypes('label_true', ['null', 'string'])
            ->setAllowedTypes('label_false', ['null', 'string'])
            ->setAllowedTypes('toggle', ['boolean']);
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

        return [
            'value' => (bool) $defaults['value'],
            'label_true' => $field->getOption('label_true', 'Yes'),
            'label_false' => $field->getOption('label_false', 'No'),
            'toggle' => $field->getOption('toggle', false),
        ] + $defaults;
    }
}
