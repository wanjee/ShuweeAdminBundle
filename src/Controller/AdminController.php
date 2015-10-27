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
    function dashboardAction()
    {
        $translator = $this->container->get('translator');

        /** @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager */
        $adminManager = $this->get('shuwee_admin.admin_manager');

        $sections = array();
        /** @var \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin */
        foreach ($adminManager->getAdmins() as $alias => $admin) {
            $section = $admin->getMenuSection();
            if (!is_string($section)) {
                throw new \Exception(
                    sprintf('AdminInterface::getMenuSection() must return a string, %s returned.', gettype($section))
                );
            }

            // Create parent menu item if it does not exist yet
            if (!isset($sections[$section])) {
                $sections[$section]['label'] = ucfirst(
                    $translator->trans($section, array(), 'ShuweeAdminBundle')
                );
                $sections[$section]['admins'] = array();
            }

            $sections[$section]['admins'][] = $admin;
        }

        return $this->render(
            'ShuweeAdminBundle:Admin:dashboard.html.twig',
            array(
                'sections' => $sections,
            )
        );
    }
}
