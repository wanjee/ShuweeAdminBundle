<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Type;
use Doctrine\ORM\PersistentCollection;

/**
 * Class DatagridTypeCollection
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Type
 */
class DatagridTypeCollection extends DatagridType
{
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
        $data = $field->getData($entity);
        $dataArray = [];

        if ($data instanceof \Traversable) {
            foreach ($data as $element) {
                $dataArray[] = $element->__toString();
            }
        } else {
            return array(
              'value' => 'Unsupported.'
            );
        }

        $string = join(', ', $dataArray);

        return array(
          'value' => $string
        );
    }
}
