<?php

namespace Wanjee\Shuwee\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

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
        /*
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right navbar-user');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $securityContext = $this->container->get('security.context');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $username = $securityContext->getToken()->getUser()->getUsername(); // Get username of the current logged in user

            $menu->addChild($username, array('route' => 'logout', 'extras' => array('icon' => 'glyphicon-user')))
              ->setAttribute('dropdown', true);
            $menu[$username]->addChild('Logout', array('route' => 'logout', 'extras' => array('icon' => 'glyphicon-off')));
        }
        else {
            $menu->addChild('Login', array('route' => 'login'));
        }

        return $menu;
        */
        $menu = $factory->createItem('root');

        return $menu;
    }

    /**
     * Main administration menu builder method
     *
     * @param FactoryInterface $factory
     * @param array $options
     */
    public function sideMenu(FactoryInterface $factory, array $options)
    {
        $translator = $this->getTranslator();

        $menu = $factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => 'admin_dashboard'));

        // Content
        $contentMenuItem = $menu->addChild('Content');

        /** @var $adminRoutingHelper \Wanjee\Shuwee\AdminBundle\Manager\AdminManager */
        $adminManager = $this->container->get('shuwee_admin.admin_manager');
        /** @var $routingHelper \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper */
        $adminRoutingHelper = $this->container->get('shuwee_admin.admin_routing_helper');

        /** @var  $admin \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface */
        foreach ($adminManager->getAdmins() as $alias => $admin) {
            $singularLabel = $translator->transchoice($admin->getLabel(), 1);
            $pluralLabel = $translator->transchoice($admin->getLabel(), 10);

            $contentTypeMenuItem = $contentMenuItem->addChild($pluralLabel);

            $contentTypeMenuItem->addChild('List', array('route' => $adminRoutingHelper->getRouteName($admin, 'index')));
            $contentTypeMenuItem->addChild('Create new ' . $singularLabel, array('route' => $adminRoutingHelper->getRouteName($admin, 'create')));
        }

        // Sections
        $sectionManager = $this->container->get('shuwee_admin.section_manager');
        /** @var $routingHelper \Wanjee\Shuwee\AdminBundle\Routing\Helper\SectionRoutingHelper */
        $sectionRoutingHelper = $this->container->get('shuwee_admin.section_routing_helper');

        /** @var  $admin \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface */
        foreach ($sectionManager->getSections() as $alias => $section) {
            $sectionItems = $section->getSectionItems();
            /** @var $sectionItem \Wanjee\Shuwee\AdminBundle\Section\SectionItem */
            foreach ($sectionItems as $sectionItem) {
                $menu->addChild($sectionItem->getLabel(), array('route' => $sectionRoutingHelper->getRouteName($section, $sectionItem)));
            }
        }

        return $menu;
    }

    /**
     * Get translator helper
     */
    public function getTranslator()
    {
        return $this->container->get('translator');
    }
}