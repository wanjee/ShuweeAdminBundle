<?php

namespace Wanjee\Shuwee\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent;

/**
 * Class Builder
 * @package Wanjee\Shuwee\AdminBundle\Menu
 */
class Builder extends ContainerAware
{
    /**
     * User menu builder method
     *
     * @param FactoryInterface $factory
     * @param array $options
     */
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right navbar-user');

        /** @var \Symfony\Component\Security\Core\SecurityContext $securityContext */
        $securityContext = $this->container->get('security.context');

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $username = $securityContext->getToken()->getUser()->getUsername();

            $userMenuItem = $menu->addChild($username, array('route' => 'logout'))
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'glyphicon-user');
            $userMenuItem->addChild('Logout', array('route' => 'logout'))
                ->setAttribute('icon', 'glyphicon-off');
        }

        return $menu;
    }

    /**
     * Build main menu.
     *
     * Let any bundle add items to this menu by subscribing to ConfigureMenuEvent::CONFIGURE
     * @see ConfigureMenuContentListener for example
     *
     * @param FactoryInterface $factory
     * @param array $options
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