Shuwee
======

Shuwee is (or will be) a backend administration application (yes, one more) for your websites or RESTFUL apps.

Features will include:

- CRUD capabilities
- Nice backend interface
- REST API
- And much more

**Warning:** This is still a total work in progress.  This is not yet supposed to be used in real world.

Documentation
-------------

The bulk of the documentation will be stored in the `Resources/doc/index.md`

License
-------

This work is under the MIT license.

Why "Shuwee" ?
--------------

Shuwee name comes from a Lakota word : čhuwí.  It's defined as the upper back of the body.


Getting Started
===============


Installation
------------

Add ShuweeAdminBundle in your composer.json

.. code-block:: javascript

    {
        "require": {
            "wanjee/shuwee-admin-bundle": "dev-master"
        }
    }

Ask composer to install the bundle and its dependencies

.. code-block:: bash

    composer update wanjee/shuwee-admin-bundle

Register required bundles in AppKernel.php :

* ShuweeAdminBundle
* KnpMenuBundle
* LiipImagineBundle

.. code-block:: php

    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Liip\ImagineBundle\LiipImagineBundle(),
    new Wanjee\Shuwee\AdminBundle\ShuweeAdminBundle(),



Add ShuweeAdminBundle to the list of Assetic supported bundles in config.yml (or comment the bundles line)

.. code-block:: yaml

    assetic:
        debug:          "%kernel.debug%"
        use_controller: false
        #bundles:        [ ]


Add basic LiipImagineBundle configuration in your main config file

.. code-block:: yaml

    liip_imagine:
        resolvers:
           default:
              web_path: ~

        filter_sets:
            cache: ~

You'll also need to register LiipImagineBundle routes in your routing.yml file

.. code-block:: yaml

    _liip_imagine:
        resource: "@LiipImagineBundle/Resources/config/routing.xml"

Refer to LiipImagineBundle official documentation : http://symfony.com/doc/current/bundles/LiipImagineBundle/index.html


You will need to register default Symfony Twig extensions in your main config file to be able to use Datagrid text type

.. code-block:: yaml

    services:
        twig.extension.text:
            class: Twig_Extensions_Extension_Text
            tags:
                - { name: twig.extension }

Enable translation in your main config file

.. code-block:: yaml

    framework:
        translator:      { fallbacks: ["%locale%"] }

Bundle usage
------------

Add ShuweeAdminBundle routing in app/config/routing.yml

.. code-block:: yaml

    shuwee_admin:
        resource: "@ShuweeAdminBundle/Resources/config/routing.yml"
        prefix: /admin

Define or generate form type for your entity.

.. code-block:: bash

    bin/console generate:doctrine:form AcmeDemoBundle:Post


Define admin services in your bundle.  

.. code-block:: php

    <?php
    namespace Acme\Bundle\DemoBundle\Admin;
    
    use Wanjee\Shuwee\AdminBundle\Admin\Admin;
    use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;
    
    /**
     * Class PostAdmin
     * @package Acme\Bundle\DemoBundle\Admin
     */
    class PostAdmin extends Admin
    {
        /**
         * Return the main admin form for this content.
         *
         * @return \Symfony\Component\Form\Form
         */
        public function getForm()
        {
            // Return either a fully qualified class name
            // or the service id of your form if it is defined as a service
            return 'Acme\Bundle\DemoBundle\Form\PostType';
        }
    
        /**
         * @return Datagrid
         */
        public function getDatagrid()
        {
            $datagrid = new Datagrid($this);
    
            $datagrid
              ->addField('id', 'text')
              ->addField('title', 'text');
    
            return $datagrid;
        }
    
        /**
         * @return string
         */
        public function getEntityName()
        {
            return 'AcmeDemoBundle:Post';
        }
    
        /**
         * @return string
         */
        public function getEntityClass()
        {
            return 'Acme\Bundle\DemoBundle\Entity\Post';
        }
    
        /**
         * @return string
         */
        public function getLabel()
        {
            return '{0} Posts|{1} Post|]1,Inf] Posts';
        }
    }

Register your admin class as a tagged service

.. code-block:: yaml

    acmedemo.post_admin:
        class: Acme\Bundle\DemoBundle\Admin\PostAdmin
        parent: shuwee_admin.admin_abstract
        tags:
          -  { name: shuwee.admin, alias: post }

Datagrid
--------

See types.rst

Security
--------

See security.rst

Change copyright
----------------

Overrides footer.html.twig template.  Copy the original one from vendor/wanjee/shuwee-admin-bundle/src/Resources/views/Partial/footer.html.twig
to app/Resources/ShuweeAdminBundle/views/Partial/footer.html.twig and modify the copy to suit your needs.

See http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates