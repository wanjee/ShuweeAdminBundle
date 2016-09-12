<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter;

use Symfony\Component\Form\FormBuilderInterface;

interface DatagridFilterInterface
{
    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeInterface
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $form
     */
    public function buildForm(FormBuilderInterface $form);

    /**
     * @return mixed
     */
    public function getExpression();

}
