<?php

namespace Wanjee\Shuwee\AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class FileTypeExtension
 * @package Wanjee\Shuwee\AdminBundle\Form\Extension
 */
class FilePreviewTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }

    /**
     * Add preview_base_path option
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('preview_base_path'));
    }

    /**
     * Build preview url and meta information (i.e. file type)
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('preview_base_path', $options)) {
            $parentData = $form->getParent()->getData();

            $previewUrl = null;
            if (null !== $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $previewUrl = $accessor->getValue($parentData, $options['preview_base_path']);
            }

            if (!empty($previewUrl)) {
                $pathInfo = pathinfo($previewUrl);
                // define path to preview resource
                $view->vars['preview_url'] = $previewUrl;
                $view->vars['preview_basename'] = $pathInfo['basename'];
            }
        }
    }
}