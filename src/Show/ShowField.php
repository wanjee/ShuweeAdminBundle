<?php

namespace Wanjee\Shuwee\AdminBundle\Show;

/**
 *
 */
class ShowField
{
    private $name;
    private $title;
    private $type;
    private $filterable;
    private $sortable;

    /**
     * Constructor
     * @param $name of the related field
     * @param $title of the column
     * @param $type of the field
     * @param boolean $filterable field
     * @param boolean $sortable field
     */
    public function __construct($name, $type = NULL, array $options = array()) {
        $this->setName($name);
        $this->setType($type);
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Field
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Field
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set filterable
     *
     * @param boolean $filterable
     * @return Field
     */
    public function setFilterable($filterable) {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * Get filterable
     *
     * @return boolean
     */
    public function isFilterable() {
        return $this->filterable;
    }

    /**
     * Set sortable
     *
     * @param string $sortable
     * @return Field
     */
    public function setSortable($sortable) {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Get sortable
     *
     * @return string
     */
    public function isSortable() {
        return $this->sortable;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     *
     * @param string $title
     * @return Field
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }
}
