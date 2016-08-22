<?php

/**
 *
 * Template Name: Browser Upgrade
 * 
 */

get_header();
?>

    <!-- Error content -->
    <div class="jumbotron error-container browser-upgrade" data-page="browser-upgrade">
        <div class="container">
       		<img src="<?php echo CHILD_URL . '/images/kasra-facebook-logo.png' ?>">
            <h1><?php _e( 'Outdated browser message', 'menapost-theme' ); ?></h1>
        </div>
	
		<ul class="downloads">
			<li>
				<div class=first>
					<h4 id="download_ie"><a title="Download Internet Explorer" href="http://www.microsoft.com/windows/internet-explorer/"><span>Internet Explorer</span></a></h4>
					<h5>Version 9+</h5>
				</div>
			</li>
			<li>
				<div>
					<h4 id="download_firefox"><a title="Download Mozilla Firefox" href="http://www.mozilla.com/firefox/"><span>Mozilla Firefox</span></a></h4>
					<h5>Version 16+</h5>
				</div>
			</li>
			<li>
				<div>
					<h4 id="download_chrome"><a title="Download Google Chrome" href="http://www.google.com/chrome/"><span>Google Chrome</span></a></h4>
					<h5>Version 7+</h5>
				</div>
			</li>
			<!-- <li>
				<div>
					<h4 id="download_safari"><a title="Download apple Safari" href="http://www.apple.com/safari/"><span>apple Safari</span></a></h4>
					<h5>Version 5+</h5>
				</div>
			</li> -->
		</ul>
	</div>

<?php
/**
 * Loads genesis engine.
 */
