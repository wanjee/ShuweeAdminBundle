<?php

namespace Wanjee\Shuwee\AdminBundle\Section;


/**
 * Class SectionItem
 * @package Wanjee\Shuwee\AdminBundle\Section
 */
class SectionItem
{
    /**
     * @var
     */
    protected $id;
    /**
     * @var
     */
    protected $label;
    /**
     * @var
     */
    protected $controller;


    /**
     * @param $id
     * @param $label
     * @param $controller
     */
    public function __construct($id, $label, $controller)
    {
        // validate id (can contain only letters and numbers)
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $id)) {
            throw new Exception('SectionItem ID is not a valid ID.  A valid id starts with a letter or underscore, followed by any number of letters, numbers, or underscores.');
        }

        $this->id = $id;
        $this->label = $label;
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }
}