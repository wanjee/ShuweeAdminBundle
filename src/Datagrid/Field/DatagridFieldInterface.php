<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Field;

interface DatagridFieldInterface
{
    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

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

    /**
     * @param mixed $row
     * @return mixed
     */
    public function getData($row);
}
