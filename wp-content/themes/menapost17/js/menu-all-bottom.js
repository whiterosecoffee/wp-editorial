jQuery(document).ready(function(){
	if (jQuery('.site-container.article-detail')) {
			jQuery(window).scroll(function() {
				socialStatsVertPositioning();
			});
			jQuery(window).resize(function(){
				socialStatsVertPositioning();
			});
	}
	jQuery(function($) {
		$('#menuAllBottom').appendTo($('li.allContainer>.sub-menu'));
	});
	( function( $ ) {
	   $( 'a[href="#"]' ).click( function(e) {
	      e.preventDefault();
	   } );
	} )( jQuery );

	function socialStatsVertPositioning() {
		if (jQuery(window).scrollTop() >= 530 && jQuery(window).width() > 1200 )  {
			var socialStatsVertical = jQuery('body').offset().left + 1116;
	        socialStatsVertical += 'px'; jQuery('#socialStatsVertical').css('left', socialStatsVertical);
	    }
	    else {
	    	jQuery('#socialStatsVertical').css('left', 'initial');
	    }
	}

});
