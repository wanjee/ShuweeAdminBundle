<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface DatagridFilterTypeInterface
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
interface DatagridFilterTypeInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Return the FQN of the FormType to be used
     *
     * @return string
     */
    public function getFormType();

    /**
     * @param array $options
     * @return array
     */
    public function resolveOptions($options);

    /**
     * @param $rawValue
     * @return mixed
     */
    public function formatValue($rawValue);

    /**
     * @return callable
     */
    public function getCriteriaExpression();


}
