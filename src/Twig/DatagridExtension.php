<?php

namespace Wanjee\Shuwee\AdminBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface;

/**
 * Class AdminExtension
 * @package Wanjee\Shuwee\AdminBundle\Twig
 */
class DatagridExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

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
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
          'datagrid' => new \Twig_Function_Method($this, 'renderDatagrid', array('is_safe' => array('html'))),
          'datagrid_field' => new \Twig_Function_Method($this, 'renderDatagridField', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid
     */
    public function renderDatagrid(DatagridInterface $datagrid)
    {
        return $this->render('datagrid', array(
          'datagrid' => $datagrid,
        ));
    }

    /**
     * @param $datagrid \Wanjee\Shuwee\AdminBundle\Datagrid\Field\DatagridFieldInterface
     */
    public function renderDatagridField(DatagridFieldInterface $field, $entity)
    {
        /** @var \Wanjee\Shuwee\AdminBundle\Datagrid\Type\DatagridTypeInterface */
        $type = $field->getType();

        return $this->render($type->getBlockName(), $type->getBlockVariables($field, $entity));
    }

    /**
     * @param string $block Block name
     * @param array $variables
     */
    public function render($block, $variables = array())
    {
        /** @var \Twig_TemplateInterface $template */
        $template = $this->environment->loadTemplate('ShuweeAdminBundle:Datagrid:datagrid.html.twig');

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