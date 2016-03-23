<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface DatagridInterface
{
    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Get implementation of Admin to use in this datagrid
     *
     * @return \Wanjee\Shuwee\AdminBundle\Admin\Admin
     */
    public function getAdmin();

    /**
     * Add a field to the datagrid.
     *
     * @param string $name
     * @param string $type A valid DatagridFieldType implementation name
     * @param array $options List of options for the given DatagridFieldType
     */
    public function addField($name, $type, $options = array());

    /**
     * Return list of all fields configured for this datagrid
     */
    public function getFields();

    /**
     * Returns the pagination object
     *
     * @return
     */
    public function getPagination();

    /**
     * Bind the Request to the Datagrid
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function bind(Request $request);
}
