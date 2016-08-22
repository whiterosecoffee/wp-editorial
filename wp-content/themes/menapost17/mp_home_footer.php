<?php

if( is_single() ) {
	$most_shared_articles = DataSource::get_random_most_shared_articles();
}

?>

<!-- Footer -->
<footer class="container-fluid footer-container">

</footer>
<!-- /Footer -->
<!-- Login Modal -->
<div class="login-modal modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">

	<div class="modal-body">
		<div>
			<div class="signin-container">
			 <header>
			<h1><?php _e('Login', 'menapost-theme'); ?></h1>
		</header>
			  <ul class="rrssb-buttons clearfix">
				<li class="facebook" >
					<a class="social_connect_login_facebook">
						<span class="sb-icon">
							<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
								<path d="M27.825,4.783c0-2.427-2.182-4.608-4.608-4.608H4.783c-2.422,0-4.608,2.182-4.608,4.608v18.434
								c0,2.427,2.181,4.608,4.608,4.608H14V17.379h-3.379v-4.608H14v-1.795c0-3.089,2.335-5.885,5.192-5.885h3.718v4.608h-3.726
								c-0.408,0-0.884,0.492-0.884,1.236v1.836h4.609v4.608h-4.609v10.446h4.916c2.422,0,4.608-2.188,4.608-4.608V4.783z"></path>
							</svg>
						</span>
						<span class="text"><?php _e( 'Facebook', 'menapost-theme' ); ?></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<!-- /.container -->
</div>
</div>
</div>

<div id="social_connect_facebook_auth">
	<input type="hidden" name="client_id" value="<?php echo get_option( 'social_connect_facebook_api_key' ); ?>" />
	<input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=facebook-callback'); ?>" />
</div>

<div id="social_connect_twitter_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('login.php?social-connect=twitter'); ?>" /></div>


</div>
<!-- /Login Modal -->

<!-- MessageBox Modal -->
<div class="login-modal modal fade" id="messagebox-modal" tabindex="-1" role="dialog" aria-labelledby="messageBoxModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div>
					<div class="signin-container">
						<header>
							<h1 data-element="loading-icon" class="text-center"><i class="icon-loading mp-icon-xs orange animate-spin"></i><?php _e( 'Loading ...', 'menapost-theme' ); ?></h1>
							<h3 data-element="message-box" class="text-center"></h3>
						</header>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Suggest an edit-->
<div class="modal fade" id="suggest-modal" tabindex="-1" role="dialog" aria-labelledby="suugestBoxModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			<button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<div>
					<div class="suggest-edit-modal popup-form-modal">
						<div id="suggestion_div">
							<form>
								<input type="hidden" id="hidden_post_id" />
								<input type="hidden" id="hidden_author_id" />
								<header>
									<h2>نموذج تعديل</h2>
									<!--<h3>Author</h3>-->
								</header>
								<div class="form-inline">
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-12 field name">
											<input type="text" class="form-control" id="corrector-name" name="corrector-name" placeholder="اسمك">
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12 field name">
											<input type="email" class="form-control" id="corrector-email" name="corrector-email" placeholder="عنوان بريدك الإلكتروني">
										</div>
									</div>
								</div>
								<div class="form-group popup-textarea">

<!--                                     <label for="exampleInputEmail1">&nbsp;</label>
 -->                                    <textarea id="correction-text" name="correction-text" class="form-control" rows="5" placeholder="ما هو اقتراحك؟"  maxlength="1000"></textarea>
								<span class="char-count" class="pull-left"></span>
								</div>
								<div class="form-inline">
									<div class="checkbox">
										<label>
											<input id="newsletter-checkbox" type="checkbox" checked="checked"> أضفني إلى نشرة كسرة
										</label>
									 </div>
									 <button type="submit" class="btn btn-default btn-submit" disabled="disabled">أرسل</button>
								</div>
							</form>
						</div>
						<br>
						<div id="email_notification" style="display: none; width: 100%; border-top: 1px solid #ff9100; padding: 20px; margin-top: 25px; text-align: center; font-size: 30px;">تم إخطار اقتراحكم، شكرا لك!</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Contact Form-->
<div class="modal fade" id="contact-form-modal" tabindex="-1" role="dialog" aria-labelledby="contactFormModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			<button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<div>
					<div class="contact-form-modal popup-form-modal">
						<div id="contact_form_div">
							<form>
								<header>
									<h2>اتصل بنا</h2>
									<h4> الرجاء التأكد من ملئ جميع الحقول</h4>
								</header>
								<div class="form-inline">
									<div class="form-group">
										<div class="col-md-12 col-sm-12 col-xs-12 field name">
											<input type="text" class="form-control" id="contact-name" name="contact-name" placeholder="اسمك">
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12 no-margin field name">
											<input type="email" class="form-control" id="contact-email" name="contact-email" placeholder="عنوان بريدك الالكتروني">
										</div>
										<!-- <div class="col-md-12 col-sm-12 col-xs-12 field name">
											<input type="text" class="form-control" id="contact-subject" name="contact-subject" placeholder="عنوان الموضوع">
										</div> -->
									</div>
								</div>
								<div class="form-group popup-textarea">
									<textarea id="contact-form-text" name="contact-form-text" class="form-control" rows="5" placeholder="رسالتك"  maxlength="1000"></textarea>
									<span class="char-count" class="pull-left"></span>
								</div>
								<!-- <div class="btn-wrapper">
									 <button type="submit" class="btn btn-default btn-submit" disabled="disabled">أرسل</button>
								</div> -->
								<div class="form-inline">
									<div class="checkbox">
										<label>
											<input id="contact-form-newsletter-checkbox" type="checkbox" checked="checked"> أضفني إلى نشرة كسرة
										</label>
									 </div>
									 <button type="submit" class="btn btn-default btn-submit" disabled="disabled">أرسل</button>
								</div>
							</form>
						</div>
						<br>
						<div id="contact_form_notification" style="display: none; width: 100%; border-top: 1px solid #ff9100; padding: 20px; margin-top: 25px; text-align: center; font-size: 30px;">.شكرا. سنكون على اتصال</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if( is_single() ) : ?>
<!-- off focus popup-->
<!-- <div class="modal fade" id="off-focus-modal" tabindex="-1" role="dialog" aria-labelledby="offFocusModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			<button type="button" class="close pull-right" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<div class="off-focus-wrapper">
					<div class="popup-form-modal">
						<div id="newsletterSignUp">
							<h2>اشترك الآن في نشرة كسرة</h2>
							<p>سجل معنا في النشرة واكسر روتينك ومللك بنكهة عربية مميزة.</p>
							<p>اكسرها وانشرها!</p>
							<?php //mailchimpSF_signup_form(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<?php endif; ?>

<!-- Go to Top Button -->
<a href="#" id="btn-to-top" class="scrollToTop"><i class="icon-up-open"></i></a>
<script type="text/javascript">
	var
	body = document.body,
		kasraIconLogo = document.getElementById("logo-icon"),
		// header = document.getElementById('main'),
	socialStatsVertical = document.getElementById('socialStatsVertical'),
	navOpen = document.getElementById('nav-open-btn'),
	navClose = document.getElementById('nav-close-btn');


	function addMenuOpen(){ /*Add menu-open class to html element*/
		body.className += " menu-open";
		body.className = body.className.replace(/\bmobile-nav-closed\b/,'');
		// header.className += " menu-open";
	}
	function removeMenuOpen(){ /*Removes menu-open class from html element*/
		body.className = body.className.replace(/\bmenu-open\b/,'');
		body.className += " mobile-nav-closed";

		// header.className = header.className.replace(/\bmenu-open\b/,'');
	}
	navOpen.addEventListener('click', removeClass(body, " mobile-nav-closed"));
/*Click function to add menu-open class*/
	navOpen.addEventListener('click', addMenuOpen ); /*Click function to add menu-open class*/
	navClose.addEventListener('click', removeMenuOpen ); /*Click function to remove menu-open class*/

	function hasClass(ele,cls) {
	  return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
	}
	function addClass(ele,cls) {
	  if (!hasClass(ele,cls)) ele.className += " "+cls;
	}
	function removeClass(ele,cls) {
	  if (hasClass(ele,cls)) {
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	  }
	}
	onscroll = function() { /*On Scroll function to enable scrolling navigation and to switch to Kasra emblem logo*/
	  var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
			if (scrollTop > 78) {
				addClass(body, " scroll-menu");
				removeClass(kasraIconLogo, "icon-kasra-logo");
				addClass(kasraIconLogo, "icon-kasra-emblem");
			}
			else if (scrollTop < 78) {
				removeClass(body, " scroll-menu");
				removeClass(kasraIconLogo, "icon-kasra-emblem");
				addClass(kasraIconLogo, "icon-kasra-logo");
			}
			if (scrollTop > 530){addClass(body, " singleSocialMenu"); }
			else if (scrollTop < 530) {removeClass(body, " singleSocialMenu");}
	  }


/*Go To Top Function*/
var goToTop = document.getElementById('btn-to-top');

goToTop.onclick = function () {
	smoothScrollTo(0, 500);}

window.smoothScrollTo = (function () {
  var timer, start, factor;

  return function (target, duration) {
	var offset = window.pageYOffset,
		delta  = target - window.pageYOffset; /* Y-offset difference*/
	duration = duration || 1000;              /* default 1 sec animation*/
	start = Date.now();                      /* get start time*/
	factor = 0;

	if( timer ) {
	  clearInterval(timer); // stop any running animations
	}

	function step() {
	  var y;
	  factor = (Date.now() - start) / duration; // get interpolation factor
	  if( factor >= 1 ) {
		clearInterval(timer); // stop animation
		factor = 1;           // clip to max 1.0
	  }
	  y = factor * delta + offset;
	  window.scrollBy(0, y - window.pageYOffset);
	}

	timer = setInterval(step, 10);
	return timer;
  };
}());

</script>
 <script type="text/javascript">//<![CDATA[
			// Google Analytics for WordPress by Yoast v4.3.5 | http://yoast.com/wordpress/google-analytics/
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-51000330-1']);
				_gaq.push(['_trackPageview']);
			(function () {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();
//]]></script>

