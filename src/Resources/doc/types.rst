Shuwee Datagrid field types
===========================

Datagrid configuration
----------------------

In you admin controller define the datagrid as follow

.. code-block:: php

    /** @var Wanjee\Shuwee\AdminBundle\Datagrid $datagrid */
    $datagrid = new Datagrid($this);

    $datagrid
        ->addField('status', 'boolean', array('label' => 'Published'))
        ->addField('image', 'image', array('label' => 'Image', 'base_path' => 'uploads/images'))
        ->addField('id', 'text', array('label' => '#'))
    ;

    return $datagrid;

Wanjee\Shuwee\AdminBundle\Datagrid::addField() arguments are :

* Field name : name of the field, in your entity, you want to expose
* Field type : type of formatter to use for display
* Options : array of options, depends on field type.  'label' is common.

Boolean
-------

.. code-block:: php

    ->addField('status', 'boolean', array('label' => 'Published'))

Cast field value to a boolean and display it as "yes" or "no"

Image
-----

.. code-block:: php

    ->addField('image', 'image', array('label' => 'Image', 'base_path' => 'uploads/images'))

base_path option is required if the full path to image (relative to project root) is not stored in field value.
If specified it is appended to the image value.  No trailing slash.
This use type use liipImagineBundle to resize the image.


Text
----

.. code-block:: php

    ->addField('id', 'text', array('label' => '#'));

Field value is escaped and truncated (80 chars) by default.  Option will come later to

You will need to register default Symfony Twig extensions in your main config file to be able to use this type

.. code-block:: yaml

    services:
        twig.extension.text:
            class: Twig_Extensions_Extension_Text
            tags:
                - { name: twig.extension }

Collection
----------

.. code-block:: php

    ->addField('things', 'collection', array('label' => 'Things'));

Field value is escaped and truncated (80 chars) by default. Your collection must be an array or implement the ``Traversable`` interface, and its elements must have a ``__toString()`` method.

You will need to register default Symfony Twig extensions in your main config file to be able to use this type

.. code-block:: yaml

    services:
        twig.extension.text:
            class: Twig_Extensions_Extension_Text
            tags:
                - { name: twig.extension }
