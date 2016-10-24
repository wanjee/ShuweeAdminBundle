<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatagridEntityAction
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Action
 */
class DatagridEntityAction extends DatagridAbstractAction
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
                    'path_parameters' => function($entity) {
                        return array(
                            'entity' => $entity
                        );
                    },
                ]
            )
            ->setRequired('path_parameters')
            ->setAllowedTypes('path_parameters', ['null', 'callable'])
        ;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getRouteParameters($entity) {
        if (!is_callable($this->options['path_parameters'])) {
            return [];
        }

        return $this->options['path_parameters']($entity);
    }
}
