<?php

namespace Wanjee\Shuwee\AdminBundle\Routing\Helper;

use Wanjee\Shuwee\AdminBundle\Section\SectionInterface;
use Wanjee\Shuwee\AdminBundle\Section\SectionItem;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Route;

class SectionRoutingHelper
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser $parser
     * @param string $routePrefix
     * @param string $routeNamePrefix
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    /**
     * Build a route for a given admin and action.
     *
     * @param \Wanjee\Shuwee\AdminBundle\Section\SectionInterface $section
     * @param \Wanjee\Shuwee\AdminBundle\Section\SectionItem $sectionItem
     * @param array $params
     * @param bool $defaultRoute
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function getRoute(SectionInterface $section, SectionItem $sectionItem)
    {
        $defaults = array();

        $path = '/' . $section->getAlias();

        $defaults = array_merge(
          array(
            '_controller' => $sectionItem->getController(),
            'alias' => $section->getAlias(),
          ),
          $defaults
        );

        return new Route($path, $defaults);
    }

    /**
     * Get the name of the route related to given $admin and $action.
     *
     * @param \Wanjee\Shuwee\AdminBundle\Section\SectionInterface $section
     * @param \Wanjee\Shuwee\AdminBundle\Section\SectionItem $sectionItem
     *
     * @return string Name of the route
     */
    public function getRouteName(SectionInterface $section, SectionItem $sectionItem)
    {
        return 'shuwee_section_' . $section->getAlias() . '_' . $sectionItem->getId();
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Section\SectionInterface $section
     * @param string $action
     * @param array $params
     *
     * @return string
     */
    public function generateUrl(SectionInterface $section, $params = array())
    {
        $routeName = $this->getRouteName($section);

        return $this->router->generate($routeName, $params);
    }
}
