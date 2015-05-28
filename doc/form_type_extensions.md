# Form type extensions

ShuweeAdminBundle comes with form extensions you can use in your admin form types.


## FilePreviewTypeExtension

Displays a preview of a previously uploaded image next to the upload form.
All you need to do is to add preview_base_path options to a field of type 'file'.  The value of this option should be
a "property" of your entity that will return the complete web path to your file. If you do not have such a property you
can create a fake accessor to it.

In the form type: 

``` php
->add(
    'file',
    'file',
    array(
        'label' => 'Image',
        'required' => false,
        // The extension will try to get value for WebPath property
        // If the property does not exist it will try accessors i.e. getYourPropertyName()
        'preview_base_path' => 'WebPath',
    )
)
```

In your entity:

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