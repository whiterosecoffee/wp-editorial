<div id="singleSocialMenu">

  <a id="singleSocialFacebook" class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
      <i class="icon-facebook-2"></i>
      <span><?php _e( 'Share', 'menapost-theme' ); ?></span>
  </a>

  <a id="singleSocialTwitter" class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">
    <i class="icon-twitter-2 twitter"></i>
    <span><?php _e( 'Chirp', 'menapost-theme' ); ?></span>
  </a>

  <a id="singleSocialWhatsapp" href="<?php echo SocialSharing::get_whatsapp_link( $article ); ?>">
    <i class="icon-whatsapp light"></i>
  </a>
</div>

<style>
  .scroll-menu #main-all-menus-wrapper {border-bottom-right-radius:0px;background-color:transparent;}
  .scroll-menu .menu {background-color:transparent;}
    .singleSocialMenu #nav-open-btn{display:block;}
    .singleSocialMenu #main-all-menus-container {display:none;}
    .singleSocialMenu #nav-open-btn { display: block!important;}


  #singleSocialMenu{display:none;}
    .singleSocialMenu #singleSocialMenu{display:block; position:fixed; top:0; left:50%;  margin-left:-92px; height:40px;}
    #singleSocialMenu a{float: right; text-align:center; line-height:40px; padding:0 10px; margin-left: 8px;}
      #singleSocialMenu a i {display:inline-block; width:20px; height:20px;line-height:20px; background-color:white; border-radius:50%; font-size:.875em;}
      #singleSocialMenu a#singleSocialWhatsapp i {background-color: transparent; font-size: 1em;}
      #singleSocialMenu a span {color:white; }
  #singleSocialFacebook{background-color:#306199; color:#306199;}
  #singleSocialTwitter{background-color:#26c4f1;}
  #singleSocialWhatsapp{background-color:#22A115;}
.desktop #singleSocialWhatsapp, .tablet #singleSocialWhatsapp {display: none;}
</style>

