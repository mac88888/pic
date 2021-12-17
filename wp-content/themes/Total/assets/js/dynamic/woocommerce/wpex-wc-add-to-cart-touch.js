( function() {

	'use strict';

	/**
	 * Touch support for the product entry add to cart button.
	 */
	var addToCartTouch = function() {

		var supportsTouch = (window.matchMedia("(any-pointer: coarse)").matches);

		if ( ! supportsTouch ) {
			return;
		}

		var buttons = document.querySelectorAll( '.wpex-loop-product-add-to-cart' );
		var images = document.querySelectorAll( '.wpex-loop-product-images' );

		if ( ! buttons.length || ! images.length ) {
			return;
		}

		var touchmoved = false;

		var hideAll = function() {
			document.querySelectorAll( '.wpex-loop-product-images.wpex-touched' ).forEach( function( element ) {
				element.classList.remove( 'wpex-touched' );
			} );
		};

		var clickingOutside = function( event ) {
			if ( ! event.target.closest( '.wpex-touched' ) ) {
				hideAll();
			}
		};

		images.forEach( function( element ) {
			element.addEventListener( 'touchend', function( event ) {

				if ( event.target.closest( '.button' ) ) {
					return; // clicking on add to cart link
				}

				if ( touchmoved ) {
					hideAll();
					return;
				}

				if ( element.classList.contains( 'wpex-touched' ) ) {
					return;
				}

				hideAll();
				element.classList.add( 'wpex-touched' );
				event.preventDefault();
				event.stopPropagation();

			}, { passive: true } );

			element.addEventListener( 'touchmove', function( event ) {
				touchmoved = true;
			}, { passive: true } );

			element.addEventListener( 'touchstart', function( event ) {
				touchmoved = false;
			}, { passive: true } );

		} );

		document.addEventListener( 'touchstart', clickingOutside, { passive: true } );
		document.addEventListener( 'touchmove', clickingOutside, { passive: true } );

	};

	addToCartTouch();

})();