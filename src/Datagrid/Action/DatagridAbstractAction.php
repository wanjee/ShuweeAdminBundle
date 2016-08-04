<?php
namespace Wanjee\Shuwee\AdminBundle\Datagrid\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class DatagridAbstractAction
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type
 */
abstract class DatagridAbstractAction implements DatagridActionInterface
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param string $route
     * @param array $options
     */
    public function __construct($route, $options = array())
    {
        $this->route = $route;

        // manage options
        $resolver = new OptionsResolver();

        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                array(
                    'icon' => null, // See http://getbootstrap.com/components/
                    'btn-style' => 'default', // See http://getbootstrap.com/css/#helper-classes-colors
                    'classes' => '', // Any additional class you want on the button
                )
            )
            ->setRequired('label')
            ->setAllowedTypes('label', ['null', 'string'])
            ->setAllowedTypes('icon', ['null', 'string'])
            ->setAllowedTypes('btn-style', ['null', 'string'])
            ->setAllowedTypes('classes', ['null', 'string'])
        ;
    }


    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @param mixed $default
     */
    public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
    }
}
