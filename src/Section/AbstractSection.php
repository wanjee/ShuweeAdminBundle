<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Section;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

abstract class AbstractSection implements SectionInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *
     */
    public function configureRoutes(RouteCollection $routeCollection)
    {
        $routingHelper = $this->getSectionRoutingHelper();

        foreach ($this->getSectionItems() as $sectionItem) {
            $routeCollection->add(
              $routingHelper->getRouteName($this, $sectionItem),
              $routingHelper->getRoute($this, $sectionItem)
            );
        }

    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Routing\Helper\SectionRoutingHelper
     */
    public function getSectionRoutingHelper()
    {
        return $this->container->get('shuwee_admin.section_routing_helper');
    }
}