<?php

namespace Wanjee\Shuwee\AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class MarkdownTextareaExtension
 * @package Wanjee\Shuwee\AdminBundle\Form\Extension
 */
class MarkdownTextareaExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return TextareaType::class;
    }

    /**
     * Configure options
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(
                [
                    'markdown',
                ]
            )
            ->setDefaults(
                [
                    'markdown' => false,
                ]
            )
            ->setAllowedTypes('markdown', 'bool');
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
        if (array_key_exists('markdown', $options)) {
            $view->vars['markdown'] = $options['markdown'];
        }
    }
}
