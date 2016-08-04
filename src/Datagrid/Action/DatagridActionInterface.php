<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Action;

interface DatagridActionInterface
{
    /**
     * @return string
     */
    public function getRoute();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null);
}
