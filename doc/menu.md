# Menu

## Main menu

You can inject routes of your choice in the main menu.  The menu uses KnpMenuBundle and is build with the help of a custom event.
Wanjee\Shuwee\AdminBundle\Menu\Builder::SideMenu dispatches a ``shuwee_admin.menu_configure`` event.  Listen to it in your own bundle
to be able to add menu items.  To listen to it implement an event listener.


``` php
<?php

namespace AppBundle\EventListener;

use Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent;

/**
 * Class ConfigureMenuListener
 */
class ConfigureMenuListener
{
    /**
     * @param \Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Menu item #1', array('route' => 'admin_route_1'));
        $menu->addChild('Menu item #2', array('route' => 'admin_route_2'));
    }
}
```

Register your listener as a service:

``` yaml
app.shuwee_menu_listener:
    class: AppBundle\EventListener\ConfigureMenuListener
    tags:
        - { name: kernel.event_listener, event: shuwee_admin.menu_configure, method: onMenuConfigure }
```

Define your routes controller as usual.
For an improved integration you should consider extending the admin base layout.

``` .twig
{% extends "ShuweeAdminBundle::page_content.html.twig" %}
```
    
