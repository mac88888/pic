( function( $ ) {

	'use strict';

	/*
	 * Enable/disable theme panel modules.
	 */
	function wpexPanelEnableDisableModules() {

		// Display save nag when changing widget inputs.
		$( '.wpex-theme-panel__form-widgets input[type="text"]' ).change( function() {
			$( '.wpex-theme-panel__savenag' ).show();
		} );

		// Save settings from save nag link.
		$( '.wpex-theme-panel__savenag a' ).click( function( e ) {
			e.preventDefault();
			$( '.wpex-theme-panel__form #submit' ).click();
		} );

		// Display save nag when changing fields.
		$( '.wpex-checkbox, .wpex-theme-panel__widget input' ).change( function() {
			$( '.wpex-theme-panel__savenag' ).show();
			var $parent = $( this ).parents( '.wpex-theme-panel__setting' );
			var data = $parent.data( 'status' );
			if ( $parent.hasClass( 'wpex-disabled' ) ) {
				$parent.addClass( 'wpex-enabled' );
				$parent.removeClass( 'wpex-disabled' );
			} else {
				$parent.addClass( 'wpex-disabled' );
				$parent.removeClass( 'wpex-enabled' );
			}
		} );

	}

	/*
	 * Panel toggles.
	 */
	function wpexPanelInfoToggle() {
		$( '.wpex-theme-panel__setting-toggle' ).click( function( e ) {
			e.preventDefault();
			var $this = $( this ),
				desc = $this.parent().next(),
				expanded = $this.attr( 'aria-expanded' );
			$( '.wpex-theme-panel__setting-info' ).hide();
			$( '.wpex-theme-panel__setting-toggle' ).attr( 'aria-expanded', 'false' );
			if ( 'true' == expanded ) {
				desc.hide();
				$this.attr( 'aria-expanded', 'false' );
			} else {
				desc.show();
				$this.attr( 'aria-expanded', 'true' );
			}
		} );
	}

	/*
	 * Theme Panel sort
	 */
	function wpexThemePanelSort() {
		var activeCat = 'all';
		var activeStatus = '';
		$( '.wpex-theme-panel__sort-item' ).click( function( event ) {
			event.preventDefault();
			var $this = $( this );
			var $list = $this.parents( '.wpex-theme-panel__sort' );
			var filter = $( this ).data( 'filter' );
			$list.find( '.wpex-theme-panel__sort-item' ).removeClass( 'wpex-theme-panel__sort-item--active' );
			$this.addClass( 'wpex-theme-panel__sort-item--active' );
			if ( 'all' === filter ) {
				$( '.wpex-theme-panel__setting' ).removeClass( 'wpex-hidden' );
				activeCat = 'all';
			} else if ( 'status-all' === filter ) {
				if ( activeCat && 'all' !== activeCat ) {
					$( '.wpex-theme-panel__setting[data-category="' + activeCat + '"]' ).removeClass( 'wpex-hidden' );
				} else {
					$( '.wpex-theme-panel__setting' ).removeClass( 'wpex-hidden' );
				}
				activeStatus = 'all';
			} else if ( 'enabled' === filter ) {
				if ( activeCat && 'all' !== activeCat ) {
					$( '.wpex-theme-panel__setting[data-status="enabled"][data-category="' + activeCat + '"]' ).removeClass( 'wpex-hidden' );
					$( '.wpex-theme-panel__setting[data-status="disabled"][data-category="' + activeCat + '"]' ).addClass( 'wpex-hidden' );
				} else {
					$( '.wpex-theme-panel__setting' ).removeClass( 'wpex-hidden' );
					$( '.wpex-theme-panel__setting[data-status="disabled"]' ).addClass( 'wpex-hidden' );
				}
				activeStatus = 'enabled';
			} else if ( 'disabled' === filter ) {
				if ( activeCat && 'all' !== activeCat ) {
					$( '.wpex-theme-panel__setting[data-status="disabled"][data-category="' + activeCat + '"]' ).removeClass( 'wpex-hidden' );
					$( '.wpex-theme-panel__setting[data-status="enabled"][data-category="' + activeCat + '"]' ).addClass( 'wpex-hidden' );
				} else {
					$( '.wpex-theme-panel__setting' ).removeClass( 'wpex-hidden' );
					$( '.wpex-theme-panel__setting[data-status="enabled"]' ).addClass( 'wpex-hidden' );
				}
				activeStatus = 'disabled';
			} else {
				$( '.wpex-theme-panel__setting' ).addClass( 'wpex-hidden' );
				$( '.wpex-theme-panel__setting[data-category="' + filter + '"]' ).removeClass( 'wpex-hidden' );
				activeCat = filter;
			}
		} );
	}

	/*
	 * Chosen dropdowns.
	 */
	function wpexChosenSelect() {

		if ( 'undefined' === typeof $.fn.chosen ) {
			return;
		}

		$( '.wpex-chosen' ).chosen( {
			disable_search_threshold: 10
		} );

		$( '.wpex-chosen' ).next( '.chosen-container' ).addClass( 'wpex-chosen-icon-container' );

		$( '.wpex-chosen-multiselect' ).chosen( {
			search_contains: true
		} );

		if ( 'undefined' !== typeof $.fn.wpexChosenIcon ) {
			$( '.wpex-chosen-icon-select' ).wpexChosenIcon( {
				search_contains: true,
				disable_search_threshold: 10
			} );
		}

		$( '#wpex_header_builder_select_chosen, #wpex_footer_builder_select_chosen' ).css( 'width', '300' );

	}

	/*
	 * Color pickers.
	 */
	function wpexColorPicker() {
		if ( 'undefined' === typeof $.fn.wpColorPicker ) {
			return;
		}
		$( '.wpex-color-field' ).wpColorPicker();
	}

	/*
	 * Admin Tabs
	 *
	 * @ine 5.1.2
	 */
	 function wpexAdminTabs() {
		// New tabs added in 5.1.2.
		$( '.wpex-admin-tabs__tab' ).click( function( e ) {
			e.preventDefault();
			var $this = $( this );
			var tab = $this.attr( 'aria-controls' );
			$( '.wpex-admin-tabs__tab' ).removeClass( 'nav-tab-active' ).attr( 'aria-selected', 'false' );
			$this.addClass( 'nav-tab-active' ).attr( 'aria-selected', 'true' );
			$( '.wpex-admin-tabs__panel' ).each( function() {
				var $this = $( this );
				$this.removeClass( 'wpex-admin-tabs__panel--active' );
				if ( tab === $this.attr( 'id' ) ) {
					$this.addClass( 'wpex-admin-tabs__panel--active' );
				}
			} );
		} );

		var tabFocus = 0;

		$( '.wpex-admin-tabs__list' ).keydown( function( e ) {

			var $tabs = $( '.wpex-admin-tabs__tab' );

			// Move right.
			if (e.keyCode === 39 || e.keyCode === 37) {
				$tabs.eq(tabFocus).attr( 'tabindex', -1 );
				if (e.keyCode === 39) {
					tabFocus++;
					// If we're at the end, go to the start.
					if (tabFocus >= $tabs.length) {
						tabFocus = 0;
					}
				// Move left.
				} else if (e.keyCode === 37) {
					tabFocus--;
					// If we're at the start, move to the end.
					if (tabFocus < 0) {
						tabFocus = $tabs.length - 1;
					}
				}

			}

			$tabs.eq(tabFocus).attr( 'tabindex', 0 );
      		$tabs.eq(tabFocus).focus();

		} );
	}

	/*
	 * Older Tabs.
	 */
	function wpexPanelTabs() {
		var $tabs = $( '.wpex-panel-js-tabs a' );
		if ( ! $tabs.length ) {
			return;
		}
		var $firstTab = $( '.wpex-panel-js-tabs a.nav-tab-active' );
		var $firstTabHash = $firstTab.attr( 'href' ).substring(1);
		$( '.wpex-' + $firstTabHash ).show();
		$( $tabs ).each( function() {
			var $this = $( this );
			$this.click( function( e ) {
				e.preventDefault();
				$tabs.removeClass( 'nav-tab-active' );
				$this.addClass( 'nav-tab-active' );
				var $hash = $( this ).attr( 'href' ).substring(1);
				$( '.wpex-tab-content' ).hide();
				$( '.wpex-' + $hash ).show();
			} );
		} );
	}

	/*
	 * Media upload.
	 */
	function wpexMediaUpload() {

		// Select & insert image
		$( '.wpex-media-upload-button' ).click( function( e ) {
			e.preventDefault();

			var button   = $( this );
			var $input   = button.prev();
			var $preview = button.parent().find( '.wpex-media-live-preview img' );
			var $remove  = button.parent().find( '.wpex-media-remove' );

			var image = wp.media( {
					library  : {
						type : 'image'
					},
					multiple: false
			} ).on( 'select', function( e ) {
				var selected = image.state().get( 'selection' ).first();
				var imageID  = selected.toJSON().id;
				var imageURL = selected.toJSON().url;

				if ( $remove.length ) {
					$remove.addClass( 'wpex-show' );
				}

				if ( $preview.length ) {
					$preview.attr( 'src', imageURL );
				} else {
					$preview = button.parent().find('.wpex-media-live-preview' );
					var $imgSize = $preview.data( 'image-size' ) ? $preview.data( 'image-size' ) : 'auto';
					$preview.append( '<img src="'+ imageURL +'" style="height:'+ $imgSize +'px;width:'+ $imgSize +'px;" />' );
				}

				$input.val( imageID ).trigger( 'change' );

			} )
			.open();
		} );

		$( '.wpex-media-remove' ).each( function() {
			var $button   = $( this );
			var $input    = $button.parent().find( '.wpex-media-input' );
			var $inputVal = $input.val();
			var $preview  = $button.parent().find( '.wpex-media-live-preview' );
			if ( $inputVal ) {
				$button.addClass( 'wpex-show' );
			}
			$button.on('click', function() {
				$input.val( '' );
				$preview.find( 'img' ).remove();
				$button.removeClass( 'wpex-show' );
				return false;
			} );
			$input.on( 'keyup change', function() {
				if ( ! $( this ).val() ) {
					$preview.find( 'img' ).remove();
					$button.removeClass( 'wpex-show' );
					return false;
				}
			} );
		} );

	}

	// Custom CSS remember to save.
	function wpexPanelCustomCSS() {

		// Show notice.
		$( '.wpex-custom-css-panel-wrap .form-table' ).click( function() {
			$( '.wpex-remember-to-save' ).show();
		} );

		// Save on link click.
		$( '.wpex-custom-css-panel-wrap .wpex-remember-to-save a' ).click( function( e ) {
			e.preventDefault();
			$( '.wpex-custom-css-panel-wrap form #submit' ).click();
		} );

	}

	// Dashicons Select.
	function wpexDashiconSelect() {
		var $buttons = $( '#wpex-dashicon-select a' );
		$buttons.click( function() {
			var $activeButton = $( '#wpex-dashicon-select a.button-primary' );
			$activeButton.removeClass( 'button-primary' ).addClass( 'button-secondary' );
			$( this ).addClass( 'button-primary' );
			$( this ).parents( '#wpex-dashicon-select' ).next( 'input' ).val( $( this ).data( 'value' ) );
			return false;
		} );
	}

	// Header/Footer ajax links.
	function wpexLayoutBuilderTemplateSelector() {
		var	$select = $( '#wpex-header-builder-select, #wpex-footer-builder-select' );

		if ( ! $select.length ) {
			return;
		}

		var $tableTr   = $( '#wpex-admin-page table tr' );
		var $selectTr  = $select.parents( 'tr' );
		var $editLinks = $( '.wpex-edit-template-links-ajax' );
		var $spinner = $( '.wpex-edit-template-links-spinner' );

		// Check initial val
		if ( $select.val() ) {
			$editLinks.show();
		} else {
			$( '.wpex-create-new-template' ).show();
			$tableTr.not( $selectTr ).hide();
		}

		// Check on change
		$( $select ).change( function () {
			var val = $( this ).val();
			$editLinks.hide();
			if ( val ) {
				$tableTr.show();
				$( '.wpex-create-new-template' ).hide();
				ajaxEditLinks( val );
			} else {
				$tableTr.not( $selectTr ).hide();
				$editLinks.hide();
				$( '.wpex-create-new-template' ).show();
			}
		} );

		function ajaxEditLinks( val ) {
			var data = {
				action      : $editLinks.data( 'action' ),
				nonce       : $editLinks.data( 'nonce' ),
				template_id : val
			};

			$spinner.show();

			$.post( ajaxurl, data, function( response ) {
				if ( response ) {
					$editLinks.html( response );
					$editLinks.show();
				}
				$spinner.hide();
			} );
		}

	}

	// Image sizes panel.
	function wpexImageSizes() {

		var $onFly = $( '#wpex_image_resizing' ), onFlyChecked, $deps;

		if ( ! $onFly.length) {
			return;
		}

		onFlyChecked = $onFly.prop( 'checked' );
		$deps = $( '#wpex_retina,#wpex_lazy_loading,#wpex_woo_support' );

		// Check on change
		$( $onFly ).change( function () {
			var $checked = $( this ).prop( 'checked' );
			if ( $checked ) {
				$deps.each( function() {
					var $this = $( this );
					$this.closest( 'tr' ).show();
				} );
			} else {
				$deps.each( function() {
					var $this = $( this );
					$this.closest( 'tr' ).hide();
				} );
			}
		} ).change();

	}

	function wpexCustomizerManager() {

		var $page = $( '#wpex-customizer-manager-admin-page' );

		if ( ! $page.length) {
			return;
		}

		$( '.wpex-customizer-check-all' ).click( function() {
			$('.wpex-customizer-editor-checkbox').each( function() {
				this.checked = true;
			} );
			return false;
		} );

		$( '.wpex-customizer-uncheck-all' ).click( function() {
			$('.wpex-customizer-editor-checkbox').each( function() {
				this.checked = false;
			} );
			return false;
		} );

	}

	/*
	 * Run functions on doc ready.
	 */
	$( document ).ready( function() {
		wpexPanelEnableDisableModules();
		wpexThemePanelSort();
		wpexPanelInfoToggle();
		wpexChosenSelect();
		wpexColorPicker();
		wpexAdminTabs();
		wpexPanelTabs();
		wpexMediaUpload();
		wpexPanelCustomCSS();
		wpexDashiconSelect();
		wpexLayoutBuilderTemplateSelector();
		wpexImageSizes();
		wpexCustomizerManager();
	} );

} ) ( jQuery );