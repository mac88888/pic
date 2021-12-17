( function( $, settings ) {

	'use strict';

	if ( 'function' !== typeof window.wpexLoadMore ) {
		window.wpexLoadMore = function() {

			var $loadMore = $( '.wpex-load-more' );

			if ( ! $loadMore.length ) {
				return;
			}

			$loadMore.each( function() {
				var $button = $( this );
				var $wrap = $( this ).parent( '.wpex-load-more-wrap' );
				var $buttonInner = $button.find( '.theme-button-inner' );
				var buttonData = $button.data( 'loadmore' );
				var $grid = $( buttonData.grid );
				var grid = document.querySelector( buttonData.grid );
				var page = 2; // when clicking the button the first results should be page 2.
				var loading = false;
				var isotope = null;
				var loadmoreData = buttonData;

				$wrap.css( 'min-height', $wrap.outerHeight() ); // prevent jump when showing loader icon.

				$button.on( 'click', function() {

					// Check if grid is rendered using isotope.
					// Needs to run on click since wpex.loadMore initiates early.
					if ( 'undefined' !== typeof Isotope ) {
						isotope = Isotope.data( grid );
					}

					if ( ! loading ) {

						loading = true;

						$wrap.addClass( 'wpex-loading' );
						$buttonInner.text( settings.i18n.loadingText );

						var data = {
							action: 'wpex_ajax_load_more',
							nonce: buttonData.nonce,
							page: page,
							loadmore: loadmoreData
						};

						$.post( settings.ajax_url, data, function( res ) {

							// Ajax request successful.
							if ( res.success ) {

								//console.log( res.data );

								// Increase page.
								page = page + 1;

								// Define vars
								var $newElements = $( res.data );
								$newElements.css( 'opacity', 0 ); // hide until images are loaded.

								// Tweak new items
								$newElements.each( function() {
									var $this = $( this );

									// Add duplicate tag to sticky incase someone want's to hide these.
									if ( $this.hasClass( 'sticky' ) ) {
										$this.addClass( 'wpex-duplicate' );
									}

									// Make sure masonry class exists to prevent issues - @todo deprecate.
									if ( $grid.hasClass( 'wpex-masonry-grid' ) ) {
										$this.addClass( 'wpex-masonry-col' );
									}

								} );

								$grid.append( $newElements ).imagesLoaded( function() {

									// Update counter (before we display items).
									var $counterEl = $grid.find( '[data-count]' );
									if ( $counterEl.length ) {
										loadmoreData.count = parseInt( $counterEl.data( 'count' ) );
										$counterEl.remove();
									}

									// Reload equal heights.
									if ( 'object' === typeof wpex && 'undefined' !== typeof wpex.equalHeights ) {
										wpex.equalHeights();
									}

									// Reload masonry.
									if ( isotope ) {
										isotope.appended( $newElements );
									}

									// Trigger event before displaying items.
									$grid.trigger( 'wpexLoadMoreAddedHidden', [$newElements] );

									// Show items
									$newElements.css( 'opacity', 1 );

									// Triger event after showing items.
									$grid.trigger( 'wpexLoadMoreAddedVisible', [$newElements] );

									// @todo Trigger the core WP "post-load" event
									//$( document.body ).trigger( 'post-load' );

									if ( 'object' === typeof wpex ) {

										// Reload overlay mobile support.
										if ( 'function' === typeof wpex.overlaysMobileSupport ) {
											wpex.overlaysMobileSupport();
										}

										// Inline hover styles.
										if ( 'function' === typeof wpex.hoverStyles ) {
											wpex.hoverStyles();
										}

									}

									// Reload sliders.
									if ( 'function' === typeof window.wpexSliderPro ) {
										wpexSliderPro( $newElements );
									}

									// Reload WP embeds.
									if ( 'undefined' !== typeof $.fn.mediaelementplayer ) {
										$newElements.find( 'audio, video' ).mediaelementplayer();
									}

									// Reset button.
									$wrap.removeClass( 'wpex-loading' );
									$buttonInner.text( settings.i18n.text );

									// Set correct focus.
									var $firstLink = $newElements.first().find( 'a' );

									if ( $firstLink.length ) {
										$firstLink.eq(0).focus();
									}

									// Hide button.
									if ( ( page - 1 ) == buttonData.maxPages ) {
										$button.hide();
									}

									// Set loading to false.
									loading = false;

								} ); // End images loaded.

							} // End success.

							else {

								$buttonInner.text( settings.i18n.failedText );

								console.log( res );

							}

						} ).fail( function( xhr, textGridster, e ) {

							console.log( xhr.responseText );

						} );

					} // end loading check.

					return false;

				} ); // End click.

			} ); // End each.

		}; // end wpex.wpexLoadMore.

	} // end wpex.wpexLoadMore check.

	$( document ).ready( function() {
		wpexLoadMore();
	} );

} ) ( jQuery, wpex_loadmore_params );