(function($) { 
	myQuizzFront=function(o){
		var self;
		var self=this;
		this.debug=false;
		this.options=o;
				
		this.init=function(o){
			$(".my_share_facebook").click(self.share_facebook);
			$(".my_share_twitter").click(self.share_twitter);
			self.check_html='<div class="my_check_ok"></div>';
			self.my_debug("Starting",o);
			self.responsivnes();
			self.chooses={};
			self.choosed_num=0;
			$(window).resize(self.responsivnes);
			self.questions=[];
			$(".my_question").each(function(i,v){
				var obj={};
				var t=$(v).find(".my_question_title").text();
				obj.title=t;
				obj.id=$(v).attr('my_id');
				obj.answers=[];
				$(v).find(".my_answer").each(function(i1,v1){
					var obj_1={};
					obj_1.title=$(v1).find(".my_title").text();
					obj_1.id=$(v1).attr('my_id');
					obj.answers[obj.answers.length]=obj_1;
				});
				self.questions[self.questions.length]=obj;
			});
			self.my_debug("Questions",self.questions);
			//$(".my_check").click(self.my_check_answers);
			$(".my_answer").click(self.my_check_answers)
		};
		this.share_twitter=function(e){
			e.preventDefault();
			var sTop=window.screen.height/2-(218);
			var sLeft=window.screen.width/2-(313);
			var href=$(this).attr('href');
			window.open(href,'twitter_share','toolbar=0,status=0,width=580,height=400,top='+sTop+',left='+sLeft);
			
			
		}
		this.my_check_answers=function(e){
			var my_id=$(this).parents(".my_question").attr('my_id');
			if(typeof self.chooses[my_id]!='undefined' && (self.choosed_num==self.questions.length)){
				return;
			}
			//var a_id=$(this).find(".my_check_span").attr('my_id');
			var a_id=$(this).attr('my_id');
			var my_change_answ=false;
			if(typeof self.chooses[my_id]!='undefined'){
				my_change_answ=true;
				old_id=self.chooses[my_id];
				self.my_debug("Change answer",{old_id:old_id,my_id:my_id,a_id:a_id});
				
				$(".my_answer[my_id='"+old_id+"'] .my_check .my_check_ok").remove();
				$(".my_answer[my_id='"+old_id+"']").removeClass("my_blue");
				$(".my_answer[my_id='"+a_id+"']").css("opacity",1);
			}
			self.chooses[my_id]=a_id;
			self.my_debug("Choosses",self.chooses);
			var $this;
			$this=$(this).find('.my_check');
			$this.append(self.check_html);
			//$(this).parents(".my_question").find(".my_answer").each(function(i,v){
			$(this).parents(".my_answers").find(".my_answer").each(function(i,v){
				var id=$(v).attr('my_id');
				if(id!=a_id)$(v).css('opacity','0.6');
				else $(v).addClass('my_blue');
			});
			if(!my_change_answ)self.choosed_num++;
			if(self.choosed_num==self.questions.length){
				var arr=[];
				$.each(self.chooses,function(i,v){
					arr[arr.length]=v;
				});
				self.get_result([],arr);
			}
		}
		this.get_result=function(arr,arr1){
			var post_id=$("input[name='my_post_quizz_id']").val();
			var data={
				action:self.options.ajax_action,
				q:arr,
				a:arr1,
				post_id:post_id,
				nonce:self.options.nonce
			};
			self.my_debug('Call ajax',data);
			$.ajax({
				url:self.options.admin_ajax_url,
				dataType:'json',
				async:false,
				data:data,
				cache:false,
				timeout:20000,
				type:'POST',
				success:function(data,status,jq){
					if(data.error==0){
						self.my_debug("Received",data)
						$(".my_share_results").show();
						$(".my_share_results #my_result").html(data.html);
						$(".my_share_facebook").attr('href',data.facebook_link);
						$(".my_share_twitter").attr('href',data.twiiter_link);
						$(".my_share_email").attr('href',data.email_link);
						var top=$(".my_share_results").offset().top;
						$("html,body").animate({scrollTop:top},1000);
						
						
					}	
				},
				error:function(){
					alert('Error');
				}
			});
			
		};
		this.share_facebook=function(e){
			e.preventDefault();
			var sTop=window.screen.height/2-(218);
			var sLeft=window.screen.width/2-(313);
			var href=$(this).attr('href');
			window.open(href,'facebook_share','toolbar=0,status=0,width=580,height=400,top='+sTop+',left='+sLeft);
			//toolbar=0,status=0toolbar=0,status=0
		};
		/**
		 * Call ajax
		 */
		this.call_ajax=function(data,options){
				$.ajax({
					url:self.options.admin_ajax_url,
					dataType:'json',
					async:false,
					data:data,
					cache:false,
					timeout:20000,
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
		
		this.responsivnes=function(){
			var width=$(".article-content-section .mp-article-content").width();
			self.my_debug("Width",width);
			if(width>600){
				$(".my_answers").css('width',602);
				$(".my_answer").css('margin-right','');
				$(".my_question").css('width',600);
				$(".my_question_title").css('width',600);
				$(".my_answer:nth-child(3n)").css('margin-right',0);
			}else if(width>480&&width<600){
				$(".my_question").css('width',width);
				$(".my_question_title").css('width',width);
				$(".my_answers").css('width',408);
				$(".my_answer").css('margin-right','');
				$(".my_answer:nth-child(2n)").css('margin-right',0);
				$(".my_answer").css('margin-right','');
				
			}else if(width>400){
				$(".my_question_title").css('width',width);
				$(".my_question").css('width',width);
				$(".my_answers").css('width',408);
				$(".my_answer").css('margin-right','');
				$(".my_answer:nth-child(2n)").css('margin-right',0);
				$(".my_answer").css('margin-right','');
			}else {
				$(".my_question_title").css('width',width);
				$(".my_question").css('width',width);
				$(".my_answer").css('margin-right','');
				
				$(".my_answers").css('width',194);
				//$(".my_answer:nth-child(3n)").css('margin-right','');
			
				
			}
		}
		this.my_debug=function(t,o){
			if(self.debug){
				console.log('Quizz Front\n'+t+' : '+JSON.stringify(o));
			}
		};
		this.init();
	};
})(jQuery);