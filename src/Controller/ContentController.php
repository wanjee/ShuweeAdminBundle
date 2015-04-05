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
use Symfony\Component\HttpFoundation\Request;
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

        // prepare entity
        $entityClass = $admin->getEntityClass();
        $entity = new $entityClass();

        $form = $this->getCreateForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'created');
            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render('ShuweeAdminBundle:Content:create.html.twig', array(
            'admin' => $admin,
            'form' => $form->createView(),
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

        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        $form = $this->getUpdateForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'updated');
            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render('ShuweeAdminBundle:Content:update.html.twig', array(
            'admin' => $admin,
            'entity' => $entity,
            'form' => $form->createView(),
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

        $form = $this->getDeleteForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'deleted');
            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render('ShuweeAdminBundle:Content:delete.html.twig', array(
            'admin' => $admin,
            'entity' => $entity,
            'form' => $form->createView(),
          )
        );
    }

    /**
     * Get form to create a new entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getCreateForm(Admin $admin, $entity)
    {
        $formClass = $admin->getForm();
        $formType = new $formClass();

        $form = $this->createForm($formType, $entity, array(
          'action' => $this->getAdminRoutingHelper()->generateUrl($admin, 'create'),
          'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Get form to update an existing entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getUpdateForm(Admin $admin, $entity)
    {
        $formClass = $admin->getForm();
        $formType = new $formClass();

        $form = $this->createForm($formType, $entity, array(
          'action' => $this->getAdminRoutingHelper()->generateUrl($admin, 'update', array('id' => $entity->getId())),
          'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Get form to update an existing entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getDeleteForm(Admin $admin, $entity)
    {
        return $this->createFormBuilder()
          ->setAction($this->getAdminRoutingHelper()->generateUrl($admin, 'delete', array('id' => $entity->getId())))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
          ->getForm();
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\RoutingHelper
     */
    public function getAdminRoutingHelper()
    {
        return $this->container->get('shuwee_admin.admin_routing_helper');
    }
}
