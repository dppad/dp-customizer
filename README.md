# dp-customizer
WordPress Customizer API wrapper

##Example Usage
```php
// creates three sections labeled "Footer", "Contact", and "Social" in the WordPress Customizer
$fields = array(
    array(
        'sectionName' => 'Footer', // Section Title
        'setting' => 'footer-bg-img', // Field name. Value retrieved with get_theme_mod('footer-bg-img')
        'label' => 'Footer Background Image', // label above input field
        'type' => 'image', // input type. Only "image" and "text" types currently supported
        'default' => 'https://placehold.it/720x480' // default value
    ),
    
    array(
        'sectionName' => 'Contact',  
        'setting' => 'phone-number', 
        'label' => 'Phone Number (ex: 555-555-5555)',
        'type' => 'text',
        'default' => '555-555-5555'
    ),

    array(
        'sectionName' => 'Contact',
        'setting' => 'fax',
        'label' => 'Fax Number (ex: 555-555-5555)',
        'type' => 'text',
        'default' => '123-555-5555'
    ),

    array(
        'sectionName' => 'Social',
        'setting' => 'link-facebook',
        'label' => 'Facebook Page',
        'type' => 'text',
        'default' => 'https://www.facebook.com/'
    ),

    array(
        'sectionName' => 'Social',
        'setting' => 'link-linkedin',
        'label' => 'LinkedIn Page',
        'type' => 'text',
        'default' => 'https://www.linkedin.com/'
    )
);
// check if plugin is activated
if (class_exists('Customizer')) {
    $DPCustomizer = new Customizer('your_namespace', $fields);
}

$facebook_link = get_theme_mod('link-facebook'); // returns "https://www.facebook.com/"
$image_url = get_theme_mod('footer-bg-img') // returns "https://placehold.it/720x480/"
```
