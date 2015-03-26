<?php

namespace Wanjee\Shuwee\AdminBundle\Routing\Helper;

use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Route;

class RoutingHelper
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser $parser
     * @param string $routePrefix
     * @param string $routeNamePrefix
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    /**
     * Get the name of the route related to given $admin and $action.
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
     * @param $action
     *
     * @return string Name of the route
     */
    public function getRouteName(AdminInterface $admin, $action)
    {
        return 'shuwee_admin_' . $admin->getAlias() . '_' . $action;
    }

    /**
     * Build a route for a given admin and action.
     *
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
     * @param string $action
     * @param array $params
     * @param bool $defaultRoute
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function getRoute(AdminInterface $admin, $action, $params = array(), $isIndex = false)
    {
        $defaults = array();
        $path = '/' . $admin->getAlias();

        // append action if required
        if(!$isIndex) {
            $path .= '/' . $action;
        }

        foreach($params as $paramKey => $paramValue) {
            if(is_int($paramKey)) {
                $paramName = $paramValue;
            }
            else {
                $paramName = $paramKey;
                $defaults[$paramName] = $paramValue;
            }
            $path .= '/{' . $paramName . '}';
        }

        $controller = 'ShuweeAdminBundle:Content:' . $action;

        $defaults = array_merge(
          array(
            '_controller' => $controller,
            'alias' => $admin->getAlias(),

          ),
          $defaults
        );

        return new Route($path, $defaults);
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
     * @param string $action
     * @param array $params
     *
     * @return string
     */
    public function generateUrl(AdminInterface $admin, $action, $params = array())
    {
        $routeName = $this->getRouteName($admin, $action);

        return $this->router->generate($routeName, $params);
    }
}
