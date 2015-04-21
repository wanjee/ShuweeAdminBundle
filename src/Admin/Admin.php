<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Admin
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
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
        $routingHelper = $this->getAdminRoutingHelper();

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
     * By default, grant access
     *
     * @param UserInterface $user
     * @param string $attribute
     * @param mixed $object
     * @return integer either VoterInterface::ACCESS_GRANTED, VoterInterface::ACCESS_ABSTAIN, or VoterInterface::ACCESS_DENIED
     */
    public function isGranted(UserInterface $user, $attribute, $object)
    {
        return VoterInterface::ACCESS_GRANTED;
    }

    /**
     * Load multiple entities
     *
     * @param int $id
     */
    public function loadEntities()
    {
        return $this->getEntityManager()->getRepository($this->getEntityClass())->findAll();
    }

    /**
     * Load a single entity by its id
     *
     * @param int $id
     */
    public function loadEntity($id)
    {
        return $this->getEntityManager()->getRepository($this->getEntityClass())->find($id);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getDatagridManager()
    {
        return $this->container->get('shuwee_admin.datagrid_manager');
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper
     */
    public function getAdminRoutingHelper()
    {
        return $this->container->get('shuwee_admin.admin_routing_helper');
    }
}