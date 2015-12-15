document.addEventListener('DOMContentLoaded', function() {

	if ( ! patch.mobile_breakpoint ) {
		patch.mobile_breakpoint = 768;
	}

	var loadType  = 'desktop',
		images    = document.querySelectorAll('.patch-load:not(.patch-loaded):not([data-patch-load-lazy=view])'),
		main_args = {
			bleed: 10,
			speed: 0.3,
		};

	if ( window.innerWidth <= patch.mobile_breakpoint ) {
		loadType = 'mobile';
	}

	function elementInViewport( el )
	{
	    var rect = el.getBoundingClientRect();

	    return (
	       rect.top  >= 0 &&
	       rect.left >= 0 &&
	       rect.top  <= (window.innerHeight || document.documentElement.clientHeight)
	    );
	}

	function loadImage( el, forceSize )
	{
		var elLoadType = ( forceSize ) ? forceSize : loadType;

		var sizes      = JSON.parse( el.getAttribute('data-patch-load-sizes') ),
			lazy       = el.getAttribute('data-patch-load-lazy'),
			background = el.getAttribute('data-patch-load-bg'),
			opacity    = el.getAttribute('data-patch-load-opacity'),
			imageUrl   = ( sizes[elLoadType] ? sizes[elLoadType].url : null ),
			paraArgs   = {
				bleed: 10,
				speed: 0.7,
			};

		// Generally, if no mobile, try desktop
		if ( ! imageUrl ) {
			imageUrl = ( sizes.desktop ? sizes.desktop.url : null );
		}

		if ( background )
		{
			if ( el.classList.contains('parallax') && 'mobile' !== elLoadType && 'undefined' !== typeof jQuery )
			{
				var bleed = el.getAttribute('data-patch-bleed'),
					speed = el.getAttribute('data-patch-speed');

				if ( bleed ) { paraArgs.bleed = bleed; }
				if ( speed ) { paraArgs.speed = speed; }

				paraArgs.imageSrc = imageUrl;

				// Reset the background (we want it to be transparent)
				el.style.background = '';
	
				// Activate the parallax container
				jQuery(el).parallax( paraArgs );

				if ( opacity && 1 > opacity ) {
					var paraImg = document.querySelector("img[src='" + imageUrl + "']");
					if ( paraImg ) {
						paraImg.style.opacity = opacity;
					}
				}
			}
			else
			{
				var bgStyle = "url('" + imageUrl + "')";
				if ( opacity && 1 > opacity ) {
					opacity = 1 - opacity;
					bgStyle = "linear-gradient( rgba(0, 0, 0, " + opacity + "), rgba(0, 0, 0, " + opacity + ") ), " + bgStyle;
				}
				// Add background image
				el.style.backgroundImage = bgStyle;
			}

			// Consider the image loaded and add class
			el.classList.add('patch-loaded');
		}
		else
		{
			// Add loaded class when image is loaded
			el.addEventListener('load', function() {
				this.classList.add('patch-loaded');
			});

			// Load the image
			el.src = imageUrl;
		}
	}

	if ( images.length )
	{
		for ( var i=0; i<images.length; i++ ) {
			loadImage(images[i]);
		}
	}


	/**
	 * Load images on scroll
	 */
	function scrollHandler()
	{
		images = document.querySelectorAll('.patch-load[data-patch-load-lazy=view]:not(.patch-loaded)');
		if ( images.length )
		{
			for ( var i=0; i<images.length; i++ )
			{
				if ( elementInViewport(images[i]) ) {
					loadImage(images[i]);
				}
			}
		}
	}
	scrollHandler();
	window.addEventListener('scroll', function() {
		waitForFinalEvent(scrollHandler, timeToWaitForLast, 'loadscroll');
	}, false);


	/**
	 * Switch images on resize
	 */
	function resizeHandler()
	{
		// Update variables
		images = document.getElementsByClassName('patch-loaded');
		if ( images.length )
		{
			for ( var i=0; i<images.length; i++ )
			{
				if ( window.innerWidth <= patch.mobile_breakpoint ) {
					loadImage(images[i], 'mobile');
				} else {
					loadImage(images[i], 'desktop');
				}
			}
		}
	}
	resizeHandler();
	window.addEventListener('resize', function() {
		waitForFinalEvent(resizeHandler, timeToWaitForLast, 'loadresize');
	});

});