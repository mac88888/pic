( function( $ ) {

	'use strict';

	if ( ! $( 'div.infinite-scroll-nav' ).length ) {
		console.log( '.infinite-scroll-nav element not found' );
		return;
	}

	if ( 'function' !== typeof window.wpexInfiteScroll ) {
		window.wpexInfiteScroll = function() {

			var grid = document.querySelector( '#blog-entries' );

			var callback = function( newElements ) {

				var $newElements = $( newElements );

				if ( grid.classList.contains( 'wpex-masonry-grid' ) && 'undefined' !== typeof Isotope ) {
					var isotope = Isotope.data( grid );
					if ( isotope ) {
						isotope.appended( newElements );
					}
				}

				newElements.forEach( function( element ) {
					element.classList.remove( 'wpex-infscr-item-loading' ); // make items visible
				} );

				$( grid ).trigger( 'wpexinfiniteScrollLoaded', [$newElements] );

				if ( 'function' === typeof window.wpexSliderPro ) {
					window.wpexSliderPro( $newElements );
				}

				if ( 'object' === typeof wpex ) {
					if ( 'function' === typeof wpex.equalHeights ) {
						wpex.equalHeights();
					}
					if ( 'undefined' !== typeof $.fn.mediaelementplayer ) {
						$newElements.find( 'audio, video' ).mediaelementplayer();
					}
				}

				// Trigger resize event to fix any possible layout issues.
				window.dispatchEvent( new Event( 'resize' ) );

			};

			$( grid ).infinitescroll( wpexInfiniteScroll, function( newElements ) {
				if ( 'function' === typeof imagesLoaded ) {
					newElements.forEach( function( element ) {
						element.classList.add( 'wpex-infscr-item-loading' ); // hide newItems
					} );
					imagesLoaded( newElements, function( instance ) {
						callback( newElements );
					} );
				} else {
					callback( newElements );
				}
			} );

		};
	}

	window.addEventListener( 'load', wpexInfiteScroll );

} ) ( jQuery );