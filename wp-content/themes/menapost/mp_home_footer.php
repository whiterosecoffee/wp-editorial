<?php

if( is_single() ) {
    $most_shared_articles = DataSource::get_random_most_shared_articles();
}

?>

<!-- Footer -->
<footer class="container-fluid footer-container">
    <ul class="footer-content">
        <li class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <a  href="<?php echo get_home_url(); ?>" class="footer-logo logo">
                <i class="icon-kasra-logo mp-icon-xxlg orange"></i>
                <span class="orange">أكسرها و أنشرها </span>
            </a>
        </li>
        <li class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <ul class="footer-us">
                <li><a href="<?= MPUrl::get_page_link( 'our-story' ); ?>"><?php _e( 'Our Story', 'menapost-theme' ); ?></a></li>
                <li><a href="<?= MPUrl::get_page_link( 'our-team' ); ?>"><?php _e( 'Our Team', 'menapost-theme' ); ?></a></li>
                <li><a href="http://blog.kasra.co" target="_blank"><?php _e( 'Our Blog', 'menapost-theme' ); ?></a></li>
                <!-- <li><a href="/work-with-us"><?php // _e( 'Work With US', 'menapost-theme' ); ?></a></li> -->
                <li><a href="<?= MPUrl::get_page_link( 'our-rules' ); ?>"><?php _e( 'Terms and Condition', 'menapost-theme' ); ?></a></li>
            </ul>
        </li>
        <li class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <ul class="footer-social">
                <li>
                     
                    <a  class="fb-bg" href="<?= SocialSharing::get_facebook_page(); ?>" target="_blank"><i class="icon-facebook-2 light mp-icon-sm"></i></a>
                    <a  class="twitter-bg" href="<?= SocialSharing::get_twitter_page(); ?>" target="_blank"><i class="icon-twitter-2 light mp-icon-sm"></i></a>
                    <a  class="g-plus-bg" href="<?= SocialSharing::get_google_plus_page(); ?>" target="_blank"><i class="icon-gplus light mp-icon-sm"></i></a>
                    <span id="contact-form-edit_div">
                        <a  id="contact-form-edit" href="" data-toggle="modal" data-target="#contact-form-modal"><i class="icon-mail dark mp-icon-sm"></i></a>
                    </span>
                </li>
            </ul>
        </li>

        <li class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php // mailchimpSF_signup_form(); ?>
            <div class="footer-newsletter">
                <form action="<?php echo home_url( '/' ); ?>" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" name="mc_mv_EMAIL" placeholder="<?php _e( 'Email...', 'menapost-theme' ); ?>" style="border-radius:0">
                    <span class="input-group-btn">
                        <button class="btn btn-warning" type="submit" id="newsletter-submit-button" style="border-radius:0; float:right;"><i class="icon-left-open light mp-icon-xs"></i></button>
                    </span>
                </div>

                <input type="hidden" id="mc_submit_type" name="mc_submit_type" value="js" />
                <input type="hidden" name="mcsf_action" value="mc_submit_signup_form" />
                <input type="hidden" name="mc_signup_submit" value="Subscribe" />
                <?php wp_nonce_field('mc_submit_signup_form', '_mc_submit_signup_form_nonce', false); ?>

                </form>
            </div> 
        </li>

    </ul>
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
<div class="modal fade" id="off-focus-modal" tabindex="-1" role="dialog" aria-labelledby="offFocusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            <button type="button" class="close pull-right" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <div class="off-focus-wrapper">
                    <div class="popup-form-modal">
                        <div class="row">
                            <h1><?php _e( 'Before leaving...check these articles!', 'menapost-theme' ); ?></h1>
                            <div class="ra_Wrap">
                                <?php for( $i=0; $i<3; $i++): ?>
                                    <div class="ra_box">
                                        <div class="ra_thumb">
                                            <a href="<?php echo $most_shared_articles[$i]->get_permalink(); ?>">
                                                 <img class="img-responsive" src="<?php echo $most_shared_articles[$i]->get_image( 'mobile' ); ?>" >
                                            </a>
                                        </div>
                                        <div class="ra_description">
                                            <a href="<?php echo $most_shared_articles[$i]->get_permalink(); ?>">
                                                <p><?php echo $most_shared_articles[$i]->title; ?></p>
                                            </a>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<!-- Go to Top Button -->
<a href="#" class="scrollToTop"><img src="<?php echo CHILD_URL . '/images/back_to_top.png'; ?>" height="25" width="25"/></a>

