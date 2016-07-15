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
     * @return string
     */
    public function getAlias()
    {
        $fqnParts = explode('\\', get_class($this));
        $className = strtolower(end($fqnParts));

        return str_replace('admin', '', $className);
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
                    'description' => null,
                )
            )
            ->setAllowedTypes('preview_url_callback', ['callable', 'null'])
            ->setAllowedTypes('description', ['string', 'null']);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions() {
        // Must be extended in children classes
        // Relies otherwise on default value configured in $this->configureOptions()
        return array();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
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
     * {@inheritdoc}
     */
    public function preUpdate($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove($entity)
    {
        // Do nothing here, let Admin implementations define it if required
    }

    /**
     * @deprecated
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
     * @deprecated
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @deprecated
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridManager
     */
    public function getDatagridManager()
    {
        return $this->container->get('shuwee_admin.datagrid_manager');
    }

    /**
     * @deprecated
     * @return \Knp\Component\Pager\Paginator
     */
    public function getKnpPaginator()
    {
        return $this->container->get('knp_paginator');
    }
}
