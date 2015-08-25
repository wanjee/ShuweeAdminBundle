# Getting started

## Installation

Add ShuweeAdminBundle in your *composer.json*

``` .json
{
    "require": {
        "wanjee/shuwee-admin-bundle": "dev-master"
    }
}
```

Ask composer to install the bundle and its dependencies

``` bash
composer update wanjee/shuwee-admin-bundle
```

Register required bundles in *AppKernel.php* :

``` php
new Knp\Bundle\MenuBundle\KnpMenuBundle(),
new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
new Liip\ImagineBundle\LiipImagineBundle(),
new Wanjee\Shuwee\AdminBundle\ShuweeAdminBundle(),
```

Add basic LiipImagineBundle configuration in your main config file

``` yaml
liip_imagine:
    resolvers:
       default:
          web_path: ~

    filter_sets:
        cache: ~
```

Add KnpPaginator templates configuration in your main config file

``` yaml
knp_paginator:
    template:
        pagination: ShuweeAdminBundle:Pagination:twitter_bootstrap_v3_pagination.html.twig
        sortable: ShuweeAdminBundle:Pagination:sortable_link.html.twig
``` 

You'll also need to register LiipImagineBundle routes in your *routing.yml* file

``` yaml
_liip_imagine:
    resource: "@LiipImagineBundle/Resources/config/routing.xml"
```

Refer to [LiipImagineBundle official documentation](http://symfony.com/doc/current/bundles/LiipImagineBundle/index.html)


You will need to register default Symfony Twig extensions in your main config file to be able to use Datagrid text and collection types

``` yaml
services:
    twig.extension.text:
        class: Twig_Extensions_Extension_Text
        tags:
            - { name: twig.extension }
```

Enable translation in your main config file

``` yaml
framework:
    translator:      { fallbacks: ["%locale%"] }
```

## Bundle usage

Add ShuweeAdminBundle routing in *app/config/routing.yml*

``` yaml
shuwee_admin:
    resource: "@ShuweeAdminBundle/Resources/config/routing.yml"
    prefix: /admin
```

Define or generate form type for your entity.

``` bash
bin/console generate:doctrine:form AcmeDemoBundle:Post
``` 

Define admin services in your bundle.  

``` php

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
```

Register your admin class as a tagged service

``` yaml
acmedemo.post_admin:
    class: Acme\Bundle\DemoBundle\Admin\PostAdmin
    parent: shuwee_admin.admin_abstract
    tags:
      -  { name: shuwee.admin, alias: post }
```

## Datagrid

See [Datagrid](./datagrid.md)

## Menu

See [Menu](./menu.md)

## Forms

See [Forms](./forms.md)

## Form type extensions

See [Form type extensions](./form_type_extensions.md)

## Security

See [Security](./security.md)

## Change copyright

Override footer.html.twig template.  
Copy the original one from vendor/wanjee/shuwee-admin-bundle/src/Resources/views/Partial/footer.html.twig
to app/Resources/ShuweeAdminBundle/views/Partial/footer.html.twig and modify the copy to suit your needs.

See http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates