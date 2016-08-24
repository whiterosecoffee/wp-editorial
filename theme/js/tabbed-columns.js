jQuery( function( $ ) {
	'use strict';

	$.fn.tabbedColumns = function() {
		var $panels = $( this ).find( '[data-role=panel]' );
		var $tabs = $( this ).find( '[data-role=tab]' );

		$( this ).find( '[data-role=tab]' ).each( function setupTab() {
			var $myPanel = $( $( this ).attr( 'data-panel' ));

			$( this ).click( function showOnlyMyPanel() {
				$panels.removeClass( 'active' );
				$myPanel.addClass( 'active' );

				$tabs.removeClass( 'active' );
				$( this ).addClass( 'active' );
			});
		});
	};

	$( '[data-role=tabbed-columns]' ).tabbedColumns();
});
