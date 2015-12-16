<?php

/**
 * @link              https://github.com/jupitercow/patch-image-loader
 * @since             1.0.0
 * @package           Patch_Image_Loader
 *
 * @wordpress-plugin
 * Plugin Name:       Patchwerk Image Loader
 * Plugin URI:        https://wordpress.org/plugins/patch-image-loader/
 * Description:       Simply the process of loading images for mobile and desktop and lazy loading.
 * Version:           1.0.1
 * Author:            Jupitercow
 * Author URI:        http://Jupitercow.com/
 * Contributor:       Jake Snyder
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$class_name = 'Patch_Image_Loader';
if (! class_exists($class_name) ) :

class Patch_Image_Loader
{
	/**
	 * The unique prefix for Sewn In.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $prefix         The string used to uniquely prefix for Sewn In.
	 */
	protected $prefix;

	/**
	 * Load the plugin.
	 *
	 * @since	1.0.0
	 * @return	void
	 */
	public function run()
	{
		$this->settings();

		add_action( 'init',                   array($this, 'init') );
	}

	/**
	 * Class settings
	 *
	 * @author  Jake Snyder
	 * @since	1.0.0
	 * @return	void
	 */
	public function settings()
	{
		$this->prefix      = 'patch';
	}

	/**
	 * Initialize the Class
	 *
	 * @author  Jake Snyder
	 * @since	1.0.0
	 * @return	void
	 */
	public function init()
	{
		add_action( "{$this->prefix}/image",  array($this, 'create_image') );
	}

	/**
	 * Create the image output
	 *
	 * @author  Jake Snyder
	 * @since	1.0.0
	 * @return	void
	 */
	public function create_image( $args )
	{
		$defaults = array(
			'alt'            => '',
			'background'     => false,
			'caption'        => '',
			'class'          => '',
			'data'           => null,
			'desktop'        => '',
			'height'         => null,
			'image'          => '',
			'img'            => '<img id="%s" class="%s" alt="%s"%s />',
			'lazy'           => 'view',
			'mobile'         => null,
			'mobile_height'  => null,
			'mobile_width'   => null,
			'noscript'       => '<noscript>%s</noscript>',
			'opacity'        => null,
			'parallax'       => false,
			'parallax_bleed' => null,
			'parallax_speed' => null,
			'post'           => null,
			'width'          => null,
			'spinner'        => get_template_directory_uri() . '/assets/img/loading.gif',
			'styles'         => '',
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		// Will hold the image data
		$info = array(
			'id'       => '',
			'class'    => "{$this->prefix}-load" . ($class ? " $class" : ''),
			'alt'      => '',
			'caption'  => '',
			'data'     => array(),
			'sizes'    => array(
				'desktop'  => null,
				'mobile'   => null,
			),
		);

		// Add default data items
		if ( is_array($data) )
		{
			$info['data'] = $data;
		}

		// If parallax is set, add the class and necessary data
		if ( $parallax )
		{
			$info['class'] .= " parallax";
			if ( $parallax_bleed )
			{
				$info['data']['bleed'] = $parallax_bleed;
			}
			if ( $parallax_speed )
			{
				$info['data']['speed'] = $parallax_speed;
			}
		}

		// Add lazy load to data
		if ( $lazy )
		{
			if ( 'view' === $lazy ) {
				$info['data']['lazy'] = 'view';
			} else {
				$info['data']['lazy'] = 'true';
			}
		}

		// Add opacity
		if ( $opacity ) {
			$info['data']['opacity'] = $opacity;
		}

		// Add background to data
		if ( $background ) {
			$info['data']['bg'] = 'true';
		}

		if ( $post )
		{
			// If post is an id, get the object
			if ( is_numeric($post) ) {
				$post = get_post($post);
			}
			// If post is not an object at this point, we can't use it, abort
			if ( ! is_object($post) ) { return false; }

			// Get the featured image
			$attach_id       = get_post_thumbnail_id( $post->ID );
			$attach_post     = get_post( $attach_id );

			$info['alt']     = ( $alt ) ? $alt : get_metadata( 'post', $attach_id, '_wp_attachment_image_alt', true );
			$info['caption'] = ( $caption ) ? $caption : $attach_post->post_excerpt;

			// If desktop is set to a size, use it, otherwise set to default
			if ( ! is_string($desktop) || false == ($src = wp_get_attachment_image_src($attach_id, $desktop)) ) {
				$desktop = 'large';
				$src = wp_get_attachment_image_src($attach_id, $desktop);
			}
			$info['sizes']['desktop'] = array(
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);

			// If mobile is set to a size, use it, otherwise set to default
			if ( ! is_string($mobile) || false == ($src = wp_get_attachment_image_src($attach_id, $mobile)) ) {
				$mobile = 'medium';
				$src = wp_get_attachment_image_src($attach_id, $mobile);
			}
			$info['sizes']['mobile'] = array(
				'url'    => $src[0],
				'width'  => $src[1],
				'height' => $src[2],
			);
		}
		elseif ( is_array($image) )
		{
			// If this isn't an ACF array, nothing to do...
			if ( ! isset($image['alt']) || ! isset($image['caption']) || empty($image['sizes']) ) { return false; }

			$info['alt']     = ( $alt ) ? $alt : $image['alt'];
			$info['caption'] = ( $caption ) ? $caption : $image['caption'];

			// If desktop is set to a size, use it, otherwise set to default
			if ( ! is_string($mobile) || empty($image['sizes'][$mobile]) ) {
				$desktop = 'large';
			}
			$info['sizes']['desktop'] = array(
				'url'    => $image['sizes'][$desktop],
				'width'  => $image['sizes'][$desktop . '-width'],
				'height' => $image['sizes'][$desktop . '-height'],
			);

			// If mobile is set to a size, use it, otherwise set to default
			if ( ! is_string($mobile) || empty($image['sizes'][$mobile]) ) {
				$mobile = 'medium';
			}
			$info['sizes']['mobile'] = array(
				'url'    => $image['sizes'][$mobile],
				'width'  => $image['sizes'][$mobile . '-width'],
				'height' => $image['sizes'][$mobile . '-height'],
			);
		}
		elseif ( is_string($desktop) || is_string($mobile) )
		{
			if ( is_string($desktop) )
			{
				$info['sizes']['desktop'] = array(
					'url'    => $desktop,
				);
				if ( $width ) {
					$info['sizes']['desktop']['width'] = $width;
				}
				if ( $height ) {
					$info['sizes']['desktop']['height'] = $height;
				}
			}

			if ( is_string($mobile) )
			{
				$info['sizes']['mobile'] = array(
					'url'    => $mobile,
				);
				if ( $mobile_width ) {
					$info['sizes']['mobile']['width'] = $mobile_width;
				}
				if ( $mobile_height ) {
					$info['sizes']['mobile']['height'] = $mobile_height;
				}
			}
		}
		else
		{
			return false;
		}

		// Add sizes to data
		$info['data']['sizes'] = json_encode($info['sizes']);
		// Set up data for output
		$data = '';
		foreach ( $info['data'] as $key => $value ) {
			$data .= " data-{$this->prefix}-load-$key='$value'";
		}

		$output = '';
		$style = ' style="' . $styles;
		if ( $background )
		{
			if ( $spinner ) {
				$spinner = esc_attr($spinner);
				$style  .= " background-image: url($spinner);";
			}
			$output = sprintf(' id="%s" class="%s" %s', esc_attr($info['id']), esc_attr($info['class']), $data); 
		}
		else
		{
			if ( $spinner ) {
				$data = " src=\"$spinner\" $data";
			}
			$output        = sprintf( $img, esc_attr($info['id']), esc_attr($info['class']), esc_attr($info['alt']), $data );
			$noscript_url  = ( ! empty($info['sizes']['desktop']['url']) ) ? $info['sizes']['desktop']['url'] : $info['sizes']['mobile']['url'];
			$data          = ' src = "' . esc_url($noscript_url) . '"';
			$noscript_img  = sprintf( $img, esc_attr($info['id']), esc_attr($info['class']) . " {$this->prefix}-load-no-script", esc_attr($info['alt']), $data );
			$output       .= sprintf( $noscript, $noscript_img );
		}
		$style .= '"';
		$data   = " $style $data";

		echo $output;
	}
}

$$class_name = new $class_name;
$$class_name->run();
unset($class_name);

endif;