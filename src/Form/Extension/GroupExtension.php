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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'group'
        ));
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
        if (!empty($options['group'])) {
            $group = $options['group'];
        } else {
            //If no group is set, make sure we have a unique index.
            //This is needed to maintain the correct order of the fields.
            $group = $form->getName();
        }

        $root = $this->getRootView($view);
        $root->vars['groups'][$group][] = $form->getName();
    }

    /**
     * @param FormView $view
     * @return FormView
     */
    public function getRootView(FormView $view)
    {
        $root = $view->parent;

        while (null === $root && is_object($root)) {
            $root = $root->parent;
        }

        return $root;
    }
}