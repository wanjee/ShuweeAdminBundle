# Getting started

## Installation

Ask composer to install the bundle and its dependencies

``` bash
composer require wanjee/shuwee-admin-bundle
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

Add ShuweeAdminBundle routing in *app/config/routing.yml*

``` yaml
shuwee_admin:
    resource: "@ShuweeAdminBundle/Resources/config/routing.yml"
    prefix: /admin
```

## Bundle usage

1. [Basic configuration](./configuration.md)
2. [Datagrid](./datagrid.md)
3. [Menu](./menu.md)
4. [Forms](./forms.md)
5. [Form type extensions](./form_type_extensions.md)
6. [Security](./security.md)


