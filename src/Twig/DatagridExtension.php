<?php

namespace Wanjee\Shuwee\AdminBundle\Twig;

use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface;

/**
 * Class AdminExtension
 * @package Wanjee\Shuwee\AdminBundle\Twig
 */
class DatagridExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManager
     */
    protected $csrfTokenManager;

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator = null, CsrfTokenManager $csrfTokenManager)
    {
        $this->translator = $translator;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
          new \Twig_SimpleFunction('datagrid', [$this, 'renderDatagrid'], ['is_safe' => ['html'], 'needs_environment' => true]),
          new \Twig_SimpleFunction('datagrid_list_actions', [$this, 'renderDatagridListActions'], ['is_safe' => ['html'], 'needs_environment' => true]),
          new \Twig_SimpleFunction('datagrid_filters', [$this, 'renderDatagridFilters'], ['is_safe' => ['html'], 'needs_environment' => true]),
          new \Twig_SimpleFunction('datagrid_field', [$this, 'renderDatagridField'], ['is_safe' => ['html'], 'needs_environment' => true]),
          new \Twig_SimpleFunction('datagrid_get_csrf_token', [$this, 'getCsrfToken']),
        ];
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid
     */
    public function renderDatagrid(Twig_Environment $env, DatagridInterface $datagrid)
    {
        return $this->render(
            $env,
            'datagrid',
            [
                'datagrid' => $datagrid,
            ]
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid
     */
    public function renderDatagridListActions(Twig_Environment $env, DatagridInterface $datagrid)
    {
        return $this->render(
            $env,
            'datagrid_list_actions',
            [
                'actions' => $datagrid->getListActions(),
            ]
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid
     */
    public function renderDatagridFilters(Twig_Environment $env, DatagridInterface $datagrid)
    {
        $form = $datagrid->getFiltersForm();

        if (!$form) {
            return null;
        }

        return $this->render(
            $env,
            'datagrid_filters',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface
     */
    public function renderDatagridField(Twig_Environment $env, DatagridInterface $datagrid, DatagridFieldInterface $field, $entity)
    {
        /** @var \Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeInterface */
        $type = $field->getType();

        return $this->render(
            $env,
            $type->getBlockName(),
            $type->getBlockVariables($field, $entity) + ['datagrid' => $datagrid, 'entity' => $entity]
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface
     */
    public function getCsrfToken()
    {
        static $token;

        if (!isset($token)) {
            // Invalidate any token generated on another page
            $this->csrfTokenManager->refreshToken('datagrid');
            $token = $this->csrfTokenManager->getToken('datagrid');
        }

        return $token;
    }

    /**
     * @param string $block Block name
     * @param array $variables
     */
    public function render(Twig_Environment $env, $block, $variables = [])
    {
        /** @var \Twig_TemplateInterface $template */
        $template = $env->loadTemplate('ShuweeAdminBundle:Datagrid:datagrid.html.twig');

        return $template->renderBlock($block, $variables);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shuwee_datagrid_extension';
    }
}
