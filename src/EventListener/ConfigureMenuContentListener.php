<?php

namespace Wanjee\Shuwee\AdminBundle\EventListener;

use Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent;
use Wanjee\Shuwee\AdminBundle\Manager\AdminManager;
use Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Wanjee\Shuwee\AdminBundle\Security\Voter\ContentVoter;

/**
 * Class ConfigureMenuContentListener
 * @package Wanjee\Shuwee\AdminBundle\EventListener
 */
class ConfigureMenuContentListener
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager
     */
    private $adminManager;

    /**
     * @var \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper
     */
    private $adminRoutingHelper;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager
     * @param \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper $adminRoutingHelper
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $authorizationChecker
     */
    public function __construct(AdminManager $adminManager, AdminRoutingHelper $adminRoutingHelper, TranslatorInterface $translator, AuthorizationChecker $authorizationChecker) {
        $this->adminManager = $adminManager;
        $this->adminRoutingHelper = $adminRoutingHelper;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $menu = $event->getMenu();

            // list of configured sections
            $sections = array();

            /** @var \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin */
            foreach ($this->adminManager->getAdmins() as $alias => $admin) {

                // object is required at least to get the class to check permissions against in ContentVoter
                $entityClass = $admin->getEntityClass();
                $object = new $entityClass();

                // Menu item should not appear if user does not have access to the list
                if (!$this->authorizationChecker->isGranted(array(ContentVoter::LIST_CONTENT), $object)) {
                    continue;
                }

                // Get parent menu item label
                $section = $admin->getMenuSection();
                if (!is_string($section)) {
                    throw new \Exception(sprintf('AdminInterface::getMenuSection() must return a string, %s returned.', gettype($section)));
                }

                // Create parent menu item if it does not exist yet
                if (!isset($sections[$section])) {
                    $sections[$section] = $menu->addChild(ucfirst($this->translator->trans($section, array(), 'ShuweeAdminBundle')));
                }

                $pluralLabel = $this->translator->transchoice($admin->getLabel(), 10, array(), 'ShuweeAdminBundle');
                $sections[$section]->addChild($pluralLabel, array('route' => $this->adminRoutingHelper->getRouteName($admin, 'index')));
            }
        }
    }
}
