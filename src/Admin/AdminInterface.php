<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

interface AdminInterface
{
    // Get Create/Edit form (form class or \Symfony\Component\Form\Form)
    public function getForm();

    // Get list configuration
    public function getList();

    /**
     * Get name of the entity (singular form)
     *
     * @return string
     */
    public function getEntityName();

    /**
     * Get name of the entity (plural form)
     *
     * @return string
     */
    public function getLabel();

    /**
     * Get name of the entity (plural form)
     *
     * @return string
     */
    public function getLabelPlural();

    /**
     * Get entity class
     *
     * @return string
     */
    public function getEntityClass();


}