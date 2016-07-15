<?php

namespace Wanjee\Shuwee\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent;


/**
 * Class Builder
 * @package Wanjee\Shuwee\AdminBundle\Menu
 */
class Builder implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * Build main menu.
     *
     * Let any bundle add items to this menu by subscribing to ConfigureMenuEvent::CONFIGURE
     * @see ConfigureMenuContentListener for example
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function sideMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $this->container->get('event_dispatcher')->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($factory, $menu)
        );

        return $menu;
    }
}
