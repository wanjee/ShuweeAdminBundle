<?php

/**
 * @author Wanjee <wanjee.be@gmail.com>
 */

namespace Wanjee\Shuwee\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wanjee\Shuwee\AdminBundle\DependencyInjection\Compiler\AdminCompilerPass;

class ShuweeAdminBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AdminCompilerPass());
    }
}