<?php

namespace Wanjee\Shuwee\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DatagridCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('shuwee_admin.datagrid_manager')) {
            return;
        }

        $definition = $container->getDefinition('shuwee_admin.datagrid_manager');

        $taggedServices = $container->findTaggedServiceIds('shuwee.datagrid_type');

        foreach ($taggedServices as $id => $tagAttributes) {
            $definition->addMethodCall('registerType', array(new Reference($id)));
        }
    }
}
