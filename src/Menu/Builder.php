<?php

namespace Wanjee\Shuwee\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent;


/**
 * Class Builder
 * @package Wanjee\Shuwee\AdminBundle\Menu
 */
class Builder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Build main menu.
     *
     * Let any bundle add items to this menu by subscribing to ConfigureMenuEvent::CONFIGURE
     * @see ConfigureMenuContentListener for example
     *
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function sideMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $this->dispatcher->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($this->factory, $menu)
        );

        return $menu;
    }
}
