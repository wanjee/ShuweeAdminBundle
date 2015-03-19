<?php

/**
 * Symfony controller for all CRUD related actions.
 *
 * PHP Version 5
 *
 * @category CategoryName
 * @package  Shuwee\AdminBundle\Controller
 * @author   Wanjee <wanjee.be@gmail.com>
 *
 */

namespace Shuwee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Shuwee\AdminBundle\Admin;

/**
 * Class CRUDController
 * @package Shuwee\AdminBundle\Controller
 */
class CRUDController extends Controller
{
    /**
     * @var AdminInterface
     */
    protected $admin;

    /**
     *
     */
    public function createAction()
    {
        if (false === $this->admin->isCreatable()) {
            throw new AccessDeniedException();
        }
    }

    /**
     *
     */
    public function listAction()
    {

    }

    /**
     *
     */
    public function editAction()
    {
        if (false === $this->admin->isEditable()) {
            throw new AccessDeniedException();
        }
    }

    /**
     *
     */
    public function deleteAction()
    {
        if (false === $this->admin->isDeleteable()) {
            throw new AccessDeniedException();
        }
    }

}
