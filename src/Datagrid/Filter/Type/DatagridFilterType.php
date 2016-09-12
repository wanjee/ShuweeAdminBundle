<?php
namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatagridFilterType
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type
 */
abstract class DatagridFilterType implements DatagridFilterTypeInterface
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $resolver;

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
        $this->resolver
            ->setDefined(
                [
                    'label',
                ]
            )
            ->setDefaults(
                [
                    'empty_data' => null,
                    'required' => false,
                ]
            )
            ->setAllowedTypes('label', ['string'])
            ->setAllowedTypes('required', ['boolean'])
            ->setAllowedTypes('empty_data', ['string', 'null']);
        $this->configureOptions($this->resolver);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {}

    /**
     * @param $options
     * @return array
     */
    public function resolveOptions($options)
    {
        return $this->resolver->resolve($options);
    }

}
