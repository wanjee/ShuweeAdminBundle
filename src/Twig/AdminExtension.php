<?php

namespace Wanjee\Shuwee\AdminBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

use Wanjee\Shuwee\AdminBundle\Admin\Admin;
use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Wanjee\Shuwee\AdminBundle\Manager\AdminManager;
use Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper;


class AdminExtension extends \Twig_Extension
{
    /**
     * @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager
     */
    protected $adminManager;

    /**
     * @var \Wanjee\Shuwee\AdminBundle\Routing\Helper\RoutingHelper
     */
    protected $routingHelper;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager
     * @param \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper $routingHelper
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(AdminManager $adminManager, AdminRoutingHelper $routingHelper, TranslatorInterface $translator = null)
    {
        $this->adminManager = $adminManager;
        $this->routingHelper = $routingHelper;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
          'admin_get_label' => new \Twig_Function_Method($this, 'getAdminLabel'),
          'admin_get_path' => new \Twig_Function_Method($this, 'getAdminPath'),
        );
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param string $action
     * @param array $params
     * @return string
     */
    public function getAdminPath(AdminInterface $admin, $action, array $params = array())
    {
        return $this->routingHelper->generateUrl($admin, $action, $params);
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin
     * @param bool $number
     * @return string
     */
    public function getAdminLabel(AdminInterface $admin, $plural = false)
    {
        $count = $plural ? 100 : 1;

        return $this->translator->transChoice($admin->getLabel(), $count, array(), 'ShuweeAdminBundle');
    }

    public function getName()
    {
        return 'shuwee_admin_extension';
    }
}
