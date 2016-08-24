    <?php
/*
 * Template Name: Our Team
 *
 */
get_header();

$team_members = mp_get_team_members();

do_action( 'navbar' );
?>

<!-- Header -->
<header data-page="team">
	<h1><?php _e( 'Our Team', 'menapost-theme' ); ?></h1>
</header>

<!-- Team Members -->
<section>
	<div id="article-teasers" class="container-fluid article-container">
		<ul class="container-main grid animated fadeInUp" id="article-list-grid-view">
			<?php foreach( $team_members as $team_member ):
				$mp_team_member = new MPUser( $team_member->ID );
			 ?>
			<li class="item floatright" id="user-id-<?php echo $team_member->ID; ?>">
				<article class="article-tile">
					<header>
						<div class="article-teaser-header">
							<a href="<?php echo get_author_posts_url( $team_member->ID ); ?>"><div class="team-img" style="background: url('<?= $mp_team_member->get_avatar( 'team-page' ); ?>');"></div></a>
							<div class="cite">
								<div class="pull-right">
								</div>
							</div>
						</div>

					</header>
					<footer class="article-content">
						<div class="title">
							<a href="<?php echo get_author_posts_url( $team_member->ID ); ?>">
								<h1><?php echo $team_member->display_name; ?></h1>
							</a>
						</div>

						<!-- TODO: I created the h3 myself. -->
						<h3 class="excerpt"><?php echo $team_member->title?$team_member->title:"&nbsp;"; ?></h3>
						<!-- / -->

			<div class="author-contact">


			<a class="orange-bg" href="<?php echo 'mailto:' . $mp_team_member->get_email(); ?>"><i class="icon-mail mp-icon-xs orange" style="font-size:16px;"></i></a>

			<?php if( isset( $team_member->facebook_id ) && $team_member->facebook_id != '' ) : ?>
			<a class="fb-bg" href="<?php echo $team_member->facebook_id; ?>" target="_blank"><i class="icon-facebook-2 mp-icon-xs fb" style="font-size:16px;"></i></a>
			<?php endif; ?>

			<?php if( isset( $team_member->twitter_id ) && $team_member->twitter_id != '' ) : ?>
			<a class="twitter-bg" href="<?php echo 'http://www.twitter.com/' . $team_member->twitter_id; ?>" target="_blank"><i class="icon-twitter-2 mp-icon-xs twitter" style="font-size:16px;"></i></a>
			<?php endif; ?>
		</div>

					</footer>
				</article>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>


<?php

get_footer();