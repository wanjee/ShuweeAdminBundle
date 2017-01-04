# Datagrid

## Configuration
 
### Datagrid options

Implement getDatagridOptions() method to configure some behaviors of your datagrid.

```php
/**
 * @inheritdoc
 */
public function getDatagridOptions()
{
    return [
        'limit_per_page' => 25,
        'default_sort_column' => 'id',
        'default_sort_order' => 'asc',
        'show_actions_column' => true,
    ];
}
```
    
#### limit_per_page

Number of items to be displayed per datagrid page 

#### default_sort_column

Column on which the list must be ordered by default

#### default_sort_order

Default direction of the sort

#### show_actions_column

Allows the action column to be hidden if you disable view, edit and delete permissions on all items.   
    
## Fields

In your admin controller define the datagrid fields as follow:

``` php
/**
 * @inheritdoc
 */
public function attachFields(DatagridInterface $datagrid)
{
    $datagrid
        ->addField('id', DatagridFieldTypeText::class, array('label' => '#'))
        ->addField('title', DatagridFieldTypeText::class, array('label' => 'Title', 'sortable' => true))
        ->addField('status', DatagridFieldTypeBoolean::class, array('label' => 'Published'))
        ->addField('published', DatagridFieldTypeDate::class, array('label' => 'Date', 'date_format' => 'F j, Y'))
        ->addField('image', DatagridFieldTypeImage::class, array('label' => 'Image', 'base_path' => 'uploads/images'))
        ->addField('comments', DatagridFieldTypeCollection::class, array('label' => 'Comments'))
        ->addField('url', DatagridFieldTypeLink::class, array('label' => 'URL', 'label_link' => 'Link', 'mailto' => false))
    ;
}
```

Wanjee\Shuwee\AdminBundle\Datagrid::addField() arguments are :

* *Field name* : name of the field, in your entity, you want to expose
* *Field type* : the fully qualified class name of the type of formatter to use for display
* *Options* : array of options, depends on field type.  'label' is common.

Wanjee\Shuwee\AdminBundle\Datagrid::addAction() arguments are :

* *Action type* : Name of the class of a action type.  Currently only supported value is DatagridListAction::class
* *Route name* : Name of a route to redirect user to. This route must exist and must not require any argument.
* *Options* : array of options, depends on action type.  'label', 'icon' and 'btn-style' and 'classes' are common.

## Field types

### Shared options

* *label*: Column title in datagrid. Expects string. Defaults to field name (ucfirst).
* *sortable*: Is the column sortable? Defaults to false.
* *help*: Help text to be displayed on the column title.
* *default_value*: What to display when field value cannot be displayed for the given type.  Defaults to null.
* *callback*: A callback function that will be used to get the value to display in the column.  It must return the expected type of object.

#### Callback

Callback option expects a [callable](http://php.net/manual/en/language.types.callable.php).  This callable will receive the entity as unique argument.
It must return the type expected by the given fieldtype.

Here are some callback examples.

```php
->addField(
    'callback',
    DatagridFieldTypeText::class,
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
    DatagridFieldTypeBoolean::class,
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
    DatagridFieldTypeLink::class,
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
    DatagridFieldTypeBoolean::class,
    array(
        'label' => 'Published'
    )
)
```

Cast field value to a boolean and display it as a "yes" or "no" label.

#### Options

* *label_true*: Label to show when field value resolves to TRUE. Expects null or string. Defaults to 'Yes'.
* *label_false*: Label to show when field value resolves to FALSE. Expects null or string. Defaults to 'No'.
* *toggle*: Display a link to switch from one value to another directly in the datagrid.  Expects boolean.  Defaults to false.

#### Callback return type

If a callback is defined it must return a value that can be converted to boolean.

### Collection

``` php
->addField(
    'comments',
    DatagridFieldTypeCollection::class,
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
    DatagridFieldTypeDate::class,
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
    DatagridFieldTypeImage::class,
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
    DatagridFieldTypeText::class,
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
    DatagridFieldTypeLink::class,
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

## Filters

A filter are used in a form displayed top of the list.  They allow you to restrict what is displayed in the list.

``` php
/**
 *
 */
public function attachFilters(DatagridInterface $datagrid)
{
    $datagrid
        ->addFilter('title', DatagridFilterTypeText::class, ['label' => 'Title',])
        ->addFilter('status', DatagridFilterTypeChoice::class, ['label' => 'Published', 'choices' => ['Yes' => 1,'No' => 0,],])
        ->addFilter('user', DatagridFilterTypeEntity::class, ['label' => 'User', 'class' => User::class, 'choice_label' => 'username', 'placeholder' => 'Choose a user',]) ;
}
```

### Shared options

* *label*: Link label. Expects string.

## Filter types

### Text

``` php
->addFilter(
    'title', 
    DatagridFilterTypeText::class,
    [
        'label' => 'Title',
   ]
)
``` 

### Choice

Allows you to define a list of available values to chose from.
 
``` php
->addFilter(
    'status', 
    DatagridFilterTypeChoice::class, 
    [
        'label' => 'Published', 
        'choices' => [
            'Yes' => 1,
            'No' => 0,
         ],
    ]
) 
``` 

### Options

* *choices*: List of options to display.  Expects array.  See http://symfony.com/doc/current/reference/forms/types/choice.html#choices

### Entity
 
Allows you to filter the list on a entity relation.

``` php
->addFilter(
    'status', 
    DatagridFilterTypeEntity::class, 
    [
        'label' => 'User', 
        'class' => User::class,
        'choice_label' => 'username',
        'placeholder' => 'Choose a user',
    ]
) 
``` 
  
### Options

* *class*: List of options to display.  Expects string. *Required*. See http://symfony.com/doc/current/reference/forms/types/entity.html#class
* *choice_label*: Displayed property in the list. See http://symfony.com/doc/current/reference/forms/types/entity.html#choice-label 
* *placeholder*: This option determines whether or not a special "empty" option will appear at the top of a select widget. Expects string. Defaults to 'All' Shuwee does not allow disabling placeholder. http://symfony.com/doc/current/reference/forms/types/entity.html#placeholder

### Known limitations

Only work on Owner side of a ManyToOne relation.

## Actions

An action is an additional functionality on top of the datagrid.

### DatagridListAction

A List action is shown on top of the datagrid. Next to the regular "Create" button.
They will allow you to add custom commands from the list.  A typical use case is for an export link.

Shuwee comes built in with the following DatagridListAction:

* DatagridCreateAction   
   
### DatagridEntityAction

A Entity action is shown for each item shown in the list.  It will be added to the actions column of an entity
They will be displayed in the order they are defined.

Shuwee comes built in with the following DatagridEntityAction:

* DatagridUpdateAction
* DatagridDeleteAction
* DatagridViewAction

### Custom Actions

To create custom actions you have to:

* Create a custom Action classes extending either DatagridListAction or DatagridEntityActions:

``` php
class MyCustomListAction extends DatagridListAction {}
```

``` php
class MyCustomEntityAction extends DatagridEntityAction {}
```

* In your admin controller define the datagrid actions as follow:

``` php
/**
 * @inheritdoc
 */
public function attachActions(DatagridInterface $datagrid)
{
    $datagrid
        ->addAction(
            MyCustomListAction::class, 
            my_custom_route',
            [
                'label' => 'Export as CSV',
                'icon' => 'save-file',
                'btn-style' => 'primary',
                'classes' => 'export-link',
            ]
        )
        ->addAction(
            MyCustomListAction::class, 
            my_custom_route',
            [
                'label' => 'Export as CSV',
                'icon' => 'save-file',
                'btn-style' => 'primary',
                'classes' => 'export-link',
                // Must return an array that will be passed to Twig path(). 
                // Unknown parameters will be passed as query parameters.
                'path_parameters' => function($entity) {
                    return [
                        'id' => $entity->getId(),
                    ]
                }
            ]
        )
    ;
}
```

#### Action options:

* *label*: Link label. Expects string. Required.
* *icon*: Name of a bootstrap glyphicon.  Only the last part is needed. E.g.: use 'plus' to display 'glyphicon-plus'. See http://getbootstrap.com/components/#glyphicons.
* *btn-style*: One of the available bootstrap btn style.  Only the last part is needed. E.g.: use 'primary' for 'btn-primary' style.  See http://getbootstrap.com/css/#buttons-options
* *classes*: A string containing the classes of your choice that you want to add on the link.
* *path_parameters* (EntityAction only): the path parameters used for the route. The Closure is passed the entity

### Securing actions

See [Security|./Security.md] for explanation about the Admin::hasAccess class
