<?php

namespace Wanjee\Shuwee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AdminController
 * @package Wanjee\Shuwee\AdminBundle\Controller
 *
 */
class AdminController extends Controller
{
    /**
     * Admin dashboard.
     *
     * @Route("/", name="admin_dashboard")
     * @Template("ShuweeAdminBundle:Admin:dashboard.html.twig")
     */
    function dashboardAction() {

        /**
         * @var \Wanjee\Shuwee\AdminBundle\Service\AdminManager
         */
        $adminManager = $this->get('shuwee_admin.admin_manager');

        foreach ($adminManager->getAdmins() as $alias => $admin) {

        }

        return array();
    }
}
