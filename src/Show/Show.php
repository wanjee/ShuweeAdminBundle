<?php

namespace Wanjee\Shuwee\AdminBundle\Show;

/**
 * Manage a list of items
 */
class Show
{
    private $fields = [];

    public function add($name, $type = NULL, array $options = array())
    {
        $this->fields[] = new ShowField($name, $type, $options);
        return $this;
    }
}