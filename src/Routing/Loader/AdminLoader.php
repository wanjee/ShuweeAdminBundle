<?php

namespace Wanjee\Shuwee\AdminBundle\Routing\Loader;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Wanjee\Shuwee\AdminBundle\Manager\AdminManager;
use Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper;

/**
 * Class AdminLoader
 * @package Wanjee\Shuwee\AdminBundle\Routing\Loader
 */
class AdminLoader extends Loader
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager
     */
    private $adminManager;

    /**
     * @var AdminRoutingHelper
     */
    private $routingHelper;

    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager
     */
    public function __construct(AdminManager $adminManager, AdminRoutingHelper $routingHelper)
    {
        $this->adminManager = $adminManager;
        $this->routingHelper = $routingHelper;
    }

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "shuwee_admin_extra" loader twice');
        }

        $routeCollection = new RouteCollection();
        foreach ($this->adminManager->getAdmins() as $alias => $admin) {
            // List
            $routeCollection->add(
                $this->routingHelper->getRouteName($admin, 'index'),
                $this->routingHelper->getRoute($admin, 'index', [], true)
            );

            // Create
            $routeCollection->add(
                $this->routingHelper->getRouteName($admin, 'create'),
                $this->routingHelper->getRoute($admin, 'create')
            );

            // Update
            $routeCollection->add(
                $this->routingHelper->getRouteName($admin, 'update'),
                $this->routingHelper->getRoute($admin, 'update', ['id'])
            );

            // Delete
            $routeCollection->add(
                $this->routingHelper->getRouteName($admin, 'delete'),
                $this->routingHelper->getRoute($admin, 'delete', ['id'])
            );

            // Toggle field
            $routeCollection->add(
                $this->routingHelper->getRouteName($admin, 'toggle'),
                $this->routingHelper->getRoute($admin, 'toggle', ['id', 'field', 'token']
                )
            );
        }

        $this->loaded = true;

        return $routeCollection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string $type The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'shuwee_admin_extra' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
        // Required but can be empty
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // Required but can be empty
    }
}
