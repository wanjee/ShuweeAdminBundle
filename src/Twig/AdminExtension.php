<?php

namespace Wanjee\Shuwee\AdminBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var array
     */
    protected $cacheIsGranted = array();

    /**
     * AdminExtension constructor.
     * @param \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager
     * @param \Wanjee\Shuwee\AdminBundle\Routing\Helper\AdminRoutingHelper $routingHelper
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     */
    public function __construct(AdminManager $adminManager, AdminRoutingHelper $routingHelper, AuthorizationCheckerInterface $authorizationChecker, TranslatorInterface $translator = null)
    {
        $this->adminManager = $adminManager;
        $this->routingHelper = $routingHelper;
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
          new \Twig_SimpleFunction('admin_get_label', array($this, 'getAdminLabel')),
          new \Twig_SimpleFunction('admin_get_path', array($this, 'getAdminPath')),
          new \Twig_SimpleFunction('admin_is_granted', array($this, 'isGranted')),
        );
    }

    /**
     * @param AdminInterface $admin
     * @param array|string $attributes
     * @param null $object
     * @return mixed
     */
    public function isGranted(AdminInterface $admin, $attributes, $object = null)
    {
        // make sure attributes is an array
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }

        $key = md5(json_encode($attributes) . ($object ? '/' . spl_object_hash($object) : ''));
        if (!array_key_exists($key, $this->cacheIsGranted)) {
            if (is_null($object)) {
                // object is required at least to get the class to check permissions against in ContentVoter
                $entityClass = $admin->getEntityClass();
                $object = new $entityClass();
            }
            $this->cacheIsGranted[$key] = $this->authorizationChecker->isGranted($attributes, $object);
        }
        return $this->cacheIsGranted[$key];
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
     * @param bool $plural
     * @return string
     */
    public function getAdminLabel(AdminInterface $admin, $plural = false)
    {
        $count = $plural ? 100 : 1;

        return $this->translator->transChoice($admin->getOption('label'), $count, array(), 'ShuweeAdminBundle');
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin
     * @param $action
     * @param array $params
     * @return string
     */
    public function getAdminPath(AdminInterface $admin, $action, array $params = array())
    {
        return $this->routingHelper->generateUrl($admin, $action, $params);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'shuwee_admin_extension';
    }
}
