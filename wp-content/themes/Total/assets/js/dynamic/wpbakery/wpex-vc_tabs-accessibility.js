( function( $ ) {
	'use strict';

	// Add tabs aria and role attributes.
	document.querySelectorAll( '.vc_tta-tabs-list' ).forEach( function( element ) {
		element.setAttribute( 'role', 'tablist' );
	} );

	document.querySelectorAll( '.vc_tta-tab > a' ).forEach( function( element ) {
		element.setAttribute( 'role', 'tab' );
		var tab = element.closest( '.vc_tta-tab' );
		var ariaSelected = tab.classList.contains( 'vc_active' ) ? 'true' : 'false';
		element.setAttribute( 'aria-selected', ariaSelected );
	} );

	document.querySelectorAll( '.vc_tta-panel-body' ).forEach( function( element ) {
		element.setAttribute( 'role', 'tabpanel' );
	} );

	// Change arias on click.
	// Must use jQuery since the event fires with jQuery.
	$( document ).on( 'click.vc.tabs.data-api', '[data-vc-tabs]', function( e ) {
		var $this = $( this );
		$this.closest( '.vc_tta-tabs-list' ).find( '.vc_tta-tab > a' ).attr( 'aria-selected', 'false' );
		$this.parent( '.vc_tta-tab' ).find( '> a').attr( 'aria-selected', 'true' );
	} );

	// Add Tab arrow navigation support.
	var $tabContainers = $( '.vc_tta-container' );

	var tabClick = function( $thisTab, $allTabs, $tabPanels, i ) {
		$allTabs.attr( 'tabindex', -1 );
		$thisTab.attr( 'tabindex', 0 ).focus().click();
	};

	$tabContainers.each( function() {

		var $tabContainer = $( this );
		var $tabs = $tabContainer.find( '.vc_tta-tab > a' );
		var $panels = $tabContainer.find( '.vc_tta-panels' );

		$tabs.each( function( index ) {

			var $tab = $( this );

			if ( 0 == index ) {
				$tab.attr( 'tabindex', 0 );
			} else {
				$tab.attr( 'tabindex', -1 );
			}

			$tab.on( 'keydown', function( e ) {
				var $this = $( this );
				var keyCode = e.keyCode || e.which;
				var $nextTab = $this.parent().next().is( 'li.vc_tta-tab' ) ? $this.parent().next().find( 'a' ) : false;
				var $previousTab = $this.parent().prev().is( 'li.vc_tta-tab' ) ? $this.parent().prev().find( 'a' ) : false;
				var $firstTab = $this.parent().parent().find( 'li.vc_tta-tab:first' ).find( 'a' );
				var $lastTab = $this.parent().parent().find( 'li.vc_tta-tab:last' ).find( 'a' );

				switch( keyCode ) {

					// Left/Up.
					case 37 :
					case 38 :
						e.preventDefault();
						e.stopPropagation();
						if ( ! $previousTab) {
							tabClick( $lastTab, $tabs, $panels );
						} else {
							tabClick( $previousTab, $tabs, $panels );
						}
					break;

					// Right/Down.
					case 39 :
					case 40 :
						e.preventDefault();
						e.stopPropagation();
						if ( ! $nextTab ) {
							tabClick( $firstTab, $tabs, $panels );
						} else {
							tabClick( $nextTab, $tabs, $panels );
						}
					break;

					// Home.
					case 36 :
						e.preventDefault();
						e.stopPropagation();
						tabClick( $firstTab, $tabs, $panels );
						break;

					// End.
					case 35 :
						e.preventDefault();
						e.stopPropagation();
						tabClick( $lastTab, $tabs, $panels );
					break;

					// Enter/Space.
					case 13 :
					case 32 :
						e.preventDefault();
						e.stopPropagation();
					break;

				} // end switch.

			} );

		} );

	} );

} ) ( jQuery );