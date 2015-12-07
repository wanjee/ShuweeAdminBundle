<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
     * Cache for grants
     * @var array
     */
    protected $cacheIsGranted = array();

    /**
     * List of options values
     * @var array
     */
    protected $options;

    /**
     * Configure this admin
     */
    public function __construct() {
        // Manage options
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($this->getOptions());

        return $this;
    }

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
     * Add all admin routes to the routeCollection
     *
     * @param RouteCollection $routeCollection
     */
    public function configureRoutes(RouteCollection $routeCollection)
    {
        $routingHelper = $this->getAdminRoutingHelper();

        // List
        $routeCollection->add(
            $routingHelper->getRouteName($this, 'index'),
            $routingHelper->getRoute($this, 'index', array(), true)
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
     * Configure options
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            // All options should have default values to avoid forcing
            // all existing Admin implementations
            // to be updated when ShuweeAdminBundle is.
            ->setDefaults(
                array(
                    'preview_url_callback' => null,
                )
            )
            ->setAllowedTypes(
                array(
                    'preview_url_callback' => array('callable', 'null'),
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions() {
        // Relies on default value configured in $this->configureOptions()
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSection()
    {
        return 'content';
    }

    /**
     * Check
     * {@inheritdoc}
     */
    public function isGranted($name, $object = null)
    {
        $key = md5(json_encode($name) . ($object ? '/' . spl_object_hash($object) : ''));

        if (!array_key_exists($key, $this->cacheIsGranted)) {
            if (is_null($object)) {
                // object is required at least to get the class to check permissions against in ContentVoter
                $entityClass = $this->getEntityClass();
                $object = new $entityClass();
            }
            $this->cacheIsGranted[$key] = $this->getAuthorizationChecker()->isGranted($name, $object);
        }

        return $this->cacheIsGranted[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function hasAccess(UserInterface $user, $action, $object = null)
    {
        // allow access by default
        return VoterInterface::ACCESS_GRANTED;
    }


    /**
     * Get basic QueryBuilder to populate Datagrid
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder;
     */
    public function getQueryBuilder()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('e')
            ->from($this->getEntityClass(), 'e')
            ->orderBy('e.id', 'DESC');

        return $queryBuilder;
    }

    /**
     * Does the current admin implements a previewUrlCallback function
     *
     * @return bool True if current admin implements a previewUrlCallback function
     */
    public function hasPreviewUrlCallback()
    {
        return is_callable($this->options['preview_url_callback']);
    }

    /**
     * Get preview url using defined callback, if any
     *
     * @param mixed $entity
     *
     * @return string Preview URL for the given entity
     */
    public function getPreviewUrl($entity)
    {
        if (!$this->hasPreviewUrlCallback()) {
            return null;
        }

        return call_user_func($this->options['preview_url_callback'], $entity);
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
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    public function getAuthorizationChecker()
    {
        return $this->container->get('security.authorization_checker');
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

    /**
     * @return \Knp\Component\Pager\Paginator
     */
    public function getKnpPaginator()
    {
        return $this->container->get('knp_paginator');
    }
}
