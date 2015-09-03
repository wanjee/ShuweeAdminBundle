<?php

namespace Wanjee\Shuwee\AdminBundle\Manager;

use Wanjee\Shuwee\AdminBundle\Admin\AdminInterface;

class AdminManager
{
    /**
     * @var array list of admin services
     */
    private $admins = array();

    /**
     * @param string $alias
     */
    public function registerAdmin($alias, AdminInterface $admin)
    {
        // Ensure alias is unique
        if (array_key_exists($alias, $this->admins)) {
            throw new \InvalidArgumentException(sprintf('An admin has already been registered with the alias "%s".  Alias must be unique', $alias));
        }

        $admin->setAlias($alias);

        $this->admins[$alias] = $admin;
    }

    /**
     * @param $alias
     * @return \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getAdmin($alias)
    {
        if (!array_key_exists($alias, $this->admins)) {
            throw new \InvalidArgumentException(sprintf('The admin %s has not been registered with the Shuwee Admin bundle', $alias));
        }
        return $this->admins[$alias];
    }

    /**
     * @return array
     */
    public function getAdmins()
    {
        return $this->admins;
    }
}