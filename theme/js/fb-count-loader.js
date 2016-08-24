(function postPageLoad() {
	'use strict';

	jQuery(function($) {
		$('[data-load-on=postPageLoad]').each(function() {
			var $this = $(this);
			var route = $this.attr('data-route');

			$.get(route).done(function(response) {
				$this.html(response);
			});
		});
	});
})();
