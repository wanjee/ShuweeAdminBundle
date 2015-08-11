# Datagrid

## Main configuration

In you admin controller define the datagrid as follow

``` php
/**
 * @return Datagrid
 */
public function getDatagrid()
{
    /** @var Wanjee\Shuwee\AdminBundle\Datagrid $datagrid */
    $datagrid = new Datagrid($this, array(
            'limit_per_page' => 10,
            'default_sort_column' => 'id',
            'default_sort_order' => 'asc',
        )
    );

    $datagrid
        ->addField('id', 'text', array('label' => '#'))
        ->addField('title', 'text', array('label' => 'Title', 'sortable' => true))
        ->addField('status', 'boolean', array('label' => 'Published'))
        ->addField('published', 'date', array('label' => 'Date', 'date_format' => 'F j, Y'))
        ->addField('image', 'image', array('label' => 'Image', 'base_path' => 'uploads/images'))
        ->addField('comments', 'collection', array('label' => 'Comments'))
        ->addField('url', 'link', array('label' => 'URL', 'label_link' => 'Link', 'mailto' => false))
    ;

    return $datagrid;
}
```

Wanjee\Shuwee\AdminBundle\Datagrid::_construct() arguments are :
 
* *limit_per_page* : Number of items to display on a single datagrid page. Defaults to 10.  
* *default_sort_column* : Column used for default ordering. Defaults to 'id'.  
* *default_sort_order* : Direction of the default ordering. Defaults to 'asc'.  

Wanjee\Shuwee\AdminBundle\Datagrid::addField() arguments are :

* *Field name* : name of the field, in your entity, you want to expose
* *Field type* : type of formatter to use for display
* *Options* : array of options, depends on field type.  'label' is common.

## Datagrid field types

### Shared options

* *label*: Column title in datagrid. Expects string. Defaults to field name (ucfirst).
* *sortable: Is the column sortable? Defaults to false.
* *default_value*: What to display when field value cannot be displayed for the given type.  Defaults to null.
* *callback*: A callback function that will be used to get the value to display in the column.  It must return the expected type of object.
 
#### Callback

Callback option expects a [callable](http://php.net/manual/en/language.types.callable.php).  This callable will receive the entity as unique argument.
It must return the type expected by the given fieldtype.

Here are some callback examples.

```php
->addField(
    'callback',
    'text',
    array(
        'label' => 'Comments',
        'callback' => function ($entity) {
            // Example only as this will add one query per displayed row.
            $comments = $entity->getComments();
            return sizeof($comments);
        },
    )
)
```

```php
->addField(
    'callback',
    'boolean',
    array(
        'label' => 'Has comments',
        'callback' => function ($entity) {
            // Example only as this will add one query per displayed row.
            $comments = $entity->getComments();
            return !empty($comments);
        },
    )
)
```

```php
->addField(
    'callback',
    'link',
    array(
        'label' => 'Search',
        'label_link' => 'Search',
        'callback' => function ($entity) {
            return 'https://duckduckgo.com/?q=' . url_encode($entity->getKeyword();
        },
    )
)
```

### Boolean

``` php
->addField(
    'status', 
    'boolean', 
    array(
        'label' => 'Published'
    )
)
``` 

Cast field value to a boolean and display it as a "yes" or "no" label.

#### Options

* *label_true*: Label to show when field value resolves to TRUE. Expects null or string. Defaults to 'Yes'.
* *label_false*: Label to show when field value resolves to FALSE. Expects null or string. Defaults to 'No'.

#### Callback return type

If a callback is defined it must return a value that can be converted to boolean.

### Collection

``` php
->addField(
    'comments', 
    'collection', 
    array(
        'label' => 'Comments'
    )
);
``` 

Field value is escaped and truncated (80 chars) by default. Your collection must be an array or implement the ``Traversable`` interface, and its elements must have a ``__toString()`` method.

You will need to register default Symfony Twig extensions in your main config file to be able to use this type

``` yaml
services:
    twig.extension.text:
        class: Twig_Extensions_Extension_Text
        tags:
            - { name: twig.extension }
``` 

#### Options

* *truncate*: Length of max string length to display for the complete list.  Expects null or string. Defaults to 80.

#### Callback return type

If a callback is defined it must return an array or an object that implements the ``Traversable`` interface.

### Date

``` php
->addField(
    'published', 
    'date', 
    array(
        'label' => 'Date', 
        'date_format' => 'F j, Y'
    )
)
``` 

Expects field column to be a \Datetime instance ('datetime' column type), will throw an exception otherwise.

#### Options

* *date_format*: Format of the outputted date string.  Expects string. Must be any format supported by PHP date() function.  See http://php.net/manual/en/function.date.php.  Defaults to 'F j, Y'.

#### Callback return type

If a callback is defined it must return a [Datetime](http://php.net/manual/en/class.datetime.php)

### Image

``` php
->addField(
    'image', 
    'image', 
    array(
        'label' => 'Image', 
        'base_path' => 'uploads/images'
    )
)
``` 

If specified it is appended to the image value.  No trailing slash.
This type use LiipImagineBundle to resize the image.

#### Options

* *base_path*: Path in which images are stored relative to the project web root.  Should not include trailing slash.  Expects string.  Defaults to 'uploads'.

#### Callback return type

If a callback is defined it must return a string: the image name.

### Text

``` php
->addField(
    'id', 
    'text', 
    array(
        'label' => '#'
    )
);
``` 

Field value is escaped and truncated (80 chars) by default.

You will need to register default Symfony Twig extensions in your main config file to be able to use this type

``` yaml
services:
    twig.extension.text:
        class: Twig_Extensions_Extension_Text
        tags:
            - { name: twig.extension }
``` 
#### Options

* *truncate*: Length of max string length to display for the complete list.  Expects null or integer. Defaults to 80.
* *escape*: Set to FALSE if you need to output raw HTML.  Expects a boolean.  Defaults to TRUE (value is escaped).

#### Callback return type

If a callback is defined it must return a string.

### Link

``` php
->addField(
    'id', 
    'link', 
    array(
        'label' => '#', 
        'label_link' => 'Link', 
        'mailto' => false
    )
);
``` 

Displays text value as link or mailto.

#### Options

* *label_link*: Text to display on the link.  Expects null or string.
* *mailto*: Set to TRUE if you need to output an email address.  Expects a boolean.  Defaults to FALSE (no "mailto:").

#### Callback return type

If a callback is defined it must return a string.