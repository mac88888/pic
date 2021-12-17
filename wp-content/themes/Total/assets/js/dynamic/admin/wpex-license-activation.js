( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		wpexLicenseActivation();
	} );

	function wpexLicenseActivation() {

		var $licenseForm = $( '.wpex-license-activation__form' );

		$licenseForm.submit( function( e ) {
			e.preventDefault();

			var $form            = $( this );
			var $submit          = $form.find( '.wpex-license-activation__button' );
			var $spinner         = $form.find( '.wpex-license-activation__spinner' );
			var actionProcess    = $submit.hasClass ( 'activate' ) ? 'activate' : 'deactivate';
			var $licenseField    = $form.find( '.wpex-license-activation__input' );
			var $devlicenseField = $form.find( '.wpex-license-activation__input-dev' );

			$( '.wpex-license-activation__notice' ).hide().removeClass( 'notice-warning updated notice-error' );

			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpex_theme_license_form',
					process: actionProcess,
					license: $form.find( '.wpex-license-activation__input' ).val(),
					devlicense: $devlicenseField.is( ':checked' ) ? 'checked' : 0,
					nonce: $form.find( 'input#wpex_theme_license_form_nonce' ).val()
				},
				beforeSend: function() {
					$spinner.addClass( 'wpex-license-activation__spinner--visible' );
					$submit.prop('disabled', true );
				},
				success: function( response ) {
					$spinner.removeClass( 'wpex-license-activation__spinner--visible' );
					$submit.prop('disabled', false );

					//console.log( response );

					if ( response.success ) {

						$devlicenseField.parent().hide();

						if ( 'activate' === actionProcess ) {
							$licenseField.attr( 'readonly', 'readonly' );
							$submit.removeClass( 'activate' ).addClass( 'deactivate' ).val( $submit.data( 'deactivate' ) );
						} else if ( 'deactivate' === actionProcess ) {
							$licenseField.attr( 'placeholder', '' ).removeAttr( 'readonly' );
							$licenseField.val( '' );
							$submit.removeClass( 'deactivate' ).addClass( 'activate' ).val( $submit.data( 'activate' ) );
							if ( response.clearLicense ) {
								var curr_page = window.location.href,
									next_page = '';
								if ( curr_page.indexOf( '?' ) > -1 ) {
									next_page = curr_page + '&license-cleared=1';
								} else {
									next_page = curr_page + '?license-cleared=1';
								}
								window.location = next_page;
							} else {
								location.reload();
							}
						}

					}

					if ( response.message ) {
						$( '.wpex-license-activation__notice' ).addClass( response.messageClass ).html( '<p>' + response.message + '</p>' ).show();
					}
				}

			} );

		} );

	}

} ) ( jQuery );