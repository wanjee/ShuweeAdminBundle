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
                    'route_params' => function($entity) {
                        return array(
                            'entity' => $entity
                        );
                    },
                ]
            )
            ->setRequired('route_params')
            ->setAllowedTypes('route_params', ['null', 'callable'])
        ;
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getRouteParameters($entity) {
        return $this->options['route_params']($entity);
    }
}
