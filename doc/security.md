# Security

## Firewall and admin users

If you want to use the build in admin user provider you will need to implement your security as follow

``` yaml
security:
    encoders:
        Wanjee\Shuwee\AdminBundle\Entity\User:
            algorithm: bcrypt
            cost: 15

    providers:
        shuwee_provider:
            entity:
                class: ShuweeAdminBundle:User
                property: username

    firewalls:
        admin_area:
            pattern:    ^/admin
            anonymous: ~
            form_login:
                check_path: /admin/login_check
                login_path: /admin/login
                default_target_path: /admin
            logout:
                path:   /admin/logout
                target: /
            provider: shuwee_provider


    access_control:
            - { path: ^/admin/login, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/admin, role: ROLE_ADMIN }

    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER]
        ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
```

Configure the encoder as you prefer.  Define *ShuweeAdminBundle:User* as a provider, use that provider in your firewall.
You can define other firewalls for i.e. frontend or a webservice if needed.

## Create admin user

To generate a new user use the dedicated command

``` bash
bin/console shuwee:admin:user:add username password --roles=ROLE_ADMIN
```


A simple voter solution has been implemented.  It's far from perfect but usable for simple use cases.
It has to be implemented per Admin (so per entity to edit).
You need to implement hasAccess method in your Admin class.  Here is a simple example that will match actions against roles.

``` php
<?php
namespace AppBundle\Admin;

use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Security\Voter\ContentVoter;
use Wanjee\Shuwee\AdminBundle\Admin\Admin;
use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridViewAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridCreateAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridUpdateAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridDeleteAction;

/**
 * Class PostAdmin
 * @package AppBundle\Admin
 */
class PostAdmin extends Admin
{
    // ... other methods ...

    /**
     * Content voter callback.
     * For a given user, a given attribute (action to take) and a given object
     * it returns user authorization
     *
     * @param UserInterface $user
     * @param string $action The FQN classname of the action being voted on.
     * @param mixed $object
     * @return integer either VoterInterface::ACCESS_GRANTED, VoterInterface::ACCESS_ABSTAIN, or VoterInterface::ACCESS_DENIED
     */
    public function hasAccess(UserInterface $user, $action, $object = null)
    {
        $grants = array(
            ContentVoter::LIST_CONTENT => array('ROLE_ADMIN'),
            DatagridViewAction::class => array('ROLE_ADMIN'),
            DatagridCreateAction::class => array('ROLE_SUPER_ADMIN'),
            DatagridUpdateAction::class => array('ROLE_ADMIN'),
            DatagridDeleteAction::class => array(), // no can do.
        );

        // get required role
        $granted = array();
        if (array_key_exists($action, $grants)) {
            $granted = $grants[$action];
        }

        // check if user has required role
        if (array_intersect($granted, $user->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }
        else {
            return VoterInterface::ACCESS_DENIED;
        }
    }
}
```
