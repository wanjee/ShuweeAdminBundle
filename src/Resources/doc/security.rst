Shuwee Admin Security
=====================

Firewall and admin users
------------------------

If you want to use the build in admin user provider you will need to implement your security as follow

.. code-block:: yaml

    security:
        encoders:
            Wanjee\Shuwee\AdminBundle\Entity\User:
                algorithm: sha1
                iterations: 1
                encode_as_base64: false

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

Configure the encoder as you prefer.  Define ShuweeAdminBundle:User as a provider, use that provider in your firewall.
You can define other firewalls for i.e. frontend or a webservice if needed.

Create admin user
-----------------

To generate a new user use the dedicated command

.. code-block:: bash

    bin/console shuwee:admin:user:add username password email --roles=ROLE_ADMIN


A simple voter solution has been implemented.  It's far from perfect but usable for simple use cases.

It has to be implemented per Admin (so per entity to edit)

You need to implement hasAccess method in your Admin class.  Here is a simple example that will match actions against roles.

.. code-block:: php

    <?php
    namespace Acme\Bundle\DemoBundle\Admin;

    use Symfony\Component\Security\Core\User\UserInterface;
    use Wanjee\Shuwee\AdminBundle\Admin\Admin;
    use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;

    /**
     * Class PostAdmin
     * @package Acme\Bundle\DemoBundle\Admin
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
         * @param string $attribute
         * @param mixed $object
         * @return integer either VoterInterface::ACCESS_GRANTED, VoterInterface::ACCESS_ABSTAIN, or VoterInterface::ACCESS_DENIED
         */
        public function hasAccess(UserInterface $user, $action, $object = null)
        {
            $grants = array(
                ContentVoter::LIST_CONTENT => array('ROLE_ADMIN'),
                ContentVoter::VIEW_CONTENT => array('ROLE_ADMIN'),
                ContentVoter::CREATE_CONTENT => array('ROLE_SUPER_ADMIN'),
                ContentVoter::UPDATE_CONTENT => array('ROLE_ADMIN'),
                ContentVoter::DELETE_CONTENT => array(), // no can do.
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