<?php

namespace Shuwee\AdminBundle\Service;

class AdminManager
{
    /**
     * @var array list of available payment methods
     */
    private $admins = array();

    /**
     * @param string $alias
     */
    public function registerAdmin($alias, AdminInterface $admin)
    {
        $this->admins[$alias] = $admin;
    }

    /**
     * @param $alias
     * @return \Shuwee\AdminBundle\Service\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getAdmin($alias)
    {
        if (!array_key_exists($alias, $this->admins)) {
            throw new \InvalidArgumentException(sprintf('The admin %s has not been registered with the Shuwee admin bundle', $alias));
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