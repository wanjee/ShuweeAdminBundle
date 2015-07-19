<?php

namespace Wanjee\Shuwee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     */
    function dashboardAction() {

        /** @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager */
        $adminManager = $this->get('shuwee_admin.admin_manager');

        return $this->render('ShuweeAdminBundle:Admin:dashboard.html.twig',
            array(
                /** @var \Wanjee\Shuwee\AdminBundle\Admin\Admin $admin */
                'admins' => $adminManager->getAdmins(),
            )
        );
    }
}
