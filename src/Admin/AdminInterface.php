<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;

/**
 * Interface AdminInterface
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
interface AdminInterface
{
    /**
     * Get entity class (ie.: Acme\BlogBundle\Entity\Post)
     *
     * @return string
     */
    public function getEntityClass();

    /**
     * Get Create/Edit form (Form type class or service key if you defined you form as a service)
     */
    public function getForm();

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Check if a given option has been defined
     * @param $name
     * @return mixed
     */
    public function hasOption($name);

    /**
     * Get value for a given option, or the default value if none is set
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null);

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function buildDatagrid(DatagridInterface $datagrid, EntityManager $em);

    /**
     * @return array Options
     */
    public function getOptions();

    /**
     * @return array Options for the datagrid
     */
    public function getDatagridOptions();

    /**
     * Content voter callback.
     * For a given user, a given attribute (action to take) and a given object
     * it returns user authorization.
     * This should not be called directly
     *
     * @param UserInterface $user
     * @param string $action
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

    /**
     * @param mixed $form
     */
    public function preCreateFormRender($form);
}
