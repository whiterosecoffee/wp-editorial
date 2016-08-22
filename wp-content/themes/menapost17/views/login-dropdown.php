<a data-login-link="" data-toggle="dropdown" class="nav-links profile-btn in" style="background: url(<?= $mp_current_user->get_avatar(); ?>) no-repeat;"><b class="login caret"></b></a>

<ul class="dropdown-menu login-dropdown" role="menu" aria-labelledby="teaser-filter">
      <li class="profile-btn" role="presentation"><?= $mp_current_user->get_nickname(); ?><!-- <a role="menuitem" tabindex="-1" href="<?= MPUrl::get_page_link( 'profile' ); ?>"></a> --></li>

      <!-- <li role="presentation"><a role="menuitem" tabindex="-1"><?php // _e( 'My Drafts', 'menapost-theme' ); ?></a></li> -->
      <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo mp_get_link( 'bookmarks', 'recent' ); ?>"><?php _e( 'My Reading List', 'menapost-theme' ); ?></a></li>
      <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php // echo mp_get_link( 'recommended-readings', 'recent' ); ?>"><?php // _e( 'Recommended Readings', 'menapost-theme' ); ?></a></li> -->

      <?php if( current_user_can( 'edit_posts' ) ) : ?>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo admin_url('index.php'); ?>"><?php _e( 'Admin Panel', 'menapost-theme' ); ?></a></li>
      <?php endif; ?>
      <li class="logout-btn" role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout', 'menapost-theme' ); ?></a></li>
</ul>
