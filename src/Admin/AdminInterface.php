<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

interface AdminInterface
{
    // Get Create/Edit form (form class or \Symfony\Component\Form\Form)
    public function getForm();

    // Get list configuration
    public function getList();

    /**
     * Get name of the entity
     *
     * @return string
     */
    public function getEntityName($entity);

    /**
     * Get entity class
     *
     * @return string
     */
    public function getEntityClass();


}