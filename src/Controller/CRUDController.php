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

namespace Wanjee\Shuwee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wanjee\Shuwee\AdminBundle\Admin;

/**
 * Class CrudController
 * @package Shuwee\AdminBundle\Controller
 */
class CrudController extends Controller
{
    /**
     * @var AdminInterface
     */
    protected $admin;

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Template()
     */
    public function viewAction()
    {
        if (false === $this->admin->isCreatable()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @Template()
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
