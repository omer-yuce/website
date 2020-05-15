
( function( $, elementor ) {

	'use strict';

	var widgetPagepiling = function( $scope, $ ) {

		$('#pagepiling').pagepiling({
            navigation: {
                'position': 'right',
            },
            loopTop: 'true',
            loopBottom: 'true'
        });
        
	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/prime-slider-pagepiling.default', widgetPagepiling );
	});

}( jQuery, window.elementorFrontend ) );