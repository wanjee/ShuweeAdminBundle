<?php

namespace Wanjee\Shuwee\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ShuweeAdminExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Prepend configuration to external bundles used by ShuweeAdminbundle
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // configure filter_sets for liip_imagine bundle
        $config = [
            'filter_sets' => [
                'datagrid_thumb' => [
                   'quality' => 85,
                    'filters' => [
                        'thumbnail' => [
                            'size' => [200, 75],
                            'mode' => 'outbound',
                        ],
                    ],
                ],
                'form_file_preview' => [
                   'quality' => 85,
                    'filters' => [
                        'thumbnail' => [
                            'size' => [200, 200],
                            'mode' => 'inset',
                        ],
                    ],
                ],
            ],
        ];

        $container->prependExtensionConfig('liip_imagine', $config);
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
