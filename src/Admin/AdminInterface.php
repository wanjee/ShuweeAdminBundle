<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

interface AdminInterface
{
    /**
     * Get Create/Edit form (form class or \Symfony\Component\Form\Form instance)
     */
    public function getForm();

    /**
     * Get list configuration
     */
    public function getList();

    /**
     * Get entity shortcut name (ie.:  AcmeBlogBundle:Post)
     *
     * @return string
     */
    public function getEntityName();

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
}