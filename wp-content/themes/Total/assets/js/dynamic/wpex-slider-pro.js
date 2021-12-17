( function( $, settings ) {
	'use strict';

	if ( 'function' !== typeof window.wpexSliderPro ) {
		window.wpexSliderPro = function( $context ) {

			if ( 'undefined' === typeof $.fn.sliderPro ) {
				return;
			}

			function dataValue( name, fallback ) {
				return ( 'undefined' !== typeof name ) ? name : fallback;
			}

			function getTallestEl( el ) {
				var tallest;
				var first = 1;
				el.each( function() {
					var $this = $( this );
					if ( first == 1 ) {
						tallest = $this;
						first = 0;
					} else {
						if ( tallest.height() < $this.height()) {
							tallest = $this;
						}
					}
				} );
				return tallest;
			}

			// Loop through each slider.
			$( '.wpex-slider', $context ).each( function() {

				// Declare vars.
				var $slider = $( this );
				var $data = $slider.data();
				var $slides = $slider.find( '.sp-slide' );

				// Lets show things that were hidden to prevent flash.
				$slider.find( '.wpex-slider-slide, .wpex-slider-thumbnails.sp-thumbnails,.wpex-slider-thumbnails.sp-nc-thumbnails' ).css( {
					'opacity': 1,
					'display': 'block'
				} );

				// Main checks.
				var $autoHeight = dataValue( $data.autoHeight, true );
				var $preloader = $slider.prev( '.wpex-slider-preloaderimg' );
				var $height = ( $preloader.length && $autoHeight ) ? $preloader.outerHeight() : null;
				var $heightAnimationDuration = dataValue( $data.heightAnimationDuration, 600 );
				var $loop = dataValue( $data.loop, false );
				var $autoplay = dataValue( $data.autoPlay, true );
				var $counter = dataValue( $data.counter, false );

				// Get height based on tallest item if autoHeight is disabled.
				if ( ! $autoHeight && $slides.length ) {
					var $tallest = getTallestEl( $slides );
					$height = $tallest.height();
				}

				// TouchSwipe.
				var $touchSwipe = true;

				if ( 'undefined' !== typeof $data.touchSwipeDesktop && ! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
					$touchSwipe = false;
				}

				// Run slider.
				$slider.sliderPro( {
					width                   : '100%',
					height                  : $height,
					responsive              : true,
					fade                    : dataValue( $data.fade, false ),
					fadeDuration            : dataValue( $data.animationSpeed, 600 ),
					slideAnimationDuration  : dataValue( $data.animationSpeed, 600 ),
					autoHeight              : $autoHeight,
					heightAnimationDuration : parseInt( $heightAnimationDuration ),
					arrows                  : dataValue( $data.arrows, true ),
					fadeArrows              : dataValue( $data.fadeArrows, true ),
					autoplay                : $autoplay,
					autoplayDelay           : dataValue( $data.autoPlayDelay, 5000 ),
					buttons                 : dataValue( $data.buttons, true ),
					shuffle                 : dataValue( $data.shuffle, false ),
					orientation             : dataValue( $data.direction, 'horizontal' ),
					loop                    : $loop,
					keyboard                : dataValue( $data.keyboard, false ),
					fullScreen              : dataValue( $data.fullscreen, false ),
					slideDistance           : dataValue( $data.slideDistance, 0 ),
					thumbnailsPosition      : 'bottom',
					thumbnailHeight         : dataValue( $data.thumbnailHeight, 70 ),
					thumbnailWidth          : dataValue( $data.thumbnailWidth, 70 ),
					thumbnailPointer        : dataValue( $data.thumbnailPointer, false ),
					updateHash              : dataValue( $data.updateHash, false ),
					touchSwipe              : $touchSwipe,
					thumbnailArrows         : false,
					fadeThumbnailArrows     : false,
					thumbnailTouchSwipe     : true,
					fadeCaption             : dataValue( $data.fadeCaption, true ),
					captionFadeDuration     : 600,
					waitForLayers           : true,
					autoScaleLayers         : true,
					forceSize               : dataValue( $data.forceSize, 'false' ),
					reachVideoAction        : dataValue( $data.reachVideoAction, 'playVideo' ),
					leaveVideoAction        : dataValue( $data.leaveVideoAction, 'pauseVideo' ),
					endVideoAction          : dataValue( $data.leaveVideoAction, 'nextSlide' ),
					fadeOutPreviousSlide    : true, // If disabled testimonial/content slides are bad.
					autoplayOnHover         : dataValue( $data.autoplayOnHover, 'pause' ),
					init: function( e ) {

						// Remove preloader image.
						$slider.prev( '.wpex-slider-preloaderimg' ).remove();

						// Add tab index and role attribute to slider arrows and buttons.
						var $navItems = $slider.find( '.sp-arrow, .sp-button, .sp-nc-thumbnail-container, .sp-thumbnail-container' );

						$navItems.attr( 'tabindex', '0' );
						$navItems.attr( 'role', 'button' );

						// Add aria-label to bullets and thumbnails.
						var $bullets = $slider.find( '.sp-button, .sp-thumbnail-container, .sp-nc-thumbnail-container' );
						$bullets.each( function( index, val ) {
							var slideN = parseInt( index + 1 );
							$( this ).attr( 'aria-label', settings.i18n.GOTO + ' ' + slideN );
						} );

						// Add label to next arrow.
						$slider.find( '.sp-previous-arrow' ).attr( 'aria-label', settings.i18n.PREV );

						// Add label to prev arrow.
						$slider.find( '.sp-next-arrow' ).attr( 'aria-label', settings.i18n.NEXT );

					},
					gotoSlide: function( e ) {

						// Stop autoplay when loop is disabled and we've reached the last slide.
						if ( ! $loop && $autoplay && e.index === $slider.find( '.sp-slide' ).length - 1 ) {
							$slider.data( 'sliderPro' ).stopAutoplay();
						}

						// Update counter.
						if ( $counter ) {
							$slider.find( '.sp-counter .sp-active' ).text( e.index + 1 );
						}

					}

				} ); // end sliderPro.

				// Get slider Data.
				var slider = jQuery( this ).data( 'sliderPro' );

				// Add counter pagination.
				if ( $counter ) {
					$( '.sp-slides-container', $slider ).append( '<div class="sp-counter"><span class="sp-active">' + ( parseInt( slider.getSelectedSlide() ) + 1 ) + '</span>/' + slider.getTotalSlides() + '</div>' );
				}

				// Accessability click events for bullets, arrows and no carousel thumbs.
				var $navItems = $slider.find( '.sp-arrow, .sp-button, .sp-nc-thumbnail-container, .sp-thumbnail-container' );
				$navItems.keypress( function( e ) {
					if ( e.keyCode == 13 ) {
						$( this ).trigger( 'click' );
					}
				} );

				// Accessability click events for thumbnails.
				var $thumbs = $( '.sp-thumbnail-container' );
				$thumbs.keypress( function( e ) {
					if ( e.keyCode == 13 ) {
						$( this ).closest( '.wpex-slider' ).sliderPro( 'gotoSlide', $( this ).index() );
					}
				} );

			} ); // End each.

			// WooCommerce: Prevent clicking on Woo entry slider.
			$( '.woo-product-entry-slider' ).click( function() {
				return false;
			} );

		};
	} // end typeof check

	// Start slider on load (must run on this event).
	$( window ).on( 'load', function() {
		wpexSliderPro();
	} );

	// Update sliders when opening modal windows.
	$( document ).on( 'wpex-modal-loaded', function() {
		$( '.fancybox-slide' ).find( '.wpex-slider' ).each( function() {
			var $this = $( this );
			if ( $this.data( 'sliderPro' ) ) {
				$this.sliderPro( 'update' );
			}
		} );
	} );

	// Refresh slider on WPBakery stretch row.
	$( document ).on( 'vc-full-width-row', function( $elements ) {
		if ( 'function' === typeof $.fn.sliderPro && 'function' === typeof window.wpexSliderPro ) {
			$( '[data-vc-full-width="true"] .wpex-slider' ).each( function() {
				if ( $( this ).data( 'sliderPro' ) ) {
					$( this ).sliderPro( 'resize' );
				}
			} );
		}
	} );

} )( jQuery, wpex_slider_pro_params );