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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;

use Wanjee\Shuwee\AdminBundle\Admin\Admin;

/**
 * Class CrudController
 * @package Shuwee\AdminBundle\Controller
 */
class ContentController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function indexAction(Request $request, Admin $admin)
    {
        // FIXME : Secure access

        return $this->render('ShuweeAdminBundle:Content:index.html.twig', array(
            'admin' => $admin,
          )
        );
    }

    /**
     * Display (preview) a single entity
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function viewAction(Request $request, Admin $admin)
    {
        // FIXME : Secure access

        // load entity
        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        return $this->render('ShuweeAdminBundle:Content:view.html.twig', array(
            'admin' => $admin,
            'entity' => $entity,
          )
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function createAction(Request $request, Admin $admin)
    {
        // FIXME : Secure access

        return $this->render('ShuweeAdminBundle:Content:create.html.twig', array(
            'admin' => $admin,
          )
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function updateAction(Request $request, Admin $admin)
    {
        // FIXME : Secure access

        // load entity
        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        return $this->render('ShuweeAdminBundle:Content:update.html.twig', array(
            'admin' => $admin,
            'entity' => $entity,
          )
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function deleteAction(Request $request, Admin $admin)
    {
        // FIXME : Secure access
        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        return $this->render('ShuweeAdminBundle:Content:delete.html.twig', array(
            'admin' => $admin,
            'entity' => $entity,
          )
        );
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\RoutingHelper
     */
    public function getRoutingHelper()
    {
        return $this->container->get('shuwee_admin.routing_helper');
    }
}
