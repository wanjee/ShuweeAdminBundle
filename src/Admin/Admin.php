<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;

/**
 * Class Admin
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
abstract class Admin implements AdminInterface
{

    /**
     * Cache for grants
     * @var array
     */
    protected $cacheIsGranted = array();

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
     * Get preview url using defined callback, if any
     *
     * @return callable|null
     */
    public function getPreviewUrlCallback()
    {
        return null;
    }

    /**
     * Does the current admin implements a previewUrlCallback function
     *
     * @return bool True if current admin implements a previewUrlCallback function
     */
    public function hasPreviewUrlCallback()
    {
        return is_callable($this->getPreviewUrlCallback());
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {}

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFields(DatagridInterface $datagrid) {}

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFilters(DatagridInterface $datagrid) {}

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachActions(DatagridInterface $datagrid) {}

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSection()
    {
        return 'content';
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
}
