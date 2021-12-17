/**
 * Total Theme Customizer controls.
 */
( function( $ ) {

	'use strict';

	if ( ! wp || ! wp.customize ) {
		console.log( 'wp or wp.customize objects not found.' );
		return;
	}

	/**
	 * Custom Selects.
	 */
	var controls = [
		'wpex-dropdown-pages',
		'wpex-font-family',
		'wpex-card-select',
		'wpex-dropdown-templates'
	];

	_.each( controls, function( control ) {

		wp.customize.controlConstructor[control] = wp.customize.Control.extend( {

			ready: function() {

				if ( 'undefined' === typeof $.fn.chosen ) {
					return;
				}

				this.container.find( 'select' ).chosen( {
					width: '100%',
					search_contains: true,
					disable_search_threshold: 5
				} );

			}

		} );

	} );

	 /**
	 * Icon Selects.
	 */
	wp.customize.controlConstructor['wpex-fa-icon-select'] = wp.customize.Control.extend( {

		ready: function() {

			if ( 'undefined' === typeof $.fn.wpexChosenIcon ) {
				return;
			}

			this.container.find( 'select' ).wpexChosenIcon( {
				width: '100%',
				search_contains: true,
				disable_search_threshold: 10
			} );

		}

	} );

	/**
	 * Custom Columns.
	 *
	 */
	wp.customize.controlConstructor['wpex-columns'] = wp.customize.Control.extend( {

		ready: function() {

			var control = this;

			control.container.find( '.wpex-cols-select' ).change( function( e ) {
				control.updateValue();
			} );

			control.container.find( '.wpex-toggle-settings' ).on( 'click', function() {
				control.container.find( '.wpex-customizer-columns-field > li.wpex-extra' ).toggleClass( 'wpex-hidden' );
				if ( 'false' == $( this ).attr( 'aria-expanded' ) ) {
					$( this ).attr( 'aria-expanded', 'true' );
					control.container.find( '.wpex-customizer-columns-field li.wpex-extra:first select' ).focus();
				} else {
					$( this ).attr( 'aria-expanded', 'false' );
				}
				return false;
			} );

		},

		updateValue: function() {

			var control = this,
				newValue = {},
				valCount = 0,
				$hiddenInput = control.container.find( '.wpex-hidden-input' );

			$hiddenInput.trigger( 'change' );

			control.container.find( '.wpex-cols-select' ).each( function( index, el ) {

				var $this = $( this ),
					val = $this.children( 'option:selected' ).val();

				if ( val ) {
					valCount++;
					newValue[$this.attr( 'data-name' )] = val;
				}

			} );

			if ( valCount == 0 ) {
				newValue = '';
			} else if ( valCount == 1 ) {
				newValue = newValue['d'];
			}

			control.setting.set( newValue );

		}

	} );

	/**
	 * Responsive Fields.
	 */
	wp.customize.controlConstructor['wpex-responsive-field'] = wp.customize.Control.extend( {

		ready: function() {

			var control = this;

			control.container.find( '.wpex-crf-input' ).on( 'input', function( e ) {
				control.updateValue();
			} );

		},

		updateValue: function() {

			var control = this,
				newValue = {},
				valCount = 0;

			control.container.find( '.wpex-crf-input' ).each( function( index, el ) {

				var $this = $( this ),
					val = $this.val();

				if ( val ) {
					valCount++;
					newValue[$this.attr( 'data-name' )] = val;
				}

			} );

			if ( valCount == 0 ) {
			   control.setting.set( '' );
			} else {
				control.setting.set( JSON.stringify( newValue ) );
			}

		}

	} );

} ( jQuery ) );