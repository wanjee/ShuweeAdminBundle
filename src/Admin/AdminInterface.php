<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface AdminInterface
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
interface AdminInterface
{
    /**
     * Get Create/Edit form (Form type class or service key if you defined you form as a service)
     */
    public function getForm();

    /**
     * Get datagrid configuration
     */
    public function getDatagrid();

    /**
     * Get entity class (ie.: Acme\BlogBundle\Entity\Post)
     *
     * @return string
     */
    public function getEntityClass();

    /**
     * Get label of the entity (singular and multiple forms).
     * Must follow the transchoice syntax (ie.: {0} Posts|{1} Post|]1,Inf] %count% posts)
     * or be a single form string
     *
     * @return string
     */
    public function getLabel();

    /**
     * Get Create/Edit form (Form type class or service key if you defined you form as a service)
     */
    public function getAlias();

    /**
     * Define the parent of the menu item
     *
     * @return string
     */
    public function getMenuSection();

    /**
     * Get options for this Admin
     *
     * @return array List of options to configure
     */
    public function getOptions();

    /**
     * Get grants for current user, a given action on a dedicated object
     *
     * @param string $name
     * @param object|null $object
     *
     * @return boolean
     */
    public function isGranted($name, $object = null);

    /**
     * Content voter callback.
     * For a given user, a given attribute (action to take) and a given object
     * it returns user authorization.
     * This should not be called directly
     *
     * @param UserInterface $user
     * @param string $attribute
     * @param mixed $object
     * @return integer either VoterInterface::ACCESS_GRANTED, VoterInterface::ACCESS_ABSTAIN, or VoterInterface::ACCESS_DENIED
     */
    public function hasAccess(UserInterface $user, $action, $object = null);

    /**
     * @param mixed $entity
     */
    public function preUpdate($entity);

    /**
     * @param mixed $entity
     */
    public function postUpdate($entity);

    /**
     * @param mixed $entity
     */
    public function prePersist($entity);

    /**
     * @param mixed $entity
     */
    public function postPersist($entity);

    /**
     * @param mixed $entity
     */
    public function preRemove($entity);

    /**
     * @param mixed $entity
     */
    public function postRemove($entity);
}
