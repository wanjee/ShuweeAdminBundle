<?php

namespace Wanjee\Shuwee\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdminCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('shuwee_admin.admin_manager')) {
            return;
        }

        $definition = $container->getDefinition('shuwee_admin.admin_manager');

        $taggedServices = $container->findTaggedServiceIds('shuwee.admin');

        foreach ($taggedServices as $id => $tagAttributes) {
            $definition->addMethodCall('registerAdmin', array(new Reference($id)));
        }
    }
}
