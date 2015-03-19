<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouteCollection;

abstract class Admin implements AdminInterface
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $resolver;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->resolver = new OptionsResolver();
        $this->defaultOptions();

        $this->options = $this->resolver->resolve($options);
    }

    /**
     *
     */
    protected function defaultOptions()
    {
        $this->resolver->setDefaults(array(
          'creatable' => false,
          'editable' => false,
          'deletable' => false,
          'itemsPerPage' => 25,
        ));
    }

    /**
     * @param array $options
     */
    public function configure(array $options = array())
    {
        $this->options = $this->resolver->resolve($options);
    }

    /**
     *
     */
    public function configureRoutes(RouteCollection $routeCollection) {

    }
}