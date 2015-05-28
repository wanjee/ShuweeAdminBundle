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
    $datagrid = new Datagrid($this);

    $datagrid
        ->addField('id', 'text', array('label' => '#'))
        ->addField('title', 'text', array('label' => 'Title'))
        ->addField('status', 'boolean', array('label' => 'Published'))
        ->addField('published', 'date', array('label' => 'Date', 'date_format' => 'F j, Y'))
        ->addField('image', 'image', array('label' => 'Image', 'base_path' => 'uploads/images'))
        ->addField('comments', 'collection', array('label' => 'Comments'))
    ;

    return $datagrid;
}
```

Wanjee\Shuwee\AdminBundle\Datagrid::addField() arguments are :

* *Field name* : name of the field, in your entity, you want to expose
* *Field type* : type of formatter to use for display
* *Options* : array of options, depends on field type.  'label' is common.

## Datagrid field types

### Shared options

* *label*: Column title in datagrid. Expects string. Defaults to field name (ucfirst).
* *default_value*: what to display when field value cannot be displayed for the given type.  Defaults to null.


### Boolean

``` php
->addField('status', 'boolean', array('label' => 'Published'))
``` 

Cast field value to a boolean and display it as a "yes" or "no" label.

#### Options

* *label_true*: Label to show when field value resolves to TRUE. Expects null or string. Defaults to 'Yes'.
* *label_false*: Label to show when field value resolves to FALSE. Expects null or string. Defaults to 'No'.


### Collection

``` php
->addField('comments', 'collection', array('label' => 'Comments'));
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

### Date

``` php
->addField('published', 'date', array('label' => 'Date', 'date_format' => 'F j, Y'))
``` 

Expects field column to be a \Datetime instance ('datetime' column type), will throw an exception otherwise.

#### Options

* *date_format*: Format of the outputted date string.  Expects string. Must be any format supported by PHP date() function.  See http://php.net/manual/en/function.date.php.  Defaults to 'F j, Y'.


### Image

``` php
->addField('image', 'image', array('label' => 'Image', 'base_path' => 'uploads/images'))
``` 

If specified it is appended to the image value.  No trailing slash.
This type use LiipImagineBundle to resize the image.

#### Options

* *base_path*: Path in which images are stored relative to the project web root.  Should not include trailing slash.  Expects string.  Defaults to 'uploads'.


### Text

``` php
->addField('id', 'text', array('label' => '#'));
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

* *truncate*: Length of max string length to display for the complete list.  Expects null or string. Defaults to 80.
* *escape*: Set to FALSE if you need to output raw HTML.  Expects a boolean.  Defaults to TRUE (value is escaped).