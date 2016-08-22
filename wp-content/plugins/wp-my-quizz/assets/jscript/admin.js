(function($) { 
	myAdminEvents=function(o){
		//var self;
		self=this;
		this.debug=true;
		this.options=o;
		this.window_msg='<div id="my_loader_div" class="my_info_window"><div class="my_loader_img"><span class="my_msg">{msg}</span></div></div>';
		this.error_msg='<div id="my_loader_div" class="my_info_window my_error_window"><div class="my_error_img"><span class="my_msg">{msg}</span></div></div>'
		this.success_msg='<div id="my_loader_div" class="my_info_window my_ok_window"><div class="my_ok_img"><span class="my_msg">{msg}</span></div></div>'
				
		this.init=function(o){
			self.width=$("#my_main").width();
			self.main_width=parseFloat(self.width)-205;
			$("#my_form").width(self.main_width);
			$(window).resize(function(e){
				self.width=$("#my_main").width();
			self.main_width=parseFloat(self.width)-205;
			$("#my_form").width(self.main_width);
			})
			self.my_debug("Plugin width ",{width:self.width,main_width:self.main_width});
			self.my_debug("Options",self.options);
			$(".my_tooltip").tooltip({
				items:"div",
				
				content:function(){
					var html=$(this).children(".my_content").html();
					///console.log('Html '+html);
					return html;
				}
				});
			
			if(self.options.my_tab==3){
				self.init_tab_3();
			}
			if(self.options.my_tab==4){
				self.init_tab_4();
			}
			if(self.options.my_tab==100){
				self.init_tab_20();
			}
			
		};
		this.save_metabox_persmissions=function(){
			self.my_debug("SAve metabox permissions");
			if(self.my_working)return;
			var msg=self.options.msgs.saving_metabox;
			var my_group=$(this).parent('form input[type="hiden"][name="my_group"]').val();
			self.my_debug('My group'+my_group);
			var my_group_name;
			my_group_name=$('.my_role [my_role="'+my_group+'"]').children('span').text();
			self.my_debug('My group name '+my_group_name);
			msg+=" "+my_group_name;
			self.my_show_working_window(msg);
			var form_data=$(this).parents("form").serialize();
			var data;
			data={
				nonce:self.options.ajax_nonce,	
				action:self.options.ajax_action,
				data:form_data,
				my_action:'save_metabox_caps'
			};
			self.my_debug('Data',{data:data});
			self.my_working=1;
			self.my_call_ajax(data,{show_msg:true});
			
		};
		this.save_user_permissions=function(e){
			if(self.my_working)return;
			var msg=self.options.msgs.saving_caps;
			var my_role_name=$(this).attr('my_role_name');
			
			msg=msg.replace('{1}',my_role_name);
			self.my_show_working_window(msg);
			
			var my_role=$(this).attr('my_role');
			self.my_debug('Role',{role:my_role});
			var form_data=$(".my_role_inner[my_role='"+my_role+"'] form").serialize();
			var data;
			data={
				nonce:self.options.ajax_nonce,	
				action:self.options.ajax_action,
				data:form_data,
				my_action:'save_user_caps'
			};
			self.my_debug('Data',{data:data});
			self.my_working=1;
			self.my_call_ajax(data,{show_msg:true});
			
			
			
			
			
			
		};
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
						self.my_working=0;
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
							self.my_working=0;
						},self.options.msg_window_timeout);
					}
					if(typeof options.after_success =='function'){
						options.after_success(jq,status);
					}
				}
				
			});
		};
		this.init_tab_4=function(){
			self.my_debug("Allow saving metabox fields");
			$(".my_button_save_permissions").unbind('click');//click(self.save_metabox_permissions);
		
			$(".my_button_save_permissions").click(self.save_metabox_persmissions);
			/**
			 * Show metabox fields
			 */
			$(".my_role_arrow").click(function(e){
				var my_role=$(this).parent('div').attr('my_role');
				var my_showed=$(this).parent('div').attr('my_showed');
				self.my_debug("Show Caps",{my_role:my_role,my_showed:my_showed});
				if(my_showed==0){
					$(this).parent('div').attr('my_showed',1);
					$(this).removeClass('my_role_arrow');
					$(this).addClass('my_role_down');
					$(this).css('height','32px');
					$(".my_role_inner[my_role='"+my_role+"']").slideToggle('slow',function(){
						/*var length=$(".my_button_save_permissions").length;
						self.my_debug("Add save permissions action",length);
						$(".my_button_save_permissions").unbind('click');//click(self.save_metabox_permissions);
						
						$(".my_button_save_permissions").click(self.save_metabox_persmissions);
						*/
					});
					//$(this).css('padding-top','3px');
					$(this).children('span').css('font-size','26px');
					$(this).children('span').css('color','#e46813');
					/*setTimeout(function(){
						self.my_debug("Allow saving metabox fields");
						$(".my_button_action").unbind('click');//click(self.save_metabox_permissions);
					
						$(".my_button_action").click(self.save_metabox_permissions);
					},1000);*/
				}else {
						$(this).children('span').css('font-size','14px');
						$(this).children('span').css('color','black');
						//$(this).css('padding-top','3px');
						$(this).removeClass('my_role_down');
						$(this).addClass('my_role_arrow');
						
						$(this).css('height','20px');
						$(this).parent('div').attr('my_showed',0);
						$(".my_role_inner[my_role='"+my_role+"']").slideToggle('slow');
						
					}
				
			});
			
		}
		this.init_tab_3=function(){
			$(".my_button_action").click(self.save_user_permissions);
			/*$(".my_select_checkbox").click(function(e){
				var my_role;
				
				my_role=$(this).attr('my_role');
				self.my_debug("Select ",{my_role:my_role});
				if($(this).is(":checked")){
					if($(this).hasClass('my_role_cap_select_all')){
						$(".my_role_cap_deselect_all").attr("checked","");
						
						$(".my_role_cap[my_role='"+my_role+"']").attr("checked","checked");
					}else {
						$(".my_role_cap_select_all").attr("checked","");
						$(".my_role_cap[my_role='"+my_role+"']").attr("checked","");
						
					}
				}else {
					
				}
			});*/
			$(".my_role_cap").change(function(e){
				var my_cap=$(this).attr('my_cap');
				if($(this).is(':checked')){
					self.my_debug("Click "+my_cap+" checked");
					$(this).parent('li').find('label').removeClass('my_role_cap_inactive');
					$(this).parent('li').find('label').addClass('my_role_cap_active');
				
				}else {
					self.my_debug("Click "+my_cap+" unchecked");
					$(this).parent('li').find('label').addClass('my_role_cap_inactive');
					$(this).parent('li').find('label').removeClass('my_role_cap_active');
				
					}
				}
			);
			/*$(".my_role_cap").click(function(e){
				var my_cap=$(this).attr('my_cap');
				if($(".my_role_cap[my_cap='"+my_cap+"']").is('checked')){
					self.my_debug("Click "+my_cap+" checked");
					$(this).parent('li').find('label').removeClass('my_role_cap_inactive');
					$(this).parent('li').find('label').addClass('my_role_cap_active');
				}else {
					self.my_debug("Click "+my_cap+" unchecked");
					$(this).parent('li').find('label').addClass('my_role_cap_inactive');
					$(this).parent('li').find('label').removeClass('my_role_cap_active');
				}
			});*/
			
			$(".my_role_arrow").click(function(e){
				var my_role=$(this).parent('div').attr('my_role');
				var my_showed=$(this).parent('div').attr('my_showed');
				self.my_debug("Show Caps",{my_role:my_role,my_showed:my_showed});
				if(my_showed==0){
					$(this).parent('div').attr('my_showed',1);
					$(this).removeClass('my_role_arrow');
					$(this).addClass('my_role_down');
					$(this).css('height','32px');
					$(".my_role_inner[my_role='"+my_role+"']").slideToggle('slow');
					//$(this).css('padding-top','3px');
					$(this).children('span').css('font-size','26px');
					$(this).children('span').css('color','#e46813');
					}else {
						$(this).children('span').css('font-size','14px');
						$(this).children('span').css('color','black');
						//$(this).css('padding-top','3px');
						$(this).removeClass('my_role_down');
						$(this).addClass('my_role_arrow');
						
						$(this).css('height','20px');
						$(this).parent('div').attr('my_showed',0);
						$(".my_role_inner[my_role='"+my_role+"']").slideToggle('slow');
					}
				
			});
		};
		this.init_tab_20=function(){
			$(".my_role_arrow").click(function(e){
				var my_role=$(this).parent('div').attr('my_key');
				var my_showed=$(this).parent('div').attr('my_showed');
				var my_get=$(this).parent('div').attr('my_get');
				/**
				 * Call ajax to get item docs
				 */
				if(my_get==0){
					var data={
							nonce:self.options.ajax_nonce,	
							action:self.options.ajax_action,
							item:my_role,
							my_action:'get_docs_item'
						};
					self.my_debug("Get item docs",data);
					var msg=self.options.msgs.get_docs;
					self.my_show_working_window(msg);
					self.my_call_ajax(data,{
						show_msg:false,
						after_success:function(data){
							self.my_remove_window();
							$("div.my_role[my_key='"+my_role+"'] .my_role_inner_docs").html(data.html);
							$("div.my_role[my_key='"+my_role+"']").attr('my_get',1);
							$(".my_show_tab").unbind('click');
							$(".my_show_tab").click(function(e){
								e.preventDefault();
								var showed=$(this).attr('my_showed');
								var id=$(this).attr('id');
								id=id.replace('my_tab_a_','');
								$("#my_tab_"+id).slideToggle('slow');
								if(showed==0){
									$(this).parent('li').addClass('my_open_item');
									$(this).attr('my_showed',1);
								}else {
									$(this).parent('li').removeClass('my_open_item');
									$(this).attr('my_showed',0);
								}
							});
						},
						after_error:function(jq,status){
							self.my_show_error_window(status);
							setTimeout(function(){
								self.my_remove_window();
								self.my_working=0;
							},self.options.msg_window_timeout);
						}
						
					});
					
					
				}
				
				self.my_debug("Show Docs",{my_role:my_role,my_showed:my_showed});
				if(my_showed==0){
					$(this).parent('div').attr('my_showed',1);
					$(this).removeClass('my_role_arrow');
					$(this).addClass('my_role_down');
					$(this).css('height','32px');
					$(".my_role_inner_docs[my_key='"+my_role+"']").slideToggle('slow');
					//$(this).css('padding-top','3px');
					$(this).children('span').css('font-size','26px');
					$(this).children('span').css('color','#e46813');
					}else {
						$(this).children('span').css('font-size','14px');
						$(this).children('span').css('color','black');
						//$(this).css('padding-top','3px');
						$(this).removeClass('my_role_down');
						$(this).addClass('my_role_arrow');
						
						$(this).css('height','20px');
						$(this).parent('div').attr('my_showed',0);
						$(".my_role_inner_docs[my_key='"+my_role+"']").slideToggle('slow');
					}
				
			});
		};
		this.my_debug=function(t,o){
			if(self.debug){
				console.log('Pro Events Backend \n'+t+' : '+JSON.stringify(o));
			}
		};
		this.my_remove_window=function(){
			$("body #my_loader_div").remove();
			//$('body').removeClass('my_info_window_overlay');
			self.my_remove_action_overlay();
		};
		this.my_show_success_window=function(msg){
			var html=self.success_msg;
			html=html.replace('{msg}',msg);
			var top=parseFloat($(window).scrollTop());
			var height=$('body').height();
			var width=$('body').width();
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
		this.my_show_error_window=function(msg){
			var html=self.error_msg;
			html=html.replace('{msg}',msg);
			var top=parseFloat($(window).scrollTop());
			var height=$('body').height();
			var width=$('body').width();
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
			var height=$('body').height();
			var width=$('body').width();
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
		
		
		this.init();
			
	}
})(jQuery);	