jQuery(function($) {
	$.get('/follow-widget')
	.done(function(response) {
		$('.follow-content').html(response);
	});
});
