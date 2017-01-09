# Forms

##  Collections support

ShuweeAdminBundle helps you adding collections to your forms by implementing the javascript behavior required to manage form collections (adding and removing items).


**Please note:** Custom prototype_name is currently not supported !

@TODO Display example of implementation.


## FilePreviewTypeExtension

Displays a preview of a previously uploaded image next to the upload form.
All you need to do is to add preview_base_path options to a field of type 'file'.  The value of this option should be
a "property" of your entity that will return the complete web path to your file. If you do not have such a property you
can create a fake accessor to it.

### Usage

#### In form type 

``` php
->add(
    'file',
    FileType::class,
    array(
        'label' => 'Image',
        'required' => false,
        // The extension will try to get value for WebPath property
        // If the property does not exist it will try accessors i.e. getYourPropertyName()
        'preview_base_path' => 'WebPath',
    )
)
```

#### In the related entity class

``` php
/**
 * @return null|string
 */
public function getWebPath()
{
    return null === $this->image
        ? null
        : $this->getUploadDir().'/'.$this->image;
}
```     

## GroupExtension

Group form elements into fieldset.

### Usage 

#### In form type

``` php
->add(
    'summary',
    TextareaType::class,
    array(
        'group' => 'Content',
    )
)
->add(
    'body',
    TextareaType::class,
    array(
        'group' => 'Content',
    )
)
->add(
    'created',
    TextareaType::class,
    array(
        'group' => 'Metadata',
    )
)
```

## HelpExtension

Displays a help text after a form field element.

### Usage

#### In form type 

``` php
->add(
    'summary',
    TextareaType::class,
    array(
        'help' => 'Keep is short and easy to understand.  It is a catchy introduction to your main content.',
    )
)
```

## MarkdownTextareaExtension

Displays a markdown editor on a text area.

### Usage

#### In form type 

``` php
->add(
    'summary',
    TextareaType::class,
    array(
        'markdown' => true,
    )
)
```
