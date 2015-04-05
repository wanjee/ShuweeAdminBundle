<?php

namespace Wanjee\Shuwee\AdminBundle\Routing\Loader;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Wanjee\Shuwee\AdminBundle\Manager\SectionManager;

/**
 * Class AdminLoader
 * @package Wanjee\Shuwee\AdminBundle\Routing\Loader
 */
class SectionLoader extends Loader
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Manager\SectionManager
     */
    private $sectionManager;

    private $loaded = false;

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Manager\SectionManager $sectionManager
     */
    public function __construct(SectionManager $sectionManager)
    {
        $this->sectionManager = $sectionManager;
    }

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "shuwee_section_extra" loader twice');
        }

        $routes = new RouteCollection();
        foreach ($this->sectionManager->getSections() as $alias => $section) {
            $section->configureRoutes($routes);
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string $type The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'shuwee_section_extra' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
        // Required but can be empty
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // Required but can be empty
    }
}