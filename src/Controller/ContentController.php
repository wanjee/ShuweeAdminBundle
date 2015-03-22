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
class ContentController extends Controller
{
    /**
     * @var AdminInterface
     */
    protected $admin;

    /**
     */
    public function indexAction()
    {
        return $this->render('ShuweeAdminBundle:Content:index.html.twig');
    }

    /**
     */
    public function viewAction()
    {
        return $this->render('ShuweeAdminBundle:Content:view.html.twig');
    }

    /**
     */
    public function createAction()
    {
        return $this->render('ShuweeAdminBundle:Content:create.html.twig');
    }

    /**
     *
     */
    public function updateAction()
    {
        return $this->render('ShuweeAdminBundle:Content:update.html.twig');
    }

    /**
     *
     */
    public function deleteAction()
    {
        return $this->render('ShuweeAdminBundle:Content:delete.html.twig');
    }

}
