<?php

namespace Shuwee\AdminBundle\Admin;

class Admin implements AdminInterface
{
    /**
     * @var Model
     */
    protected $entity;

    /**
     * @var array
     */
    protected $listMapping;

    /**
     * @var boolean
     */
    protected $creatable = false;

    /**
     * @var boolean
     */
    protected $editable = false;

    /**
     * @var boolean
     */
    protected $deletable = false;

    /**
     * @var boolean
     */
    protected $previewable = false;

    /**
     * @var integer
     */
    protected $perPage = 25;

    /**
     *
     */
    public function setEntity()
    {


    }

    /**
     *
     */
    public function setListMapping()
    {

    }

    /**
     * @return boolean
     */
    public function isCreatable()
    {
        return $this->creatable;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * @return boolean
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    /**
     * @return boolean
     */
    public function isPreviewable()
    {
        return $this->previewable;
    }

    /**
     * @return boolean
     */
    public function getPerPage()
    {
        return $this->perPage();
    }
}