( function( $ ) {

	'use strict';

	var modelId, context;

	/**
	 * Always reload.
	 */
	var globalReloads = function() {

		if ( 'function' === typeof window.wpexSliderPro ) {
			wpexSliderPro();
		}

		if ( 'object' === typeof wpex ) {

			if ( 'function' === typeof wpex.lightbox ) {
				wpex.lightbox(); // because fancybox is dynamically loaded.
			}

			if ( 'function' === typeof wpex.equalHeights ) {
				wpex.equalHeights();
			}

			if ( 'function' === typeof wpex.masonryGrids ) {
				wpex.masonryGrids();
			}

			if ( 'function' === typeof wpex.hoverStyles ) {
				wpex.hoverStyles();
			}

			if ( 'function' === typeof wpex.parallax ) {
				wpex.parallax();
			}

			if ( 'function' === typeof wpex.overlaysMobileSupport ) {
				wpex.overlaysMobileSupport();
			}

			if ( 'function' === typeof wpex.customSelects ) {
				wpex.customSelects();
			}

			if ( 'function' === typeof wpex.menuWidgetAccordion ) {
				wpex.menuWidgetAccordion();
			}

		}

	};

	/**
	 * Remove duplicates.
	 *
	 * @todo convert to vanilla js
	 */
	var removeDups = function() {

		if ( modelId ) {
			context = document.querySelector( '[data-model-id="' + modelId + '"]' );
		}

		if ( ! context ) {
			return;
		}

		var $this = $( context ),
			$module = $this.children( ':first' );

		if ( ! $module.length ) {
			return;
		}

		// Shape dividers.
		var $topShapeDivider = $module.find( '> .wpex-shape-divider-top' );
		if ( $module.hasClass( 'wpex-has-shape-divider-top' ) ) {
			$topShapeDivider.not( ':first' ).remove();
		} else if ( $topShapeDivider.length ) {
			$topShapeDivider.remove();
		}

		var $bottomShapeDivider = $module.find( '> .wpex-shape-divider-bottom' );
		if ( $module.hasClass( 'wpex-has-shape-divider-bottom' ) ) {
			$bottomShapeDivider.not( ':first' ).remove();
		} else if ( $bottomShapeDivider.length ) {
			$bottomShapeDivider.remove();
		}

		// Overlays.
		var $overlays = $module.find( '> .wpex-bg-overlay-wrap' );
		if ( $module.hasClass( 'wpex-has-overlay' ) ) {
			$overlays.not( ':first' ).remove();
		} else if ( $overlays.length ) {
			$overlays.remove();
		}

		// Self-hosted Videos.
		var $videos = $module.find( '> .wpex-video-bg-wrap' );
		if ( $module.hasClass( 'wpex-has-video-bg' ) ) {
			$videos.not( ':first' ).remove();
		} else if ( $videos.length ) {
			$videos.remove();
		}

		// Parallax.
		var $parallax = $module.find( '> .wpex-parallax-bg' );
		if ( $module.hasClass( 'wpex-parallax-bg-wrap' ) ) {
			$parallax.not( ':first' ).remove();
		} else if ( $parallax.length ) {
			$parallax.remove();
		}

		// Video Backgrounds - Deprecated? @todo Remove & test.
		var $videoOverlays = $module.find( '> .wpex-video-bg-overlay' );
		if ( $videoOverlays.length ) {
			$videoOverlays.not( ':first' ).remove();
		}

	};

	parent.vc.events.on( 'shortcodes:add shortcodes:update shortcodes:clone', function( model ) {
		modelId = model.id;
	} );

	// Must use jQuery since we can't check for events registered with jQuery using javascript.
	$( window ).on( 'vc_reload', function() {
		globalReloads();
		removeDups();
	} );

} ) ( jQuery );