# Image Loader

## About

This is meant to be added into a theme. Specifically developed for Patchwerk from Jupitercow. The javascript should be loaded into your pages and the PHP class should be included.

## Future

Currently lazyload isn't really doing much, especially the in view portion. That support is next, followed by some advanced reveal animations.

## Dependencies

The javascript expects the below function to be defined in the global scope. In Patchwerk, it is at the top of the theme.js file.

```
/**
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
 */
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();
```

If you want to change the refresh rate on scroll events, you can set this variable lower: `timeToWaitForLast`. If it is set globally, it will get used in the image-loader.js script.

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

As an img tag:

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
  * Default: null
* `desktop`
  * Description: URL for desktop image, or when `image` or `post` are set, you can use this to set the desktop image size 
  * type: string
  * Default: ''
* `image`
  * Description: Pass an ACF image array, this would replace manually setting desktop and mobile images
  * type: array
  * Default: null
* `img`
  * Description: The format for the img tag
  * type: string
  * Default: '<img id="%s" class="%s" alt="%s"%s />'
* `lazy`
  * Description: Turn on lazy loading, options are: false/true, 'view' (load when in view)
  * type: boolean|string
  * Default: 'view'
* `mobile`
  * Description: URL for mobile image, or when `image` or `post` are set, you can use this to set the mobile image size
  * type: string
  * Default: ''
* `noscript`
  * Description: Format for noscript
  * type: string
  * Default: '<noscript>%s</noscript>'
* `opacity`
  * Description: Set the opacity of the background image, useful if you want to darken or lighten the image using the background color
  * type: integer
  * Default: null
* `parallax`
  * Description: Turn on parallax for background images, requires the parallax.js library
  * type: boolean
  * Default: false
* `parallax_bleed`
  * Description: Set the overlap for parallax, how much room above and below to move
  * type: integer
  * Default: 10
* `parallax_speed`
  * Description: Set the speed for parallax
  * type: integer
  * Default: 0.7
* `post`
  * Description: A post ID, when set, will grab the featured image from the post
  * type: integer|object
  * Default: null
* `spinner`
  * Description: Location for a loading spinner animation
  * type: string
  * Default: get_template_directory_uri() . '/assets/img/loading.gif'
* `styles`
  * Description: Add extra styles to be outputted in the styles attribute
  * type: string
  * Default: ''
