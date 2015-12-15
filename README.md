# Image Loader

## Example Usage

As a background image:

```php
<div<?php
	do_action('patch/image', array(
		'background' => true,
		'class'      => $class,
		'desktop'    => $desktop,
		'lazy'       => false,
		'mobile'     => $mobile,
	) );
	if ( $parallax_speed ) :
		echo ' data-patch-speed="' . $parallax_speed . '"';
	endif;
?>>
```

As an img:

```php
<?php
	do_action('patch/image', array(
		'alt'        => esc_attr($image['alt']),
		'desktop'    => $image['sizes']['gallery-main'],
		'lazy'       => true,
		'mobile'     => $image['sizes']['gallery-main'],
	) );
?>
```

## Options

* `alt`
  * Description: Image alt attribute
  * Type: string
  * Default: ''
* `background`
  * Description: Use a style attribute to set the background-image instead of using an `img` tag
  * type: boolean
  * Default: true
* `caption`
  * Description: Caption for the image
  * type: string
  * Default: ''
* `class`
  * Description: Add extra classes to the tag
  * type: string
  * Default: ''
* `data`
  * Description: Add extra data fields to the tag
  * type: array
  * Default: array()
* `desktop`
  * Description: URL for desktop image
  * type: string
  * Default: ''
* `height`
  * Description: 
  * type: 
  * Default: 
* `image`
  * Description: 
  * type: 
  * Default: 
* `img`
  * Description: 
  * type: 
  * Default: 
* `lazy`
  * Description: 
  * type: 
  * Default: 
* `mobile`
  * Description: 
  * type: 
  * Default: 
* `mobile_height`
  * Description: 
  * type: 
  * Default: 
* `mobile_width`
  * Description: 
  * type: 
  * Default: 
* `noscript`
  * Description: 
  * type: 
  * Default: 
* `opacity`
  * Description: 
  * type: 
  * Default: 
* `post`
  * Description: 
  * type: 
  * Default: 
* `width`
  * Description: 
  * type: 
  * Default: 
* `spinner`
  * Description: 
  * type: 
  * Default: 
* `styles`
  * Description: 
  * type: 
  * Default: 

			'alt'           => '',
			'background'    => '',
			'caption'       => '',
			'class'         => '',
			'data'          => null,
			'desktop'       => '',
			'height'        => null,
			'image'         => '',
			'img'           => '<img id="%s" class="%s" alt="%s"%s />',
			'lazy'          => 'view',
			'mobile'        => null,
			'mobile_height' => null,
			'mobile_width'  => null,
			'noscript'      => '<noscript>%s</noscript>',
			'opacity'       => false,
			'post'          => null,
			'width'         => null,
			'spinner'       => get_template_directory_uri() . '/assets/img/loading.gif',
			'styles'        => '',
