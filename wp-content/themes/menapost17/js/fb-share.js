jQuery(function($) {
	$('body').on('click', '[data-action=fb-share]', function(event) {
		var articleUrl = $(this).attr('href');
		var popup = window.open('http://facebook.com/share.php?u=' + articleUrl, '_blank', 'innerWidth=600,innerHeight=600,menubar=no,location=no', false);
		popup.focus();

		event.preventDefault();
		event.stopPropagation();
	});
});
