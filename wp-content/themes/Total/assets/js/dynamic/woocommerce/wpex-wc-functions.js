( function( $ ) {

	'use strict';

	// @todo check if this is still needed.
	var customSelects = function() {
		if ( 'undefined' !== typeof jQuery && 'undefined' !== typeof jQuery.fn.select2 ) {
			jQuery( '#calc_shipping_country' ).select2();
		}
	};

	/**
	 * WooCommerce fix for WPBakery Rows.
	 */
	var wpbakeryFixes = function() {

		// Re-trigger wpbakery rows when clicking wc tabs.
		if ( 'undefined' !== typeof window.vc_rowBehaviour ) {
			$( '.wc-tabs .description_tab' ).click( function() {
				setTimeout( function() {
					vc_rowBehaviour();
				}, 10 );
			} );
		}

	};

	/**
	 * WooCommerce Gallery functions.
	 */
	var productGallery = function() {

		if ( 'undefined' === typeof wc_single_product_params || ! wc_single_product_params.flexslider.directionNav ) {
			return;
		}

		var $window = $( window );

		function setWooSliderArrows() {

			var $wooGallery = $( '.woocommerce-product-gallery--with-images' );

			if ( ! $wooGallery.length ) {
				return;
			}

			$wooGallery.each( function() {

				var $this = $( this );
				var $nav = $( this ).find( '.flex-direction-nav' );
				var $thumbsNav = $( this ).find( '.flex-control-thumbs' );

				if ( $nav.length && $thumbsNav.length ) {

					var thumbsNavHeight = $thumbsNav.outerHeight();
					var arrowHeight = $nav.find( 'a' ).outerHeight();
					var arrowTopoffset = - ( thumbsNavHeight + arrowHeight ) / 2;

					if ( arrowTopoffset ) {
						$this.find( '.flex-direction-nav a' ).css( 'margin-top', arrowTopoffset );
					}

				}

			} );

		}

		$window.on( 'load', function() {
			setWooSliderArrows();
		} );

		$window.resize( function() {
			setWooSliderArrows();
		} );

	};

	/**
	 * Woo Add to cart notice.
	 */
	var addToCartNotice = function() {
		if ( 'undefined' === typeof wpex_wc_params || 'undefined' === typeof wpex_wc_params.addedToCartNotice ) {
			return;
		}

		var noticeTxt = wpex_wc_params.addedToCartNotice ? wpex_wc_params.addedToCartNotice : 'was added to your shopping cart.';
		var notice = '';
		var image = '';
		var productName = '';

		$( 'body' ).on( 'click', '.product .ajax_add_to_cart', function() {
			$( '.wpex-added-to-cart-notice' ).remove(); // prevent build-up
			var parent = $( this ).closest( 'li.product' );
			image = parent.find( '.woocommerce-loop-product__link img:first' );
			productName = parent.find( '.woocommerce-loop-product__title' );
			if ( image.length && productName.length ) {
				notice = '<div class="wpex-added-to-cart-notice"><div class="wpex-inner"><div class="wpex-image"><img src="' + image.attr( 'src' ) + '"></div><div class="wpex-text"><strong>' + productName.text() + '</strong> ' + noticeTxt + '</div></div></div>';
			}
		} );

		$( document ).on( 'added_to_cart', function() {
			if ( notice ) {
				$( 'body' ).append( notice );
				notice = '';
			}
		} );

	};

	/**
	 * Add quantity buttons to quantity fields.
	 *
	 * Attached to the window so it can be re-triggered if needed.
	 */
	 if ( 'function' !== typeof window.wpexWooQBPrepend ) {
        window.wpexWooQBPrepend = function( $context ) {
			if ( ( 'undefined' !== typeof wpex_wc_params ) && ( 'undefined' !== typeof wpex_wc_params.quantityButtons ) ) {
				$( wpex_wc_params.quantityButtons ).addClass( 'buttons_added' ).append( '<div class="wpex-quantity-btns"><a href="#" class="plus"><span class="ticon ticon-angle-up"></span></a><a href="#" class="minus"><span class="ticon ticon-angle-down"></span></a></div>' );
			}
		};
	}

	/**
	 * Trigger actions when clicking quanity buttons.
	 *
	 * Attached to the window so it can be re-triggered if needed.
	 */
	 if ( 'function' !== typeof window.wpexWooQBActions ) {
        window.wpexWooQBActions = function( $context ) {

			$( document ).on( 'click', '.wpex-quantity-btns .plus, .wpex-quantity-btns .minus', function() {

				var $qty = $( this ).closest( '.quantity' ).find( '.qty' );
				var currentVal = parseFloat( $qty.val() );
				var max = parseFloat( $qty.attr( 'max' ) );
				var min = parseFloat( $qty.attr( 'min' ) );
				var step = $qty.attr( 'step' );

				if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) {
					currentVal = 0;
				}

				if ( max === '' || max === 'NaN' ) {
					max = '';
				}

				if ( min === '' || min === 'NaN' ) {
					min = 0;
				}

				if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) {
					step = 1;
				}

				if ( $( this ).is( '.plus' ) ) {

					if ( max && ( max == currentVal || currentVal > max ) ) {
						$qty.val( max );
					} else {
						$qty.val( currentVal + parseFloat( step ) );
					}

				} else {

					if ( min && ( min == currentVal || currentVal < min ) ) {
						$qty.val( min );
					} else if ( currentVal > 0 ) {
						$qty.val( currentVal - parseFloat( step ) );
					}

				}

				$qty.trigger( 'change' );

				return false;

			} );

		};

	}

	/*** Bind Events ***/
	$( document ).ready(function() {
		customSelects();
		wpbakeryFixes();
		productGallery();
		addToCartNotice();
		wpexWooQBPrepend();
		wpexWooQBActions();
	} );

	$( document.body ).on( 'updated_wc_div wc_update_cart cart_page_refreshed init_checkout updated_checkout', function( event ) {
		wpexWooQBPrepend();
	} );

	// Quick view plugin support.
	$( document ).on( 'qv_loader_stop quick-view-displayed', function( event ) {
		wpexWooQBPrepend();
		if ( 'object' === typeof wpex && 'function' === typeof wpex.customSelects ) {
			wpex.customSelects();
		}
	} );

} ) ( jQuery );