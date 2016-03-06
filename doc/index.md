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

See [Bundle usage](./usage.md)

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

## Change branding of administration interface

See http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates

### Footer

Override footer.html.twig template: 
Copy the original one from vendor/wanjee/shuwee-admin-bundle/src/Resources/views/Partial/footer.html.twig
to app/Resources/ShuweeAdminBundle/views/Partial/footer.html.twig and modify the copy to suit your needs.

### Login page

Override login_header.html.twig template: 
Copy the original one from vendor/wanjee/shuwee-admin-bundle/src/Resources/views/Partial/login_header.html.twig
to app/Resources/ShuweeAdminBundle/views/Partial/login_header.html.twig and modify the copy to suit your needs.
 
### Header 

Override navbar_brand.html.twig template: 
Copy the original one from vendor/wanjee/shuwee-admin-bundle/src/Resources/views/Partial/navbar_brand.html.twig
to app/Resources/ShuweeAdminBundle/views/Partial/navbar_brand.html.twig and modify the copy to suit your needs.



