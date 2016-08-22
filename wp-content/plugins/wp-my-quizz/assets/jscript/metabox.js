jQuery(document).ready(function($){
	var my_debug_quizz_flag=false;
	my_debug_quizz=function(t,o){
		if(my_debug_quizz_flag){
			if(window.console){
				console.log(t,o);
			}
		}
	}
	$("#my_color_picker_one").ColorPicker({
		onChange: function (hsb, hex, rgb) {
			$("#my_color_picker_one").css('backgroundColor', '#' + hex);
			$("#my_color_picker_one").val('#'+hex);
		}
	});
	my_call_ajax=function(data,options){
		$.ajax({
			url:my_quizz_admin_ajax,
			dataType:options.dataType,
			async:false,
			data:data,
			cache:false,
			timeout:10000,
			type:'POST',
			success:function(data,status,jq){
				
				
				
				if(typeof options.after_success =='function'){
					options.after_success(data);
				}
			},
			error:function(jq,status){
				alert('Error');
			}
			
		});
	};
	$(".my_refresh_attachments").click(function(e){
		var post_id=$("#post_ID").val();
		var options={
				dataType:'html',					
				after_success:function(data){
					$("#my_post_atts").html(data);
					setTimeout(function(){
						//$("#my_attachments").unbind('change');
						//$("#my_attachments").change(my_change_attachment);
						my_count=0;
						$("#my_post_atts .my_attach").each(function(i,v){
							if($(v).is(':visible')){
								my_count++;
							}
						});
					},500);
					
				}	
		}
		var data={
			action:'wp_my_quizz_action',
			my_action:'get_attachs',
			nonce:my_quizz_admin_nonce,
			post_id:post_id
		}
		my_debug_quizz('Refresh attachments',data);
		my_call_ajax(data,options);
		
	});
	var checked_val=$("input[name='my_is_quiz_post']").is(":checked");
	my_debug_quizz("Checked val",checked_val);
	if(checked_val){
		my_debug_quizz("Slide toogle ");
		$("#my_quiz_data").slideToggle('slow');
	}
	$("input[name='my_is_quiz_post']").change(function(e){
		$("#my_quiz_data").slideToggle('slow');
		/*if($(this).is(":checked")){
			val=1;
		}else val=0;
		var options={
				dataType:'html',					
				/*after_success:function(data){
					alert('Final result item has been deleted');
					$(".my_form_item[my_id='"+id+"']").remove();
				}*/
		//}
		/*var data={
				action:'wp_my_quizz_action',
				my_action:'set_quizz',
				nonce:my_quizz_admin_nonce,
				post_id:post_id,
				val:val
				
			}
		my_debug_quizz('Set quizz metadata',data);
		my_call_ajax(data,options);
		*/
		
		
	});
	/**
	 * Show forms to site user
	 */
	my_header_click=function(e){
		if($(this).parent("div").children(".my_inner").is(":visible")){
			$(this).removeClass('my_header_open');
		}else {
			$(this).addClass('my_header_open');
			
		}
		$(this).parent("div").children(".my_inner").slideToggle('fast');
		
	}
$(".my_header").click(my_header_click);
	my_choose_image_or_color=function(e){
		var $form=$(this).parents('.my_form');
		//var val=$("input[name='my_answer_type_id1234']:checked").val();
		var name=$(this).attr('name');
		var val=$("input[name='"+name+"']:checked").val();
		if(val=='color'){
			$form.find(".my_image_li div").html('');
			$form.find(".my_image_li").slideToggle('slow');
			$form.find(".my_color_li").slideToggle('slow');
		}else {
			$form.find(".my_image_li").slideToggle('slow');
			$form.find(".my_color_li").slideToggle('slow');
		}
	};
	$("input[name='my_answer_type_id1234']").change(my_choose_image_or_color);
	/**
	 * Add final result data
	 */
	/*$("#my_add_final").ajaxForm({
		success:function(data){
			$("#my_final_results .my_inner ").append(data);
			//$("#my_final_results .my_inner ").slideToogle('fast');
			
		}
	});*/
	/**
	 * Get attachment from media
	 */
	my_old_function_send='';
	my_old_function_remove='';
	my_old_function_insert='';
	my_send_attachment_to_editor=function(){
		
		//my_debug_quizz("Attachment",props);
		var att=new_quizz_editor.state().get('selection').first().toJSON();
		//var att=props._single.attributes;
		my_debug_quizz("Attachment",att);
		
		var title=att.title;
		if(title.length>10){
			title=title.substr(0,10)+'...';
		}
		var html='<div id="my_attach_'+att.id+'" class="my_attach" my_id="'+att.id+'">';
		html+='<div class="my_attach_inner">';
		html+='<h4>'+title+'</h4>';
		html+='<img src="'+att.url+'" width="50px" height="50px"/>';
		html+='</div>';
		html+='<div class="my_sel_att">';
		html+='<input type="radio" name="my_attachments_1234" id="my_attachments" value="'+att.id+'"/>';
		html+='</div></div><div class="my_clear"></div>';
		$("#my_media_attachment").html(html);
		my_debug_quizz("Html",html);
		setTimeout(function(){
			$("#my_media_attachment #my_attachments").prop('checked',true);
		},500);
		var top=$("#my_media_attachment").offset().top;
		$("html,body").animate({scrollTop:top},500);
		wp.media.editor.insert=my_old_function_insert;
		wp.media.editor.send.attachment=my_old_function_send;
		//wp.media.editor.remove=my_old_function_remove;
		
	}
	my_count=0;
	$("#my_post_atts .my_attach").each(function(i,v){
		if($(v).is(':visible')){
			my_count++;
		}
	});
	my_show_prev_next_att=function(e){
		e.preventDefault();
		var dir=$(this).attr('my_dir');
		my_debug_quizz("Show dir",dir);
		var my_found=false;
		var my_hidded=false;
		/*var my_count=0;*/
		var my_added=0;
		
		if(dir=='next'){
			$("#my_post_atts .my_attach").each(function(i,v){
				if($(v).is(':visible')){
					//my_count++;
					my_found=true;
				}
				my_debug_quizz("My count",my_count);
				if(my_found==true&&!$(v).is(':visible')){
					if(my_hidded==false){
						$("#my_post_atts .my_attach").filter(':visible').hide();
						my_hidded=true;
					}
					$(v).show();
					my_added++;
					if(my_added==my_count)return false;
				}
			});
			
		}else if(dir=='prev'){
			var my_i=0;
			$("#my_post_atts .my_attach").each(function(i,v){
				if($(v).is(':visible')){
					my_i=i;
					//my_count++;
					my_found=true;
					return false;
				}
			});
			if(my_i>0){
				$("#my_post_atts .my_attach").filter(':visible').hide();
				my_hidded=true;
				var poc=my_i-my_count;
				
				var end=my_i;
				my_debug_quizz("Prev",{poc:poc,end:end});
				if(poc>=0){
					$("#my_post_atts .my_attach").each(function(i,v){
						if(i>=end)return false;
						if(i>=poc)$(v).show();
					});
					}
				}
			}
			
		}
	//}
	$(".my_show_att_next").click(my_show_prev_next_att);
	//wp.media.editor.add("my_new_editor",
	new_quizz_editor=wp.media({		
		title:"Select image for Quizz",
		multiple:false,
		/*
		send:{attachment:my_send_attachment_to_editor},
		on:{insert:my_send_attachment_to_editor}*/
		
	});
	//new_quizz_editor.send.attachment=my_send_attachment_to_editor;
	new_quizz_editor.on('select',my_send_attachment_to_editor);
	my_get_attach_from_media=function(e){
		e.preventDefault();
		var $this=$(this);
		/*var frame=wp.media.editor.add('content');
		frame.on('escape',function(){
			my_debug_quizz("Remove editor");
			wp.media.editor.send.attachment=my_old_function_send;
			wp.media.editor.remove=my_old_function_remove;
		});*/
		my_old_function_send=wp.media.editor.send.attachment;
		my_old_function_insert=wp.media.editor.insert;
		/*
		wp.media.editor.get('my_new_editor').on('insert',my_send_attachment_to_editor);
		//);
		wp.media.editor.get("my_new_editor").on('close',function($this){
			my_debug_quizz("Remove editor");
			wp.media.editor.send.attachment=my_old_function_send;
			//wp.media.editor.remove=my_old_function_remove;
		});*/
		
		/*wp.media.editor.get('my_new_editor').open({
			send:{attachment:my_send_attachment_to_editor}
		});*/
		new_quizz_editor.open();
	}
	$(".my_get_media").click(my_get_attach_from_media);
	/**
	 * Get selected image for an item
	 */
	
	my_get_selected_image=function(e){
		var selected_val=$("#my_attachments:checked").val();
		if(typeof selected_val!="undefined"){
			var html=$("#my_attach_"+selected_val+" .my_attach_inner").html();
			var input_html='<input type="hidden" class="my_selected_attachment" value="'+selected_val+'"/>';
			$(this).parent('li').find('div').html(html+input_html);
		}else {
			alert("Please select attchment image !");
			var top=$("#my_uploaded_items").offset().top-200;
			$("html,body").animate({scrollTop:top},500);
		}
	}
	$(".my_get_selected_image").click(my_get_selected_image);
	my_quizz_delete_question=function(e){
		e.preventDefault();
		if(confirm('All answers will be deleted too')){
		var post_id=$("#post_ID").val();
		var id=$(this).parents('.my_form_item_1').attr('my_id');
		my_debug_quizz('Delete item',id);
		var options={
				dataType:'html',					
				after_success:function(data){
					$(".my_question_answer").each(function(i,v){
						$(v).find('option').each(function(i1,v1){
							var val=$(v1).val();
							if(val==id)$(v1).remove();
						});
					});
					
					alert('Qeestion has been deleted');
					
					$("#my_questions .my_form_item_1[my_id='"+id+"']").remove();
				    
				}
		}
		var data={
				action:'wp_my_quizz_action',
				my_action:'delete_question',
				nonce:my_quizz_admin_nonce,
				post_id:post_id,
				id:id,
				
			}
		my_debug_quizz(' attachments',data);
		my_call_ajax(data,options);
		}
	}
	my_quizz_update_question=function(e){
		var id=$(this).parents(".my_form_item_1").attr('my_id');
		
		var $form=$(this).parents('.my_form');
		var title=$form.find("textarea[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("textarea[name='my_quiz_title']").focus();
			return;
		}
		/*var descr=$form.find("textarea[name='descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='descr']").focus();
			return;
		}*/
		var image=$form.find('.my_selected_attachment').val();
		if(typeof image=='undefined'){
			//image=0;
			alert('Please select the image for a question !');
			return;
		}
		my_debug_quizz("Form data",{title:title,image:image});
		var options={
				dataType:'html',					
				after_success:function(data){
					$("#my_questions .my_inner").append(data);
					alert('You have updated a question!');
					/*setTimeout(function(){
						
						//$("#my_attachments").unbind('change');
						//$("#my_attachments").change(my_change_attachment);
							$(".my_edit_item").unbind('click');
							$(".my_edit_item").click(my_quizz_edit_item);
							$(".my_delete_question").unbind('click');
							$(".my_delete_question").click(my_quizz_delete_question);
							$(".my_update_question").unbind('click');
							$(".my_update_question").click(my_quizz_update_question);
							$(".my_get_selected_image").unbind('click');
							
							$(".my_get_selected_image").click(my_get_selected_image);
								
					},500);*/
					
				}	
		}
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'update_question',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			title:title,
			id:id,
			image:image
		}
		my_debug_quizz('Add question',data);
		my_call_ajax(data,options);
	}
	$(".my_update_question").click(my_quizz_update_question);
	
	$(".my_delete_question").click(my_quizz_delete_question);
	/**
	 * Edit item
	 */
	my_quizz_edit_item=function(e){
		e.preventDefault();
		if($(this).parents(".my_form_item_1").children(".my_inner").is(":visible")){
			//$(this).removeClass('my_header_open');
		}else {
			//$(this).addClass('my_header_open');
			
		}
		$(this).parents(".my_form_item_1").children(".my_inner").slideToggle('fast');
		
		
	}
	my_quizz_delete_final=function(e){
		e.preventDefault();
		var post_id=$("#post_ID").val();
		var id=$(this).parents('.my_form_item_1').attr('my_id');
		my_debug_quizz('Delete item',id);
		var options={
				dataType:'html',					
				after_success:function(data){
					$(".my_final_result_answer").each(function(i,v){
						$(v).find('option').each(function(i1,v1){
							var val=$(v1).val();
							if(val==id)$(v1).remove();
						});
					});
					alert('Final result item has been deleted');
					$("#my_final_results .my_form_item_1[my_id='"+id+"']").remove();
					if(data.length>0){
						my_debug_quizz("Remove answers",data);
						if(data.indexOf(",")!=-1){
							var arr=data.split(",");
							$.each(arr,function(i,v){
								$(".my_form_item_2[my_id='"+v+"']").remove();
							});
						}else {
							$(".my_form_item_2[my_id='"+data+"']").remove();
							
						}
					}
				}
		};
		var data={
				action:'wp_my_quizz_action',
				my_action:'delete_final_result',
				nonce:my_quizz_admin_nonce,
				post_id:post_id,
				id:id,
				
			};
		my_debug_quizz(' attachments',data);
		my_call_ajax(data,options);
		
	};
	my_update_answer=function(e){
		var id=$(this).parents("ul").attr('my_id');
		var $form=$(this).parents('.my_form');
		my_debug_quizz("Form length "+id,$form.length);
		//return;
		var title=$form.find("textarea[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("textarea[name='my_quiz_title']").focus();
			return;
		}
		/*var descr=$form.find("textarea[name='descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='descr']").focus();
			return;
		}*/
		var final_res=$("#my_final_result_answer_1 option:selected").val();
		if(final_res==""){
			alert("Please select final result !");
			return;
		}
		var question=$("#my_question_answer_1 option:selected").val();
		if(question==""){
			alert("Please select question !");
			return;
		}
		var use_color=$form.find("input[name='my_answer_type_id123462']:checked").val();
		if(use_color=='color'){
		var color=$("#my_color_picker_two").val();
		
		//$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		if(color==""){
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select  color for the answer !');
				return;
			}
		}else image=0;
		}else {
			color="";
			var image=$form.find('.my_selected_attachment').val();
			
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select image for the answer !');
				return;
			}else color="";
		}
		
		my_debug_quizz("Form data",{title:title,image:image,color:color,final_res:final_res,question:question});
		var options={
				dataType:'html',					
				after_success:function(data){
					$("#my_check_data").html(data);
					
					alert('You have update the answer');
					/*
					setTimeout(function(){
						var question=$("#my_check_data .my_form_item_2").attr('my_q');
						$(".my_added_answers[my_id='"+question+"']").append(data);
						$(".my_edit_item_anwser").unbind('click');
						$(".my_edit_item_anwser").click(my_edit_item_anwser);
						$(".my_delete_answer").unbind('click');
						$(".my_delete_answer").click(my_delete_answer);
					},500);
					*/
					}	
		};
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'update_answer',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			id:id,
			title:title,
			color:color,
			image:image,
			final_res:final_res,
			question:question
		};
		my_debug_quizz('Update answer',data);
		my_call_ajax(data,options);
		
		
	};
	my_edit_item_anwser=function(e){
		e.preventDefault();
		var id=$(this).parents(".my_form_item_2").attr('my_id');
		my_debug_quizz("Edit answer",id);
		var options={
				dataType:'html',					
				after_success:function(data){
					//$("#my_check_data").html(data);
					$("#my_edit_answer .my_inner").html(data);
					$("#my_edit_answer .my_inner").show();
					var top=$("#my_edit_answer .my_inner").offset().top;
					$("html,body").animate({scrollTop:top},500,function(){
						$("#my_edit_answer textarea[name='my_quiz_title']").focus();
						
					});
					//alert('You have added new answer');
					setTimeout(function(){
						$("#my_edit_answer .my_edit_answer").click(my_update_answer);
						$("#my_edit_answer .my_get_selected_image").click(my_get_selected_image);
						$("#my_color_picker_two").ColorPicker({
							onChange: function (hsb, hex, rgb) {
								$("#my_color_picker_two").css('backgroundColor', '#' + hex);
								$("#my_color_picker_two").val('#'+hex);
							}
						});
						$("input[name='my_answer_type_id123462']").unbind('change');
						$("input[name='my_answer_type_id123462']").change(my_choose_image_or_color);
						
						/*var id=$("#my_check_data .my_form_item_2").attr('my_q');
						$(".my_added_answers[my_id='"+question+"']").append(data);
						$(".my_edit_item_anwser").unbind('click');
						$(".my_edit_item_anwser").click(my_edit_item_anwser);
						$(".my_delete_answer").unbind('click');
						$(".my_delete_answer").click(my_delete_answer);
						*/
					},500);
					}	
		};
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'edit_answer',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			id:id
			/*title:title,
			color:color,
			image:image,
			final_res:final_res,
			question:question*/
		};
		my_debug_quizz('Add answer',data);
		my_call_ajax(data,options);
	};
	my_delete_answer=function(e){
		e.preventDefault();
		var id=$(this).parents(".my_form_item_2").attr('my_id');
		my_debug_quizz("Delete answer",id);
		var options={
				dataType:'html',					
				after_success:function(data){
					//$("#my_check_data").html(data);
					
					alert('You have deleted a answer');
					$(".my_form_item_2[my_id='"+id+"']").remove();
					/*setTimeout(function(){
						var question=$("#my_check_data .my_form_item_2").attr('my_q');
						$(".my_added_answers[my_id='"+question+"']").append(data);
						$(".my_edit_item_anwser").unbind('click');
						$(".my_edit_item_anwser").click(my_edit_item_anwser);
						$(".my_delete_answer").unbind('click');
						$(".my_delete_answer").click(my_delete_answer);
					},500);*/ 
					}
		};
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'delete_answer',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			id:id,
			/*title:title,
			color:color,
			image:image,
			final_res:final_res,
			question:question*/
		};
		my_debug_quizz('Add answer',data);
		my_call_ajax(data,options);
		
	}
	$(".my_edit_item_anwser").click(my_edit_item_anwser);
	$(".my_delete_answer").click(my_delete_answer);
	my_quizz_add_answer_item=function(e){
		var $form=$(this).parents('.my_form');
		my_debug_quizz("Form length",$form.length);
		var title=$form.find("textarea[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("textarea[name='my_quiz_title']").focus();
			return;
		}
		var final_res=$("#my_final_result_answer option:selected").val();
		if(final_res==""){
			alert("Please select final result !");
			return;
		}
		var question=$("#my_question_answer option:selected").val();
		if(question==""){
			alert("Please select question !");
			return;
		}
		//var color=$("#my_color_picker_one").val();
		$form.find("textarea[name='my_quiz_title']").val('');
		//$form.find("textarea[name='title']").val();
		//$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		/*if(color==""){
			var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select image or color for the answer !');
				return;
			}
		}else image=0;*/
		var use_color=$form.find("input[name='my_answer_type_id1234']:checked").val();
		var image;
		image=0;
		if(use_color=='color'){
		var color=$("#my_color_picker_one").val();
	
		//$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		if(color==""){
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select  color for the answer !');
				return;
			}
		}else image=0;
		}else {
			color="";
			image=$form.find('.my_selected_attachment').val();
			
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select image for the answer !');
				return;
			}else color="";
		}
		
		my_debug_quizz("Form data",{title:title,image:image,color:color,final_res:final_res,question:question});
		var pre_html=$("textarea[name='title_1234']").val();
		if(pre_html.length>0){
			pre_html+="\r\n";
		}
		pre_html+=title;
		$("textarea[name='title_1234']").val(pre_html);
		pre_html=$("textarea[name='title_1234_colors']").val();
		if(pre_html.length>0){
			pre_html+="\r\n";
		}
		
		if(use_color=="color")pre_html+=color;
		else pre_html+=image;
		$("textarea[name='title_1234_colors']").val(pre_html);
		pre_html=$("textarea[name='title_1234_final']").val();
		if(pre_html.length>0){
			pre_html+="\r\n";
		}
		pre_html+=final_res;
		$("textarea[name='title_1234_final']").val(pre_html);
	}
	$(".my_add_new_item").click(my_quizz_add_answer_item);
	my_quizz_add_answer=function(){
		if($(".my_add_multiple_1234").is(":checked")){
			var $form=$(this).parents('.my_form');
			var question;
			question=$form.find('.my_question_answer option:selected').val();
			if(typeof question=='undefined'){
				alert("Please select question !");
				return;
			}
			var titles=$form.find("textarea[name='title_1234']").val();
			if(titles==""){
				alert("Please add some items !");
				return;
			}
			var images=$form.find("textarea[name='title_1234_colors']").val();
			var final_ress=$form.find("textarea[name='title_1234_final']").val();
			$form.find("textarea[name='title_1234']").val('');
			$form.find("textarea[name='title_1234_colors']").val('');
			$form.find("textarea[name='title_1234_final']").val('');
			
			my_debug_quizz("Add multiple answers");
			var options={
					dataType:'html',					
					after_success:function(data){
						$("#my_check_data").html(data);
						
						alert('You have added new answers');
						setTimeout(function(){
							var question=$("#my_check_data .my_form_item_2").attr('my_q');
							$(".my_added_answers[my_id='"+question+"']").append(data);
							$(".my_edit_item_anwser").unbind('click');
							$(".my_edit_item_anwser").click(my_edit_item_anwser);
							$(".my_delete_answer").unbind('click');
							$(".my_delete_answer").click(my_delete_answer);
						},500);
						}	
			};
			var post_id=$("#post_ID").val();
			
			var data={
				action:'wp_my_quizz_action',
				my_action:'add_multiple_answer',
				nonce:my_quizz_admin_nonce,
				post_id:post_id,
				//id:id,
				titles:titles,
				/*color:color,*/
				images:images,
				final_ress:final_ress,
				question:question
			};
			my_debug_quizz('Add answers',data);
			my_call_ajax(data,options);
			return;
		}
		//var id=$(this).parents(".my_form_item_1").attr('my_id');
		var $form=$(this).parents('.my_form');
		my_debug_quizz("Form length",$form.length);
		var title=$form.find("textarea[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("textarea[name='my_quiz_title']").focus();
			return;
		}
		/*var descr=$form.find("textarea[name='descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='descr']").focus();
			return;
		}*/
		var final_res=$("#my_final_result_answer option:selected").val();
		if(final_res==""){
			alert("Please select final result !");
			return;
		}
		var question=$("#my_question_answer option:selected").val();
		if(question==""){
			alert("Please select question !");
			return;
		}
		//var color=$("#my_color_picker_one").val();
		$form.find("textarea[name='my_quiz_title']").val('');
		//$form.find("textarea[name='title']").val();
		//$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		/*if(color==""){
			var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select image or color for the answer !');
				return;
			}
		}else image=0;*/
		var use_color=$form.find("input[name='my_answer_type_id1234']:checked").val();
		if(use_color=='color'){
		var color=$("#my_color_picker_one").val();
		
		//$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		if(color==""){
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select  color for the answer !');
				return;
			}
		}else image=0;
		}else {
			color="";
			var image=$form.find('.my_selected_attachment').val();
			
			//var image=$form.find('.my_selected_attachment').val();
			if(typeof image=='undefined'){
			//image=0;
				alert('Please select image for the answer !');
				return;
			}else color="";
		}
		
		my_debug_quizz("Form data",{title:title,image:image,color:color,final_res:final_res,question:question});
		var options={
				dataType:'html',					
				after_success:function(data){
					$("#my_check_data").html(data);
					
					alert('You have added new answer');
					setTimeout(function(){
						var question=$("#my_check_data .my_form_item_2").attr('my_q');
						$(".my_added_answers[my_id='"+question+"']").append(data);
						$(".my_edit_item_anwser").unbind('click');
						$(".my_edit_item_anwser").click(my_edit_item_anwser);
						$(".my_delete_answer").unbind('click');
						$(".my_delete_answer").click(my_delete_answer);
					},500);
					}	
		};
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'add_answer',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			//id:id,
			title:title,
			color:color,
			image:image,
			final_res:final_res,
			question:question
		};
		my_debug_quizz('Add answer',data);
		my_call_ajax(data,options);
		
		
	};
	my_show_answers=function(e){
		e.preventDefault();
		$(this).next('.my_added_answers').slideToggle('slow');
	};
	$(".my_show_answers").click(my_show_answers);
	$(".my_add_answer").click(my_quizz_add_answer);
	my_quizz_update_final=function(){
		var id=$(this).parents(".my_form_item_1").attr('my_id');
		var $form=$(this).parents('.my_form');
		my_debug_quizz("Form length",$form.length);
		var title=$form.find("input[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("input[name='my_quiz_title']").focus();
			return;
		}
		var descr=$form.find("textarea[name='my_quiz_descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='my_quiz_descr']").focus();
			return;
		}
		$(this).parents(".my_form_item_1").find('.my_header_1 span').text(title);
		var image=$form.find('.my_selected_attachment').val();
		if(typeof image=='undefined'){
			image=0;
		}
		my_debug_quizz("Form data",{title:title,descr:descr,image:image});
		var options={
				dataType:'html',					
				after_success:function(data){
					alert('You have update final result');
					
				}	
		}
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'update_final_result',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			id:id,
			title:title,
			descr:descr,
			image:image
		}
		my_debug_quizz('Update final result',data);
		my_call_ajax(data,options);
		
	}
	$(".my_edit_item").unbind('click');
	$(".my_edit_item").click(my_quizz_edit_item);
	$(".my_delete_final_result").unbind('click');
	$(".my_delete_final_result").click(my_quizz_delete_final);
	$(".my_update_final_result").unbind('click');
	$(".my_update_final_result").click(my_quizz_update_final);
	/**
	 * Add new question
	 */
	my_add_question=function(e){
		var $form=$(this).parents('.my_form');
		var title=$form.find("textarea[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("textarea[name='my_quiz_title']").focus();
			return;
		}
		/*var descr=$form.find("textarea[name='descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='descr']").focus();
			return;
		}*/
		var image=$form.find('.my_selected_attachment').val();
		if(typeof image=='undefined'){
			//image=0;
			alert('Please select the image for a question !');
			return;
		}
		$form.find("textarea[name='my_quiz_title']").val('');
		//$form.find("textarea[name='descr']").val('');
		
		my_debug_quizz("Form data",{title:title,image:image});
		var options={
				dataType:'html',					
				after_success:function(data){
					$("#my_check_data").html(data);
					setTimeout(function(){
							var id=$("#my_check_data .my_form_item_1").attr('my_id');
							var title_1=$("#my_check_data .my_header_1 span").text();
							if(title_1.length>30){
								title_1=title_1.substr(0,30)+'...';
							}
							setTimeout(function(){
								$("#my_add_answer .my_question_answer option[value='"+id+"']").prop('selected',true);
							},500);
							
							$("#my_add_answer .my_add_multiple_12345").show();
							$(".my_question_answer").append('<option value="'+id+'">'+title_1+'</option>');
							if(!$("#my_add_answer .my_inner").is(':visible')){
								$("#my_add_answer .my_inner").slideToggle('slow',function(){
									$("#my_add_answer .my_add_multiple_1234").prop('checked',true);
									var top=$("#my_add_answer").offset().top;
									$("html,body").animate({scrollTop:top},500,function(){
										
									});
								});
							}else {
								$("#my_add_answer .my_add_multiple_1234").prop('checked',true);
								var top=$("#my_add_answer").offset().top;
								$("html,body").animate({scrollTop:top},500,function(){
									
								});
							}
						},500);
					$("#my_questions .my_inner").append(data);
					alert('You have added new question!');
					setTimeout(function(){
						
						//$("#my_attachments").unbind('change');
						//$("#my_attachments").change(my_change_attachment);
							$(".my_edit_item").unbind('click');
							$(".my_edit_item").click(my_quizz_edit_item);
							$(".my_delete_question").unbind('click');
							$(".my_delete_question").click(my_quizz_delete_question);
							$(".my_update_question").unbind('click');
							$(".my_update_question").click(my_quizz_update_question);
							$(".my_get_selected_image").unbind('click');
							$(".my_show_answers").unbind('click');
							$(".my_show_answers").click(my_show_answers);
							$(".my_get_selected_image").click(my_get_selected_image);
								
					},500);
					
				}	
		}
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'add_question',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			title:title,
			
			image:image
		}
		my_debug_quizz('Add question',data);
		my_call_ajax(data,options);
	}
	$(".my_add_question").click(my_add_question);
	my_add_multiple_answers=function(e){
		if($(".my_add_multiple_1234").is(":checked")){
			$(".my_add_multiple_12345").slideToggle('slow');
		}else {
			$(".my_add_multiple_12345").slideToggle('slow');
		}
		
	};
	$(".my_add_multiple_1234").change(my_add_multiple_answers);
	/**
	 * Save new result to db
	 */
	my_add_final_result=function(e){
		var $form=$(this).parents('.my_form');
		var title=$form.find("input[name='my_quiz_title']").val();
		if(title==""){
			alert("Please add the title !");
			$form.find("input[name='my_quiz_title']").focus();
			return;
		}
		var descr=$form.find("textarea[name='my_quiz_descr']").val();
		if(descr==""){
			alert("Please add the description !");
			$form.find("textarea[name='my_quiz_descr']").focus();
			return;
		}
		var image=$form.find('.my_selected_attachment').val();
		if(typeof image=='undefined'){
			image=0;
		}
		$form.find("input[name='my_quiz_title']").val('');
		$form.find("textarea[name='my_quiz_descr']").val('');
		my_debug_quizz("Form data",{title:title,descr:descr,image:image});
		var options={
				dataType:'html',					
				after_success:function(data){
					
					
					$("#my_check_data").html(data);
					setTimeout(function(){
							var id=$("#my_check_data .my_form_item_1").attr('my_id');
							var id_1=$("#my_check_data .my_form_item_1").attr('my_id_1');
							
							var title_1=$("#my_check_data .my_header_1 span").text();
							my_debug_quizz("Title",title_1);
							if(title_1.length>30){
								title_1=title_1.substr(0,30)+'...';
							}
							//title_1=id_1+" "+title_1;
							$(".my_final_result_answer").append('<option value="'+id+'">'+title_1+'</option>');
							
							var options_1={
									dataType:'html',					
									after_success:function(data){
										$("#my_added_final_results_front .my_inner").append(data);
										setTimeout(function(){
											if($("body .my_test_final_html").length==0){
												$("body").append('<div style="display:none" class="my_test_final_html"></div>');
											}
											$("body .my_test_final_html").html(data);
											var new_html=$("body .my_test_final_html .my_inner").html();
											$("#my_last_final_result").html(new_html);
											var top=$("#my_last_final_result").offset().top-100;
											$("html,body").animate({scrollTop:top},500);
											$(".my_header").unbind('click');
											$(".my_header").click(my_header_click);
											
										},500);
										}
							};
							var post_id=$("#post_ID").val();
							var data_1={
									action:'wp_my_quizz_action',
									my_action:'get_final_html',
									id:id,
									post_id:post_id,
									nonce:my_quizz_admin_nonce,
							};
							my_call_ajax(data_1,options_1);
						},500);
				
					$("#my_final_results .my_inner").append(data);
					alert('You have added final result');
					//$(".my_final_result_answer").append('<option value=')
					setTimeout(function(){
						
						//$("#my_attachments").unbind('change');
						//$("#my_attachments").change(my_change_attachment);
							$(".my_edit_item").unbind('click');
							$(".my_edit_item").click(my_quizz_edit_item);
							$(".my_delete_final_result").unbind('click');
							$(".my_delete_final_result").click(my_quizz_delete_final);
							$(".my_update_final_result").unbind('click');
							$(".my_update_final_result").click(my_quizz_update_final);
							$(".my_get_selected_image").unbind('click');
							
							$(".my_get_selected_image").click(my_get_selected_image);
								
					},500);
					
				}	
		}
		var post_id=$("#post_ID").val();
		
		var data={
			action:'wp_my_quizz_action',
			my_action:'add_final_result',
			nonce:my_quizz_admin_nonce,
			post_id:post_id,
			title:title,
			descr:descr,
			image:image
		}
		my_debug_quizz('Add final result',data);
		my_call_ajax(data,options);
	}
	$(".my_add_final_result").click(my_add_final_result);
	/**
	 * Show selected attachment
	 */
	my_change_attachment=function(e){
		var selected_val=$("#my_attachments option:selected").val();
		my_debug_quizz("Selected attach",selected_val);
		$(".my_attach").filter(':visible').slideToggle('slow');
		if(selected_val!=""){
			$("#my_attach_"+selected_val).slideToggle('slow');
		}
		
	};
	//$("#my_attachments").change(my_change_attachment);
});