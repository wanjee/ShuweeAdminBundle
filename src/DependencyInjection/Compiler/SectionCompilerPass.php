<?php

namespace Wanjee\Shuwee\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SectionCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('shuwee_admin.section_manager')) {
            return;
        }

        $definition = $container->getDefinition('shuwee_admin.section_manager');

        $taggedServices = $container->findTaggedServiceIds('shuwee.admin_section');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $alias = isset($attributes['alias'])
                  ? $attributes["alias"]
                  : $id;

                $definition->addMethodCall('registerSection', array($alias, new Reference($id)));
            }
        }
    }
}