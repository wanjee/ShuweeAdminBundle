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
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Wanjee\Shuwee\AdminBundle\Admin\Admin;
use Wanjee\Shuwee\AdminBundle\Security\Voter\ContentVoter;

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
        $this->secure($admin, ContentVoter::LIST_CONTENT);

        /** @var \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid $datagrid */
        $datagrid = $admin->getDatagrid();
        $datagrid->bind($request);

        return $this->render(
            'ShuweeAdminBundle:Content:index.html.twig',
            array(
                'admin' => $admin,
                'datagrid' => $datagrid,
            )
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     */
    public function createAction(Request $request, Admin $admin)
    {
        $translator = $this->getTranslator();

        // prepare entity
        $entityClass = $admin->getEntityClass();
        $entity = new $entityClass();

        $this->secure($admin, ContentVoter::CREATE_CONTENT, $entity);

        $form = $this->getCreateForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $translator->trans('crud.create.success', array(), 'ShuweeAdminBundle'));

            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render(
            'ShuweeAdminBundle:Content:create.html.twig',
            array(
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
        $translator = $this->getTranslator();

        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        $this->secure($admin, ContentVoter::UPDATE_CONTENT, $entity);

        $form = $this->getUpdateForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $translator->trans('crud.edit.success', array(), 'ShuweeAdminBundle'));

            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render(
            'ShuweeAdminBundle:Content:update.html.twig',
            array(
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
        $translator = $this->getTranslator();

        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        $this->secure($admin, ContentVoter::DELETE_CONTENT, $entity);

        $form = $this->getDeleteForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $translator->trans('crud.delete.success', array(), 'ShuweeAdminBundle'));

            return $this->redirect($this->getAdminRoutingHelper()->generateUrl($admin, 'index'));
        }

        return $this->render(
            'ShuweeAdminBundle:Content:delete.html.twig',
            array(
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
    public function toggleAction(Request $request, Admin $admin)
    {
        $entity = $admin->loadEntity($request->attributes->get('id'));

        if (!$entity) {
            throw $this->createNotFoundException('The resource cannot be found');
        }

        $this->secure($admin, ContentVoter::UPDATE_CONTENT, $entity);

        // Check provided token is valid to prevent csrf attacks
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManager $csrfTokenManager */
        $csrfTokenManager = $this->get('security.csrf.token_manager');
        $token = new CsrfToken('datagrid', $request->attributes->get('token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new AccessDeniedException('Invalid token');
        }

        $field = $request->attributes->get('field');

        // Get current entity property value
        $accessor = PropertyAccess::createPropertyAccessor();
        $current = $accessor->getValue($entity, $field);
        // Update it to its contrary
        $accessor->setValue($entity, $field, !$current);

        try {
            // Apply
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse(!$current, 200);
        } catch (Exception $e) {
            // return json response
            return new JsonResponse($current, 500);
        }
    }

    /**
     * Get form to create a new entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getCreateForm(Admin $admin, $entity)
    {
        $translator = $this->getTranslator();

        $formType = $admin->getForm();

        $form = $this->createForm(
            $formType,
            $entity,
            array(
                'action' => $this->getAdminRoutingHelper()->generateUrl($admin, 'create'),
                'method' => 'POST',
            )
        );
        $form->add(
            'submit',
            SubmitType::class,
            array(
                'label' => $translator->trans('crud.create.action', array(), 'ShuweeAdminBundle'),
                'attr' => array('class' => 'btn-success'),
            )
        );

        return $form;
    }

    /**
     * Get form to update an existing entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getUpdateForm(Admin $admin, $entity)
    {
        $translator = $this->getTranslator();

        $formType = $admin->getForm();

        $form = $this->createForm(
            $formType,
            $entity,
            array(
                'action' => $this->getAdminRoutingHelper()->generateUrl($admin, 'update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add(
            'submit',
            SubmitType::class,
            array(
                'label' => $translator->trans('crud.edit.action', array(), 'ShuweeAdminBundle'),
                'attr' => array('class' => 'btn-primary'),
            )
        );

        return $form;
    }

    /**
     * Get form to update an existing entity.
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     */
    private function getDeleteForm(Admin $admin, $entity)
    {
        $translator = $this->getTranslator();

        return $this->createFormBuilder()
            ->setAction($this->getAdminRoutingHelper()->generateUrl($admin, 'delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => $translator->trans('crud.delete.action', array(), 'ShuweeAdminBundle'),
                    'attr' => array('class' => 'btn-danger'),
                )
            )
            ->getForm();
    }

    /**
     * @param mixed $attributes
     * @param mixed $object
     * @return mixed
     */
    private function secure(Admin $admin, $attributes, $object = null)
    {
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        if (!$admin->isGranted($attributes, $object)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper
     */
    private function getAdminRoutingHelper()
    {
        return $this->container->get('shuwee_admin.admin_routing_helper');
    }

    /**
     * Get translator helper
     * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    private function getTranslator()
    {
        return $this->container->get('translator');
    }
}
