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
     * @var string
     */
    protected $alias;

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
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
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
        $routeCollection->add(
          $routingHelper->getRouteName($this, 'view'),
          $routingHelper->getRoute($this, 'view', array('id'))
        );

        // Create
        $routeCollection->add(
          $routingHelper->getRouteName($this, 'create'),
          $routingHelper->getRoute($this, 'create')
        );

        // Update
        $routeCollection->add(
          $routingHelper->getRouteName($this, 'update'),
          $routingHelper->getRoute($this, 'update', array('id'))
        );

        // Delete
        $routeCollection->add(
          $routingHelper->getRouteName($this, 'delete'),
          $routingHelper->getRoute($this, 'delete', array('id'))
        );

    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\RoutingHelper
     */
    public function getRoutingHelper()
    {
        return $this->container->get('shuwee_admin.routing_helper');
    }
}