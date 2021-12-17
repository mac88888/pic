( function() {

	if ( ! wp || ! wp.customize ) {
		console.log( 'wp or wp.customize objects not found.' );
		return;
	}

	wp.customize.bind( 'ready', function() {
		_.each( wpexControlVisibility, function( dependency, controlName ) {
			checkVisibility( controlName, dependency.check, dependency.value );
		} );
	} );

	function checkVisibility( control, targetControl, targetValue ) {

		// Handle changes to the targetControl.
		wp.customize( targetControl, function( targetSetting ) {

			// Check's if the targetControl is enabled.
			var isEnabled = function() {
				var getSetting = targetSetting.get();

				if ( _.isArray( targetValue ) ) {
					return _.contains( targetValue, getSetting );
				} else {
					switch( targetValue ) {
						case 'true':
							return getSetting;
						break;
						case 'false':
							return getSetting ? false : true;
						break;
						default:
							return targetValue == getSetting ? true : false; // use lose comparison to check for 0 == false
					}
				}

			};

			// Update state.
			visibilityCheck = function( control ) {

				var setActiveState = function() {
					if ( isEnabled() ) {
						control.activate();
					} else {
						control.deactivate();
					}
				};

				// Makes sure the control is validated to prevent the need for PHP active_callback.
				control.active.validate = isEnabled;

				// Set initial active state on Customizer load.
				setActiveState();

				// Update activate state whenever the targetControl is changed.
				targetSetting.bind( setActiveState );

			};

			// Show/Hide the control depending on the targetControl value.
			wp.customize.control( control, visibilityCheck );

		} );

	}

}() );