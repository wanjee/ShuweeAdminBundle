<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatagridTypeCollection
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
class DatagridTypeCollection extends DatagridType
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
                )
            )
            ->setAllowedTypes(
                array(
                    'truncate' => array('null', 'integer'),
                )
            );
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

        $data = $field->getData($entity);

        if ($data instanceof \Traversable) {
            $dataArray = [];
            foreach ($data as $element) {
                $dataArray[] = $element->__toString();
            }

            $string = implode(', ', $dataArray);
        } else {
            $string = 'Unsupported.';
        }

        return array(
            'value' => $string,
            'truncate' => $field->getOption('truncate', 80),
        ) + $defaults;
    }
}
