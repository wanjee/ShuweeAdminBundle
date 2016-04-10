<?php

namespace Wanjee\Shuwee\AdminBundle\Twig;

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
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
          new \Twig_SimpleFunction('datagrid', array($this, 'renderDatagrid'), array('is_safe' => array('html'), 'needs_environment' => true)),
          new \Twig_SimpleFunction('datagrid_list_actions', array($this, 'renderDatagridListActions'), array('is_safe' => array('html'), 'needs_environment' => true)),
          new \Twig_SimpleFunction('datagrid_field', array($this, 'renderDatagridField'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid
     */
    public function renderDatagrid(Twig_Environment $env, DatagridInterface $datagrid)
    {
        return $this->render(
            $env,
            'datagrid',
            array(
                'datagrid' => $datagrid,
            )
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
            array(
                'actions' => $datagrid->getActions(),
            )
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
            $type->getBlockVariables($field, $entity) + array('datagrid' => $datagrid, 'entity' => $entity)
        );
    }

    /**
     * @param string $block Block name
     * @param array $variables
     */
    public function render(Twig_Environment $env, $block, $variables = array())
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
