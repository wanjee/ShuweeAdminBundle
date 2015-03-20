<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

abstract class Admin implements AdminInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     *
     */
    public function configureRoutes(RouteCollection $routeCollection)
    {
        $routingHelper = $this->getRoutingHelper();

        // List
        $routeCollection->add(
          $routingHelper->getRouteName($this, 'index'),
          $routingHelper->getRoute($this, 'index', array(), true)
        );

        // View

        // Create

        // Update

        // Delete

    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\RoutingHelper
     */
    public function getRoutingHelper()
    {
        return $this->container->get('shuwee_admin.routing_helper');
    }
}