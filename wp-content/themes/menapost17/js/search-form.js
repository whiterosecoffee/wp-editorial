jQuery(function($) {

	$( '[data-target="search-open"]' ).on( 'click', function() {

		var target = $( '[data-element="search-form"]' );

		setTimeout( function () {
			$( '.search-form input' ).focus();
		}, 100);
	});

	$( '[data-target="search-close"]' ).on( 'click', function() {
		close( $( '[data-element="search-form"]' ));
	});

	$( '.search-form input' ).focusout(function() {
		close( $( '[data-element="search-form"]' ));
	});

	function close($context) {
		$context.removeClass( 'visible' );
	}
});
