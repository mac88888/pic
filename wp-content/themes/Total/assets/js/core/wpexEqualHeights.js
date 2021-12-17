if ( 'function' !== window.wpexEqualHeights ) {
	window.wpexEqualHeights = function( parent, child, context ) {

		if ( ! parent || ! child ) {
			return;
		}

		if ( ! context || ! context.childNodes ) {
			context = document;
		}

		var allParents = context.querySelectorAll( parent );

		if ( ! allParents ) {
			return;
		}

		// Set initial heights.
		allParents.forEach( function( parent ) {
			if ( 'function' === typeof imagesLoaded ) {
				var imgLoad = new imagesLoaded( parent );
				imgLoad.on( 'always', function( instance ) {
					setHeights( parent, false )
				} );
			} else {
				setHeights( parent, false );
			}
		} );

		// Update heights on resize
		window.addEventListener( 'resize', function() {
			allParents.forEach( function( parent ) {
				setHeights( parent, true );
			} );
		} );

		// Set element heights
		function setHeights( parent, reset ) {

			var tallestHeight = 0;
			var allChilds = parent.querySelectorAll( child );

			if ( ! allChilds ) {
				return;
			}

			// Get tallets item height.
			allChilds.forEach( function( element ) {
				if ( element.classList.contains( 'vc_column-inner' ) && element.closest( '.vc_row.vc_inner' ) ) {
					return; // @todo remove? This shouldn't be needed since 4.0.
				}
				if ( reset ) {
					element.style.height = ''; // reset heights so we can calculate new heights.
				}
				var elementHeight = element.getBoundingClientRect().height;
				if ( elementHeight > tallestHeight ) {
					tallestHeight = elementHeight;
				}
			} );

			if ( ! tallestHeight ) {
				return;
			}

			// Set heights
			allChilds.forEach( function( element ) {
				element.style.height = tallestHeight + 'px';
			} );

			// Re-trigger isotope if needed, prevents issues since
			// the equal heights may run after the isotope grid is rendered.
			if ( 'undefined' !== typeof Isotope ) {
				var iso = Isotope.data( parent );
				if ( iso ) {
					iso.layout();
				}
			}

		}

	}
}

/* Fallback **/
( function() {

	if ( 'undefined' === typeof jQuery ) {
		return;
	}

	jQuery.fn.wpexEqualHeights = function() {
		var elements = this.get();
		if ( elements ) {
			console.log( 'The jQuery wpexEqualHeights prototype has been deprecated. Please use the new wpexEqualHeights function.' );
		}
	}

})();