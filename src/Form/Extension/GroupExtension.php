<?php

namespace Wanjee\Shuwee\AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class MarkdownTextareaExtension
 * @package Wanjee\Shuwee\AdminBundle\Form\Extension
 */
class GroupExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(
                array(
                    'group'
                )
            );
    }

    /**
     * Preprocess view variables
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $formName = $form->getName();
        //Make sure to exclude all form names starting with 2 underscores.
        //For instance the prototype of a Collection starts with 2 underscores
        if ('__' === substr($formName,0,2)) {
            return;
        }

        if (!empty($options['group'])) {
            $group = $options['group'];
            $type = 'group';
        } else {
            // If no group is set, make sure we have a unique index.
            // This is needed to maintain the correct order of the fields.
            $group = 'single_' . $formName;
            $type = 'single';
        }

        $root = $view->parent;

        if (!isset($root->vars['groups'][$group])) {
            $root->vars['groups'][$group] = array(
                'type' => $type,
                'items' => array(),
            );
        }
        $root->vars['groups'][$group]['items'][] = $formName;
    }
}