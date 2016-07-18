<?php

/**
 * Symfony controller for all CRUD related actions.
 *
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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Wanjee\Shuwee\AdminBundle\Admin\Admin;
use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;
use Wanjee\Shuwee\AdminBundle\Security\Voter\ContentVoter;

/**
 * Class CrudController
 * @package Shuwee\AdminBundle\Controller
 */
class ContentController extends Controller
{

    /**
     * List all entities of the type supported by given Admin
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, Admin $admin)
    {
        $this->secure($admin, ContentVoter::LIST_CONTENT);

        // create our datagrid
        $datagrid = $this->get('shuwee_admin.datagrid');

        // bind our request to the datagrid
        $datagrid->bind($admin, $request);

        return $this->render(
            'ShuweeAdminBundle:Content:index.html.twig',
            array(
                'datagrid' => $datagrid,
            )
        );
    }

    /**
     * Create an entity of given type
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @return \Symfony\Component\HttpFoundation\Response
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
            $admin->prePersist($entity);
            $em->persist($entity);
            $admin->postPersist($entity);
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
     * Update a given entity
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException if the resource cannot be loaded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Admin $admin)
    {
        $translator = $this->getTranslator();

        $id = $request->attributes->get('id');
        $entity = $this->getDoctrine()->getRepository($admin->getEntityClass())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        $this->secure($admin, ContentVoter::UPDATE_CONTENT, $entity);

        $form = $this->getUpdateForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $admin->preUpdate($entity);
            $em->persist($entity);
            $admin->postUpdate($entity);
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
     * Delete a given entity (with confirmation form)
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException if the resource cannot be loaded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Admin $admin)
    {
        $translator = $this->getTranslator();

        $id = $request->attributes->get('id');
        $entity = $this->getDoctrine()->getRepository($admin->getEntityClass())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('That resource cannot be found');
        }

        $this->secure($admin, ContentVoter::DELETE_CONTENT, $entity);

        $form = $this->getDeleteForm($admin, $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $admin->preRemove($entity);
            $em->remove($entity);
            $admin->postRemove($entity);
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
     * Toggles the value of a given field
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException if the resource cannot be loaded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleAction(Request $request, Admin $admin)
    {
        $id = $request->attributes->get('id');
        $entity = $this->getDoctrine()->getRepository($admin->getEntityClass())->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('The resource cannot be found');
        }

        $this->secure($admin, ContentVoter::UPDATE_CONTENT, $entity);
        $this->checkCsrf($request->attributes->get('token'));

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
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     *
     * @return \Symfony\Component\Form\Form
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
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     *
     * @return \Symfony\Component\Form\Form
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
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param $entity
     *
     * @return \Symfony\Component\Form\Form
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
     * Ensure a user is allowed to access an action
     *
     * @throws AccessDeniedException if user is not allowed
     *
     * @param mixed $attributes
     * @param mixed $object
     */
    private function secure(Admin $admin, $attributes, $object = null)
    {
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        if (is_null($object)) {
            // object is required at least to get the class to check permissions against in ContentVoter
            $entityClass = $admin->getEntityClass();
            $object = new $entityClass();
        }

        if (!$this->get('security.authorization_checker')->isGranted($attributes, $object)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Check provided token is valid to prevent csrf attacks
     *
     * @throws AccessDeniedException if the token cannot be verified
     *
     * @param String $tokenValue
     */
    private function checkCsrf($tokenValue)
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManager $csrfTokenManager */
        $csrfTokenManager = $this->get('security.csrf.token_manager');

        $token = new CsrfToken('datagrid', $tokenValue);

        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new AccessDeniedException('Invalid token');
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
     * @return object|\Symfony\Component\Translation\DataCollectorTranslator|\Symfony\Component\Translation\IdentityTranslator
     */
    private function getTranslator()
    {
        return $this->get('translator');
    }
}
