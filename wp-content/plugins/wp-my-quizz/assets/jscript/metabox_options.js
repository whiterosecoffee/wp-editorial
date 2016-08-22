jQuery(document).ready(function($){
	my_color_id_new='';
	$(".my_color_picker").focus(function(e){
		my_color_id_new=$(this).attr('id');
	});
	$(".my_color_picker").ColorPicker({
		onChange: function (hsb, hex, rgb) {
			var id=my_color_id_new;
			if(window.console){
				console.log('Id '+id);
			}
			$("#"+id).css('background-color', '#'+hex);
			$("#"+id).val('#'+hex);
			//$("#"+id).val('#'+hex);
			
			//$("#my_color_picker_one").css('backgroundColor', '#' + hex);
			//$("#my_color_picker_one").val('#'+hex);
		}
	});
});
