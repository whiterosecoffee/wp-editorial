(function($) { 
	myQuizMetabox=function(o){
		var self;
		self=this;
		this.debug=false;
		this.options=o;
		this.my_working=false;
		this.window_msg='<div id="my_loader_div" class="my_info_window"><div class="my_loader_img"><span class="my_msg">{msg}</span></div></div>';
		this.error_msg='<div id="my_loader_div" class="my_info_window my_error_window "><div class="my_error_img my_error_msg_div"><div><span class="my_msg">{msg}</span></div></div></div>';
		this.success_msg='<div id="my_loader_div" class="my_info_window my_ok_window "><div class="my_ok_img my_ok_msg_div"><div><span class="my_msg">{msg}</span></div></div></div>';
		this.viewport={};	
		this.no_results=true;
		this.no_questions=true;
		self.order=false;
		this.results_images={};
		this.answers_images={};
		this.questions_images={};
		this.post_id='';
		
		this.init=function(){
			self.my_debug("Options",self.options);
			self.step=1;
			self.assing_next_back();
			$(window).resize(self.get_window_size);
			self.get_window_size();
			$(".my_window").each(function(i,v){
				var m;
				if(i==0)m=0;
				else if(i==1)m=self.width;
				else if(i==2)m=2*self.width;
				//$(v).css('margin-left',m+'px');
				$(v).css('margin-right',m+'px');
				
			});
			
			self.new_quizz_editor=wp.media({		
				title:self.options.msgs.media_title,
				multiple:false});
			self.new_quizz_editor.on('select',self.my_send_attachment_to_editor);
			
		};
		this.assing_tooltip=function(){
			$(".my_tooltip").tooltip({
				items:"input",
				content:function(){
					var i=$(this).attr('my_id');
					var id=$(this).attr('my_tooltip');
					self.my_debug("Show tooltip",{i:i,id:id});
					return $("#"+id+" .my_tooltip_content[my_id='"+i+"']").html();
				}
			});
		};
		this.my_send_attachment_to_editor=function(){
			var type=self.media_type;
			var id=self.media_id;
			var tooltip=self.media_tooltip;
			var att=self.new_quizz_editor.state().get('selection').first().toJSON();
			//var att=props._single.attributes;
			self.my_debug("Attachment",att);
			self.my_debug("Object",{type:type,id:id,tooltip:tooltip});
			var title=att.title;
			var html='';
			
			html='';//'<h4>'+title+'</h4>';
			html+='<img src="'+att.sizes.thumbnail.url+'" width="150px" height="150px"/>';
			$("#"+tooltip+" .my_tooltip_content[my_id='"+id+"']").html(html);
			var res=type+id;
			self.my_debug("Res"+res);
			$("input[name='"+res+"']").val(att.id);
			if(type=='res_image_id_'){
				$("input[name='res_image_"+id+"']").val(att.title);
			}
			var size='small';
			var my_type_arr;
			
			if(type=='q_image_id_'){
				size='big';
				my_type_arr='q';
			}else if(type=='res_image_id_'){
				my_type_arr='r';
			}else {
				my_type_arr='a';
			}
			my_image_id=id;
			
			var data={
					action:self.options.ajax_action,
					nonce:self.options.ajax_nonce,
					my_action:'get_image_size',
					size:size,
					id:att.id
			};
			$.ajax({
				url:self.options.ajax_url,
				dataType:'json',
				async:false,
				data:data,
				cache:false,
				timeout:self.options.ajax_timeout,
				type:'POST',
				success:function(data,status,jq){
				//this.results_images={};
				//this.answers_images={};
				//this.questions_images={};
				self.my_debug("Get response ",{data:data,my_type_arr:my_type_arr,my_image_id:my_image_id});
					if(my_type_arr=='q'){
						self.questions_images[my_image_id]=data.url;
						self.my_debug('Add question image',{data:data,images:self.questions_images});
					}else if(my_type_arr=='a'){
						self.answers_images[my_image_id]=data.url;
						self.my_debug('Add answer image',{data:data,images:self.answers_images});
						
					}else {
						self.results_images[my_image_id]=data.url;
						self.my_debug('Add result image',{data:data,images:self.results_images});
					}
				}
				,eror:function(){
			
				}
			
			});
			
		};
		this.check_save=function(){
			var format=self.answ_format;
			var num_q=self.num_questions;
			var num_a=self.num_results;
			self.my_debug("Check form",{num_q:num_q,num_a:num_a,format:format});
			var error=true;
			for(var i=1;i<=num_q;i++){
				var title=$("input[name='q_title_"+i+"']").val();
				self.my_debug("Check question title ",{i:i,title:title})
				if(title==""){
					self.my_debug("Error question "+i);
					alert(self.options.msgs.step_2_error);
					var top=$("input[name='q_title_"+i+"']").position().top;
					$("html").animate({scrollTop:top},500);
					$("input[name='q_title_"+i+"']").focus();
					error=false;
					break;
				}
				var image=$("input[name='q_image_id_"+i+"']").val();
				self.my_debug("Check question image ",{i:i,image:image})
				
				if(image==""){
					self.my_debug("Error image question "+i);
					
					var msg=self.options.msgs.step_3_no_image;
					msg=msg.replace('{1}','Question '+i+' ');
					alert(msg);
					var top=$("input[name='q_title_"+i+"']").position().top;
					$("html").animate({scrollTop:top},500);
					
					//$("input[name='q_title_"+i+"']").focus();
					error=false;
					break;
				}
				if(error){
				/**
				 * Check answers
				 */
				for(var j=1;j<=num_a;j++){
					var name='a_'+i+'_'+j;
					var title=$("input[name='"+name+"']").val();
					self.my_debug("Check answer ",{i:i,j:j,title:title});
					if(title==""){
						self.my_debug("Error answer title "+i+" "+j);
						alert(self.options.msgs.step_2_error);
						var top=$("input[name='"+name+"']").position().top;
						$("html").animate({scrollTop:top},500);
						$("input[name='"+name+"']").focus();
						error=false;
						break;
					}
					if(format==1){
						self.my_debug("Check answer image "+format);
						var name='a_image_id_'+i+'_'+j;
						var val=$("input[name='"+name+"']").val();
						if(val==""){
							self.my_debug("Error image answer");
							var msg=self.options.msgs.step_3_no_image;
							msg=msg.replace('{1}','Answer '+j+' '+' from question '+i);
							alert(msg);
							var top=$(".my_answer[my_a='"+j+"'][my_q='"+i+"']").position().top;
							$("html").animate({scrollTop:top},500);
							error=false;
							break;
							//$("input[name='q_title_"+i+"']").focus();	
						}
					}
				}
				if(!error)break;
				}
			}
			
			return error;
		};
		this.save_post=function(){
			
			if(self.my_working){
				return;
			}
			self.my_working=true;
			var ret=self.check_save();
			self.my_debug("Save post",ret);
			if(!ret){
				self.my_working=false;
			}
			if(ret){
				self.my_show_working_window(self.options.msgs.saving_quiz);
				
			var form_1=$("#my_step_1").serialize();
			var form_2=$("#my_step_2").serialize();
			var form_3=$("#my_step_3").serialize();
			var form_data='';
			form_data=form_1+'&'+form_2+'&'+form_3+'&my_is_quiz_post=1';
			self.my_debug("Form data",form_data);
			var data={
					action:self.options.ajax_action,
					nonce:self.options.ajax_nonce,
					my_action:'save_post',
					my_is_quiz_post:1,
					post_id:self.post_id,
					data:form_data
			};
			$.ajax({
				url:self.options.ajax_url,
				dataType:'json',
				async:false,
				data:data,
				cache:false,
				timeout:self.options.ajax_timeout,
				type:'POST',
				success:function(data,status,jq){
				//this.results_images={};
				//this.answers_images={};
				//this.questions_images={};
					self.my_debug("Returned arr",data);
					self.my_remove_window();
					self.my_show_success_window(data.msg);
					self.post_id=data.post_id;
					setTimeout(function(){
						self.my_remove_window();
						self.my_working=false;
					},self.options.msg_window_timeout);
				}
				,eror:function(){
					self.my_remove_window();
					self.my_working=false;
				}
			
			});
			}
		}
		this.get_window_size=function(e){
			//return;
			self.width=$(".my_windows_1").width();
			self.my_debug("Window size"+self.width);
			$(".my_window").width(self.width);
			$(".my_window").each(function(i,v){
				var m;
				if(i==0)m=0;
				else if(i==1)m=self.width;
				else if(i==2)m=2*self.width;
				if(self.step==2){
					m-=self.width;
				};
				if(self.step==3){
					if(i==0){
						m-=2*self.width;
					}
					else if(i==1){
						m-=2*self.width;
					}else if(i==2){
						m=0;}
				}
				$(v).css('margin-right',m+'px');
				
				$(v).width(self.width);
			});
		};
		this.assing_next_back=function(){
			var $win=$(".my_window[my_id='"+self.step+"']");
			
			$win.find('.my_next').unbind('click');
			$win.find('.my_next').click(self.my_next);
			$win.find('.my_prev').unbind('click');
			$win.find('.my_prev').click(self.my_back);
		};
		this.go_to_step_2=function(){
			var title=$("input[name='quiz_title']").val();
			if(title==""){
				$("input[name='quiz_title']").focus();
				
				return false;
			}
			self.num_results=parseInt($("select[name='my_num_results'] option:selected").val());
			self.my_debug("Num results"+self.num_results);
			self.num_questions=parseInt($("select[name='my_num_question'] option:selected").val());
			self.my_debug("Num questions"+self.num_questions);
			self.answ_format=parseInt($("input[name='my_answers_format']:checked").val());
			self.my_debug("Answer format"+self.answ_format);
			
			
			return true;
		};
		this.go_to_step_3=function(){
			//return true;
			var title_key='res_title_';
			var image_key_1='res_image_';
			var image_key='res_image_id_';
			var descr_key='res_descr_';
			var can=true;
			for(var i=1;i<=self.num_results;i++){
				var t_k=title_key+i;
				var title=$(".my_window[my_id='2'] input[name='"+t_k+"']").val();
				if(title==""){
					var top=$(".my_window[my_id='2'] input[name='"+t_k+"']").position().top;
					$("html").scrollTop(top);
					$(".my_window[my_id='2'] input[name='"+t_k+"']").focus();
					alert(self.options.msgs.step_2_error);
					can=false;
					break;
				}
				var t_k=image_key+i;
				var title=$(".my_window[my_id='2'] input[name='"+t_k+"']").val();
				if(title==""){
					var top=$(".my_window[my_id='2'] input[name='res_image_"+i+"']").position().top;
					$("html").scrollTop(top);
					$(".my_window[my_id='2'] input[name='res_image_"+i+"']").focus();
					alert(self.options.msgs.step_2_error);
					can=false;
					break;
				}
				var t_k=descr_key+i;
				var title=$(".my_window[my_id='2'] textarea[name='"+t_k+"']").val();
				if(title==""){
					var top=$(".my_window[my_id='2'] textarea[name='"+t_k+"']").position().top;
					$("html").scrollTop(top);
					$(".my_window[my_id='2'] textarea[name='"+t_k+"']").focus();
					alert(self.options.msgs.step_2_error);
					can=false;
					break;
				}
				
			}
			return can;
				
		};
		this.my_next=function(e){
			if(self.my_working)return;
			if(self.step>1){
				/*if(typeof $(".my_tooltip").tooltip("close")!="undefined"){
					$(".my_tooltip").tooltip("close");
				}*/
				/*if(self.step>2){
					$(".my_tooltip_span").tooltip("close");
				}*/
			}
			var $win=$(".my_window[my_id='"+self.step+"']");
			//var margin_left=$(".my_window[my_id='1']").css('margin-left');
			//changed to margin right
			var margin_left=$(".my_window[my_id='1']").css('margin-right');
			
			if(typeof margin_left=='undefined')margin_left=0;
			else margin_left=parseFloat(margin_left);
			margin_left-=self.width;
			self.my_working=true;
			var can;
			can=true;
			if(self.step==1){
				can=self.go_to_step_2();
			}
			if(self.step==2){
				can=self.go_to_step_3();
			}
			if(!can){
				if(self.step==1)alert(self.options.msgs.empty_title);
				
				self.my_working=false;
				return;
			}
			
			$(".my_window[my_id='"+self.step+"'] .my_step").position().top;
			self.step++;
			if(self.step==2)self.initialize_step_2();
			else if(self.step==3){
				self.initialize_step_3();
				}
			$("html").animate({scrollTop:top},500,function(){
				var new_step=self.step+1;
				//$(".my_window[my_id='"+new_step+"']").prev(".my_window").animate({'margin-left':"-="+self.width},1000,function(){
				$(".my_window").animate({"margin-right":"-="+self.width},1000,function(){
				
				self.my_working=false;
				self.assing_next_back();
				self.assing_tooltip();
				self.my_debug("Step",self.step);
				/*if(typeof $(".my_tooltip").tooltip("close")!="undefined"){
					$(".my_tooltip").tooltip("close");
				}*/
				
				
			});});
			
		};
		this.initialize_step_3=function(){
			this.assing_tooltip();
			var title=$("input[name='quiz_title']").val();
			$("#my_quizz_title_3").html(title);
			var num=self.num_questions;
			var format=self.answ_format;
			var q_html=$("#my_html_pattern_1 .my_question_template").html();
			var a_html=$(".my_answer_template .my_answers_div").html();
			var count_niz;
			count_niz=3;
			var window_width=$("body").width();
			if(window_width<1280){
				count_niz=2;
			}else if(window_width<700){
				count_niz=1;
			}
			self.my_debug("Q",{num:num,count_niz:count_niz,res:self.num_results});
			if(self.no_questions){
				for(var i=1;i<=num;i++){
					var niz=['','',''];
					var q_html_tmp=q_html;
					q_html_tmp=q_html_tmp.replace(/{id_q}/g,i);
					$("#my_questions").append(q_html_tmp);
					for(var j=1;j<=self.num_results;j++){
						var a_html_tmp=a_html;
						a_html_tmp=a_html_tmp.replace(/{id_a}/g,j);
						a_html_tmp=a_html_tmp.replace(/{id_q}/g,i);
						var c_index=i-1;//j-1;
						var color_hex='white';
						var color_name='';
						if(typeof self.options.colors[c_index]!='undefined'){
							color_hex=self.options.colors[c_index];
							color_name=self.options.colors[c_index];
						}else {
							color_name='';
							
						}
						a_html_tmp=a_html_tmp.replace(/{color_hex}/g,color_hex);
						a_html_tmp=a_html_tmp.replace(/{color_name}/g,color_name);
						if(count_niz>1){
							var a=(j-1)%count_niz;
							self.my_debug("Choose arr",{i:i,j:j,a:a});
							niz[a]+=a_html_tmp;
						}else niz[0]+=a_html_tmp;
						
						
					}
					
					var a_html_total='<div class="my_answers_div" my_q="'+i+'">';
					if(count_niz>1){
					//$.each(niz,function(i1,v1){
					for(var k=0;k<count_niz;k++){
						var v1=niz[k];
						if(v1.length>0){
							a_html_total+='<div class="my_row_33" style="">'+v1+"</div>";
							
						}
						
					};}else a_html_total+='<div class="my_row_33" style="width:360px">'+niz[0]+"</div>";
					
					a_html_total+="</div><div class=\"my_clear\"></div>";
					$("#my_questions").append(a_html_total);
				}	
				
			}else {
				self.my_debug("Change view",{num_results:self.num_results,num_questions:self.num_questions,bac_res:self.backup_num_results_1,back_quest:self.backup_num_questions});
				/**
				 * Remove questions
				 */
				if(self.num_questions<self.backup_num_questions){
					self.my_debug("Remove some questions");
					$(".my_step_3_question").each(function(i,v){
						var id=$(v).attr('my_id');
						if(id>self.num_questions){
							self.my_debug("Remove question id",id);	
							$(v).remove();
							$(".my_answers_div[my_q='"+id+"']").remove();
							/**
							 * Set bare tooltip
							 */
							
							$("#my_q_images_res .my_tooltip_content[my_id='"+id+"']").html($(".my_bare_tooltip").html());
						}
					});
				}
				/**
				 * Remove answers
				 */
				if(self.num_results<self.backup_num_results_1){
					self.my_debug("Remove some answers");
					$(".my_answers_div .my_answer").each(function(i,v){
						var id=$(v).attr('my_a');
						if(id>self.num_results){
							self.my_debug('Remove Answer',id);
							$(v).remove();
						}
					});
					//Remove tooltip
					var bare_tooltip=$(".my_bare_tooltip").html();
					var start=self.num_results+1;
					self.my_debug("Remove tooltip",{start:start,bare:bare_tooltip});
					for(var i=1;i<=self.options.max_q;i++){
						for(var j=1;j<=self.options.max_res;j++){
							var exists_a_12;
							exists_a_12=$("input[name='a_"+i+'_'+j+"']").length;
							self.my_debug("Check answer",{i:i,j:j,exists:exists_a_12});
							
							if(exists_a_12==0){
								self.my_debug("Remove add bare tooltip",{i:i,j:j});
									
								$("#my_a_images_res .my_tooltip_content[my_id='"+i+"_"+j+"']").html(bare_tooltip);
							}
							
						}
					}
				}
				if((self.num_questions>self.backup_num_questions) || (self.num_results>self.backup_num_results_1)){
					self.my_debug("Adding new questions answers");
					for(var i=1;i<=num;i++){
						var niz=['','',''];
						var exists_q;
						exists_q=$(".my_step_3_question[my_id='"+i+"']").length;
						self.my_debug("Exixst question",{i:i,exist_q:exists_q});
						if(exists_q==0){
							var q_html_tmp=q_html;
							q_html_tmp=q_html_tmp.replace(/{id_q}/g,i);
							$("#my_questions").append(q_html_tmp);
						}
						if(exists_q==0 || self.num_results>self.backup_num_results_1){
						for(var j=1;j<=self.num_results;j++){
							var exists_a=$(".my_answer[my_a='"+j+"'][my_q='"+i+"']").length;
							self.my_debug("Exists answer",{j:j,exists_a:exists_a});
							if(exists_a==0){
							var a_html_tmp=a_html;
							a_html_tmp=a_html_tmp.replace(/{id_a}/g,j);
							a_html_tmp=a_html_tmp.replace(/{id_q}/g,i);
							var c_index=i-1;
							var color_hex='white';
							var color_name='';
							if(typeof self.options.colors[c_index]!='undefined'){
								color_hex=self.options.colors[c_index];
								color_name=self.options.colors[c_index];
							}else {
								color_name='';
								
							}
							a_html_tmp=a_html_tmp.replace(/{color_hex}/g,color_hex);
							a_html_tmp=a_html_tmp.replace(/{color_name}/g,color_name);
							if(count_niz>1){
								var a=(j-1)%count_niz;
								self.my_debug("Choose arr",{i:i,j:j,a:a});
								niz[a]+=a_html_tmp;
							}else niz[0]+=a_html_tmp;
							}
							
						}
						}
						if(exists_q==0){
						self.my_debug("Question dont't exists add it");	
						var a_html_total='<div class="my_answers_div" my_q="'+i+'">';
						if(count_niz>1){
						//$.each(niz,function(i1,v1){
						for(var k=0;k<count_niz;k++){
							var v1=niz[k];
							if(v1.length>0){
								a_html_total+='<div class="my_row_33" style="">'+v1+"</div>";
								
							}
							
						};}else a_html_total+='<div class="my_row_33" style="width:360px">'+niz[0]+"</div>";
						
						a_html_total+="</div><div class=\"my_clear\"></div>";
						$("#my_questions").append(a_html_total);
						}else {
							self.my_debug("Add more answers",niz);
							for(var k=0;k<count_niz;k++){
								//var c_row=k+1;
								var v1=niz[k];
								if(v1.length>0){
									var exists_row=$("#my_questions .my_answers_div[my_q='"+i+"'] .my_row_33:eq("+k+")").length;
									if(exists_row==0){
										self.my_debug("Row don't exists",exists_row);
										a_html_total_1='<div class="my_row_33" style="">'+v1+"</div>";
										$("#my_questions .my_answers_div[my_q='"+i+"']").append(a_html_total_1);
									}
									else $("#my_questions .my_answers_div[my_q='"+i+"'] .my_row_33:eq("+k+")").append(v1);
								}
							}
						
						}
						}
						
						
				}
				
			}
			self.backup_num_results_1=self.num_results;
			self.backup_num_questions=self.num_questions;
			self.backup_format=format;
			self.no_questions=false;
			if(format==2){
				$("#my_questions .my_add_image_div").hide();
				$("#my_questions .my_color").show();
			}else {
				$("#my_questions .my_color").hide();
				$("#my_questions .my_add_image_div").show();
				
			}
			/**
			 * Add outcomes 
			 */
			for(var i=1;i<=self.num_results;i++){
				var my_id_1234="input[name='res_title_"+i+"']";
				var val=$("#my_step_2 "+my_id_1234).val();
				if(val.length>12){
					val=val.substr(0,10)+"...";
				}
				for(var j=1;j<=self.num_questions;j++){
					var new_id_1234=".my_outcome_span[my_id='"+j+"_"+i+"']";
					$("#my_step_3 "+new_id_1234).text("( "+val+" )");
				}
				
			}
			$(".my_window[my_id='3'] .my_save_quiz").unbind('click');
			$(".my_window[my_id='3'] .my_save_quiz").click(self.save_post);
			
			$(".my_window[my_id='3'] .my_preview").unbind('click');
			$(".my_window[my_id='3'] .my_preview").click(self.my_preview);
			$(".my_window[my_id='3'] .my_add_image").unbind('click');
			$(".my_window[my_id='3'] .my_add_image").click(self.add_image_2);
			
			self.assing_tooltip();
			$(".my_tooltip_span").tooltip({
				items:"span",
				content:function(){
					var i=$(this).attr('my_id');
					var n=i.split("_");
					var id_q=n[0];
					var id_a=n[1];
					var q_title=$("input[name='quiz_title']").val();
					var image=$("#my_images_res .my_tooltip_content[my_id='"+id_a+"']").html();
					var title=$("input[name='res_title_"+id_a+"']").val();
					var descr=$("textarea[name='res_descr_"+id_a+"']").val();
					var html_tmp=$("#my_front_outcome").html();
					html_tmp=html_tmp.replace('{title}',title);
					html_tmp=html_tmp.replace('{descr}',descr);
					html_tmp=html_tmp.replace('{image}',image);
					html_tmp=html_tmp.replace('{quizz_title}',q_title);
					//var id=$(this).attr('my_tooltip');
					self.my_debug("Show tooltip",{i:i,id_a:id_a,id_q:id_q});
					return html_tmp;//$("#"+id+" .my_tooltip_content[my_id='"+i+"']").html();
				}
			});
			
			
		
		};
		this.my_preview=function(){
			if(self.my_working){
				return;
			}
			self.my_working=true;
			
			self.my_show_working_window(self.options.msgs.my_generate_preview);
			var html='';
			var format=self.answ_format;
			var html_question=$("#my_preview_question_html").html();
			var html_answer='';
			if(format==2){
				html_answer=$("#my_preview_answer_html .my_color_html").html();
			}else {
				html_answer=$("#my_preview_answer_html .my_image_html").html();
				
			}
			var num_q=self.num_questions;
			for(var i=1;i<=num_q;i++){
				var title=$("input[name='q_title_"+i+"']").val();
				if(typeof title=='undefined')title='Not set';
				var html_q_tmp=html_question;
				var image=self.questions_images[i];
				html_q_tmp=html_q_tmp.replace(/{id_q}/g,i);
				html_q_tmp=html_q_tmp.replace(/{title}/g,title);
				html_q_tmp=html_q_tmp.replace(/{image}/g,'<img src="'+image+'"/>');
				html+=html_q_tmp;
				var html_total='<div class="my_answers" my_q="'+i+'">';
				self.my_debug("Question",{i:i,html:html_q_tmp,title:title,image:image});
				for(var j=1;j<=self.num_results;j++){
					var html_a_tmp=html_answer;
					var title=$("input[name='a_"+i+"_"+j+"']").val();
					html_a_tmp=html_a_tmp.replace(/{id_a}/g,j);
					html_a_tmp=html_a_tmp.replace(/{title}/g,title);
					
					if(typeof title=='undefined')title='Not set';
					
					if(format==2){
						var c_id=i-1;//j-1;
						var color=self.options.colors[c_id];
						html_a_tmp=html_a_tmp.replace(/{color_hex}/g,color);
						
					}else {
						var c_id=i+"_"+j;
						var image=self.answers_images[c_id];
						html_a_tmp=html_a_tmp.replace(/{image}/g,'<image src="'+image+'"/>');
					}
					html_total+=html_a_tmp;
				}
				html_total+='</div>';
				html=html.replace(/{answers}/g,html_total);
				//html+=html_total;
				
			}
			setTimeout(function(){
				self.my_remove_window();
				$("#my_preview_quiz_div iframe").contents().find("body").html('<div style="margin:auto;"><div style="margin:auto;width:650px;">'+html+'</div></div>');
				var top=$("#my_preview_quiz_div").position().top;
				$("html").animate({scrollTop:top},500);
				self.my_working=false;
			},2000);
		};
		this.initialize_step_2=function(){
			var pattern=$("#my_html_pattern").html();
			var num;
			num=self.num_results;
			var title=$("input[name='quiz_title']").val();
			$("#my_quizz_title_2").html(title);
			
			/**
			 * Prepare window for this purpose
			 */
			if(self.no_results){
				for(var i=1;i<=num;i++){
					var html=pattern;
					html=html.replace(/\{id\}/g,i);
					$("#my_outcomes").append(html);
				}
			}else {
				$("#my_outcomes .my_outcome").each(function(i,v){
					var id=$(v).attr('my_id');
					if(id>num){
						self.my_debug("Remove outcome,tooltip ",{id:id})
						$(v).remove();
						$("#my_images_res .my_tooltip_content[my_id='"+id+"']").html($(".my_bare_tooltip").html());
					}
				});
				self.my_debug("Backup num results",{back:self.backup_num_results,num:num});
				if(self.backup_num_results<self.num_results){
					var start=self.backup_num_results+1;
					for(var i=start;i<=self.num_results;i++){
						var html=pattern;
						html=html.replace(/\{id\}/g,i);
						$("#my_outcomes").append(html);
						/**
						 * Remove tooltips from images
						 */
						
					}
				}
			}
			self.no_results=false;
			$(".my_add_more_results").unbind('click');
			$(".my_add_more_results").click(function(e){
				if(self.my_show_dialog_1)return;
				self.my_show_dialog_1=true;
				var num=self.num_results;
				if(num==self.options.max_res){
					alert('You have added maximum number of outcomes');
					self.my_show_dialog_1=false;
					return;
				}
				
				self.my_show_dialog("my_dialog_html");
				setTimeout(function(){
					var num=self.num_results;
					self.my_debug('Number outcomes'+num);
					$("#my_loader_div .my_add_more_outcomes option").remove();
					var can_add=self.options.max_res-num;
					self.my_debug("Can add"+can_add);
					var sel_html='';//'<select class="my_add_more_outcomes">';
					$("#my_loader_div .my_add_more_outcomes").empty();
					for(var i=1;i<=can_add;i++){
						//$(".my_add_more_outcomes").append(
						sel_html+='<option value="'+i+'">'+i+'</option>';
						//$(".my_add_more_outcomes").append('<option>').val(i).text(i);
					}
					$("#my_loader_div .my_add_more_outcomes").html(sel_html);
					//sel_html+='</select>';
					self.my_debug('sel_html',sel_html);
					//$("#my_out_12345").html(sel_html);
					$("#my_loader_div .my_close_dialog").click(function(e){
						self.my_show_dialog_1=false;
						self.my_debug("Close window");
						self.my_remove_window();
					});	
					$("#my_loader_div .my_add_outcomes").click(function(e){
						self.my_show_dialog_1=false;
						var add;
						var t=$("#my_loader_div .my_add_more_outcomes option:selected").val();
						self.my_debug('T'+t);
						add=parseInt(t);
						self.my_debug("Add more outcomes"+add);
						var pre=self.num_results+1;
						self.num_results+=add;
						self.my_debug("Add outcomes",{add:add,num:self.num_results});
						$("select[name='my_num_results'] option[value='"+self.num_results+"']").prop('selected',true);
						var pattern=$("#my_html_pattern").html();
						
						for(var i=pre;i<=self.num_results;i++){
							var html=pattern;
							html=html.replace(/\{id\}/g,i);
							$("#my_outcomes").append(html);
						}
						self.backup_num_results=self.num_results;
						setTimeout(function(){
							$(".my_window[my_id='2'] .my_add_image").unbind('click');
							$(".my_window[my_id='2'] .my_add_image").click(self.add_image_2);
							self.assing_tooltip();
						},500);
						self.my_remove_window();
					});
				},1000);
			});
			$(".my_window[my_id='2'] .my_add_image").unbind('click');
			$(".my_window[my_id='2'] .my_add_image").click(self.add_image_2);
			;
			/*$(".my_window[my_id='2'] .my_add_image").click(function(e){
				var type=$(this).attr('my_name');
				var id=$(this).attr('my_id');
				var tooltip=$(this).attr('my_tooltip');
				self.my_debug("Upload attrs",{type:type,id:id,tooltip:tooltip});
				self.media_tooltip=tooltip;
				self.media_id=id;
				self.media_type=type;
				self.new_quizz_editor.open();
			});*/
			self.backup_num_results=self.num_results;
		};	
		this.add_image_2=function(e){
				var type=$(this).attr('my_name');
				var id=$(this).attr('my_id');
				var tooltip=$(this).attr('my_tooltip');
				self.my_debug("Upload attrs",{type:type,id:id,tooltip:tooltip});
				self.media_tooltip=tooltip;
				self.media_id=id;
				self.media_type=type;
				self.new_quizz_editor.open();
			};
		this.my_back=function(e){
			if(self.my_working){
				return;
			}
			self.my_working=true;
			/*if(typeof $(".my_tooltip").tooltip("close")!='undeifned'){
				$(".my_tooltip").tooltip("close");
			}
			if(typeof $(".my_tooltip_span").tooltip("close")!='undefined'){
				$(".my_tooltip_span").tooltip("close");
			}*/
			
			
			var $win=$(".my_window[my_id='"+self.step+"']");
			var margin_left=$(".my_window[my_id='1']").css('margin-left');
			if(typeof margin_left=='undefined')margin_left=0;
			else margin_left=parseFloat(margin_left);
			margin_left+=self.width;
			self.my_working=true;
			
			var top=$(".my_window[my_id='"+self.step+"'] .my_step").position().top;
			self.step--;
			
			$("html").animate({scrollTop:top},500,function(){
				var new_step=self.step-1;
				//$(".my_window[my_id='"+new_step+"']").animate({'margin-left':"+="+self.width},1000,function(){
				   $(".my_window").animate({'margin-right':"+="+self.width},function(){
				    self.my_working=false;
					self.assing_next_back();
					self.assing_tooltip();
					/*if(typeof $(".my_tooltip").tooltip("close")!='undeifned'){
						$(".my_tooltip").tooltip("close");
					}
					if(typeof $(".my_tooltip_span").tooltip("close")!='undefined'){
						$(".my_tooltip_span").tooltip("close");
					}*/
				});
			});
		}
		this.my_call_ajax=function(data,options){
			$.ajax({
				url:self.options.ajax_url,
				dataType:'json',
				async:false,
				data:data,
				cache:false,
				timeout:self.options.ajax_timeout,
				type:'POST',
				success:function(data,status,jq){
					
					self.my_remove_window();
					/**
					 * If action is required to show 
					 */
					if(options.show_msg){
						self.my_debug("Received data",data);
						if(data.error==0){
							self.my_show_success_window(data.msg);
						
						}else {
							self.my_show_error_window(data.msg);
							}
						
					setTimeout(function(){
						self.my_remove_window();
						self.my_working=false;
					},self.options.msg_window_timeout);
					}
					if(typeof options.after_success =='function'){
						options.after_success(data);
					}
				},
				error:function(jq,status){
					self.my_remove_window();
					self.my_show_error_window(status);
					if(options.show_msg){
						setTimeout(function(){
							self.my_remove_window();
							self.my_working=false;
						},self.options.msg_window_timeout);
					}
					if(typeof options.after_error =='function'){
						options.after_error(jq,status);
					}
				}
				
			});
		};
		/**
		 * Getviewport
		 */
		this.getViewport=function(){
			var h;
			var w;
			if(document.compatMode==='BackCompat'){
				h=document.body.clientHeight;
				w=document.body.clientWidth;
			}else {
				h=document.documentElement.clientHeight;
				w=document.documentElement.clientWidth;
			}
			self.viewport={
					w:w,
					h:h
			};
			self.my_debug('Viewport',self.viewport);
		};
		this.my_remove_window=function(){
			$("body #my_loader_div").remove();
			//$('body').removeClass('my_info_window_overlay');
			self.my_remove_action_overlay();
		};
		this.my_show_dialog=function(id){
			var html='<div id="my_loader_div">'+$("#"+id).html()+'</div>';
			var top=parseFloat($(window).scrollTop());
			var height=$('body').height();
			var width=$('body').width();
			self.getViewport();
			height=self.viewport.h;
			width=self.viewport.w;
			
			$('body').append(html);
			//$('body').addClass('my_info_window_overlay');
			self.my_add_action_overlay();
			self.my_debug("Show window",{top:top,height:height,width:width})
			setTimeout(function(){
			var h=$("#my_loader_div").height();
			var w=$("#my_loader_div").width();
			
			var top_1=Math.floor((height-h)/2)+top;
			var left=Math.floor((width-w)/2);
			self.my_debug("Position",{top:top,top_1:top_1,left:left,height:height,width:width})
			
			$("#my_loader_div").css('left',left+'px');
			$("#my_loader_div").css('top',top_1+'px');
			},200);
		}
		this.my_show_success_window=function(msg){
			var html=self.success_msg;
			html=html.replace('{msg}',msg);
			var top=parseFloat($(window).scrollTop());
			var height=$('body').height();
			var width=$('body').width();
			self.getViewport();
			height=self.viewport.h;
			width=self.viewport.w;
			
			$('body').append(html);
			//$('body').addClass('my_info_window_overlay');
			self.my_add_action_overlay();
			self.my_debug("Show window",{top:top,height:height,width:width})
			setTimeout(function(){
			var h=$("#my_loader_div").height();
			var w=400;//$("#my_loader_div").width();
			
			var top_1=Math.floor((height-h)/2)+top;
			var left=Math.floor((width-w)/2);
			self.my_debug("Position",{top:top,height:height,width:width})
			
			$("#my_loader_div").css('left',left+'px');
			$("#my_loader_div").css('top',top_1+'px');
			$("#my_loader_div").css('height','auto');
			$("#my_loader_div").css('width','400px');
			},200);
		};
		this.my_show_error_window=function(msg){
			var html=self.error_msg;
			html=html.replace('{msg}',msg);
			var top=parseFloat($(window).scrollTop());
			var height=$('body').height();
			var width=$('body').width();
			self.getViewport();
			height=self.viewport.h;
			width=self.viewport.w;
			
			$('body').append(html);
			//$('body').addClass('my_info_window_overlay');
			self.my_add_action_overlay();
			self.my_debug("Show window",{top:top,height:height,width:width})
			setTimeout(function(){
			var h=$("#my_loader_div").height();
			var w=$("#my_loader_div").width();
			
			var top_1=Math.floor((height-h)/2)+top;
			var left=Math.floor((width-w)/2);
			self.my_debug("Position",{top:top,height:height,width:width})
			
			$("#my_loader_div").css('left',left+'px');
			$("#my_loader_div").css('top',top_1+'px');
			},200);
		};
		this.my_add_action_overlay=function(){
			var width=$(document).width();
			var height=$(document).height();
			self.my_debug("Window height width",{width:width,height:height});
			var html='<div class="my_info_window_overlay"';
			html+=' style="position:absolute;top:0;left:0;width:'+width+'px;height:'+height+'px"></div>';
			$('body').append(html);
		};
		this.my_remove_action_overlay=function(){
			$("body .my_info_window_overlay").remove();
		};
			
		this.my_show_working_window=function(msg){
			var html=self.window_msg;
			html=html.replace('{msg}',msg);
			var top=parseFloat($(window).scrollTop());
			var height=$(window).height();
			var width=$(window).width();
			self.getViewport();
			height=self.viewport.h;
			width=self.viewport.w;
			$('body').append(html);
			//$('body').addClass('my_info_window_overlay');
			self.my_add_action_overlay();
			self.my_debug("Show window",{top:top,height:height,width:width})
			setTimeout(function(){
			var h=$("#my_loader_div").height();
			var w=400;//$("#my_loader_div").width();
			
			var top_1=Math.floor((height-h)/2)+top;
			var left=Math.floor((width-w)/2);
			self.my_debug("Position",{top:top,height:height,width:width})
			
			$("#my_loader_div").css('left',left+'px');
			$("#my_loader_div").css('top',top_1+'px');
			$("#my_loader_div").css('height','auto');
			$("#my_loader_div").css('width','400px');
			},200);
		};
		this.my_debug=function(t,o){
			if(self.debug){
				console.log('Testimonials Admin \n'+t+' : '+JSON.stringify(o));
			}
		};

		this.init();
	};
})(jQuery);