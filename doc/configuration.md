# Configuration

## Define or generate an entity

``` bash
$ bin/console generate:doctrine:entity
```

## Define or generate form type for your entity

``` bash
$ bin/console generate:doctrine:form AppBundle:Post
```

Shuwee comes with a few utilities you can use to improve the look & feel an usability of your entity form.

See [Form type extensions](./form_type_extensions.md)

## Define Admin class in your bundle

``` php
<?php
namespace AppBundle\Admin;

use Wanjee\Shuwee\AdminBundle\Admin\AbstractAdmin;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;

use AppBundle\Form\PostType;
use AppBundle\Entity\Post;

/**
 * Class PostAdmin
 * @package AppBundle\Admin
 */
class PostAdmin extends AbstractAdmin
{
    /**
     * @return string
     */
    public function getEntityClass()
    {
        return Post::class;
    }

    /**
     * Return the main admin form for this content.
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        return PostType::class;
    }
    
    /**
     * @inheritdoc
     */
    public function attachFields(DatagridInterface $datagrid)
    {
        $datagrid
          ->addField('id', 'text')
          ->addField('title', 'text');
    }
}
```

### Datagrid

Read more on datagrid configuration: [Datagrid](./datagrid.md)

### Security

See how you can define which user can create, view, update or delete content: [Security](./security.md)

### Admin options

Implement getOptions() method to configure some behaviors of your Admin implementation.

Complete example: 
```php
/**
  * @return array Options
  */
 public function getOptions() {
     return array(
         'label' => '{0} Posts|{1} Post|]1,Inf] Posts',
         'description' => 'A blog post is a journal entry.',
         'menu_section' => 'Content',
         'preview_url_callback' => function($entity) {
             return $entity->getId();
         }
     );
 }
``` 

#### label

Defines how your entity is identified in the administration pages.

```php
/**
 * @return array Options
 */
public function getOptions() {
    return array(
        'label' => '{0} Posts|{1} Post|]1,Inf] Posts',
    );
}
``` 

If you pass a string you can leverage the power of translation pluralisation system

#### description

Describes your entity purpose.

``` php
/**
 * @return array Options
 */
public function getOptions() {
    return array(
        'description' => 'A blog post is a journal entry.',
    );
}
```

#### menu_section

You entities administration pages are grouped by this field in the menu and on dashboard.

```php
/**
 * @return array Options
 */
public function getOptions() {
    return array(
        'menu_section' => 'Content',
    );
}
``` 


#### preview_url_callback

Add a "view" link for all listed entities

``` php
/**
 * @return array Options
 */
public function getOptions() {
    return array(
        'preview_url_callback' => function ($entity) {
            return $entity->getId();
        },
    );
}
```

* Value for preview_url_callback must be a valid [callable](http://php.net/manual/en/language.types.callable.php)
* This callback must return a string that will be used directly in href attribute.
* To use the Symfony router inject it in your Admin service
* To use an absolute URL that will work on any environment inject a domain parameter in your Admin service
* Keep in mind your URL should with any front controller (i.e. app.php, app_dev.php)

##### Example on how to inject the router to build the preview url

``` php
private $router;

/**
 *
 */
function setRouter(Router $router)
{
    $this->router = $router;
}

/**
 * @return array Options
 */
public function getOptions() {
    return array(
        'preview_url_callback' => function ($entity) {
            return $this->router->generate('post_details', array('slug' => $entity->getSlug()));
        },
    );
}
```

And in your service definition

``` yaml
    app.post_admin:
        class: AppBundle\Admin\PostAdmin
        parent: shuwee_admin.admin_abstract
        calls:
             - [setRouter, ['@router.default']]
        tags:
            -  { name: shuwee.admin }
```

### Lifecycle callbacks

6 callbacks will be triggered on CRUD operations.

* prePersist: before an entity is persisted for the first time (create form)
* postPersist: after an entity is persisted for the first time (create form)
* preUpdate: before an entity is updated (update form)
* postUpdate: after an entity is updated (update form)
* preRemove: before an entity is removed (delete form)
* postRemove: after an entity is removed (delete form)

Implement the corresponding method in your Admin class and it will be executed.  All callbacks are given a single $entity parameter.

### Form rendering callbacks

In certain cases, you would like to change certain values of a form being rendered. For instance adding default values from cookie, session and/or Request.

The following callback(s) are triggered:

* preCreateFormRender: before the CreateEntity form is rendered (right before calling createView)

## Register your Admin class as a tagged service

``` yaml
app.post_admin:
    class: AppBundle\Admin\PostAdmin
    parent: shuwee_admin.admin_abstract
    tags:
      -  { name: shuwee.admin }
```

**Note:** there is no ordering functionality yet so currently the order in which your services are defined is the display order (in menu and dashboard).

## Admin class alias

Every AdminClass has an alias that is used in the url and in route names. By default, we use the lower cased version of your classname without the word `admin`.
E.g. `AppBundle\Admin\PostAdmin` will have an alias set to `post`.

To overwrite the default implement the `getAlias` method in your admin class:

``` php
/**
 * @return string
 */
public function getAlias() {
    return 'my-custom-alias'
}
```
