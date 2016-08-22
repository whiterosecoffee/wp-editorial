<?php
wp_enqueue_script( 'kasra-eid-script' );
?>
<style type="text/css">
	.app_container {
		width: 810px;
		/*height: 582px;*/
		margin: 0 auto;
		background: #f6f6f6;
		padding: 6px;
		min-height: 100%;
		position: relative;
	}
	.app_container ul#tabs {
		list-style-type: none;
		margin: 30px 0 0 0;
		width: 100%;
		text-align: center;
	}
	.app_container ul#tabs li {
		display: inline;
	}
	.app_container ul#tabs li a {
		color: #626366;
		border-bottom: none;
		padding: 1em 1em 0.3em 1em;
		text-decoration: none;
	}
	.app_container ul#tabs li a.selected {
		color: #626366;
		font-weight: bold;
		border-bottom: 3px solid #ff9100
	}
	.app_container div.tabContent {
		/*padding: 0.5em;*/
		background-color: #f6f6f6;
	}
	.hide {
		display: none;
	}
	.app_container hr {
		height: 3px;
		background: #626366;
		border: none
	}
	.app_container .footer {
		height: 34px;
		bottom: 0;
		left: 0;
	}
	.app_container .footer img {
		float: right
	}
	.heading {
		width: 100%;
		height: 35px
	}
	.heading h2 {
		text-align: center;
		margin: 30px 0;
		color: #626366;
	}
	.app_container ul {
		padding: 0;
		list-style-type: none;
	}
	.app_container ul li:nth-child(n+2) {
		border-right: 1px solid #9e9e9e;
	}
	.stack_holder {
		width: 500px;
		margin: 0 auto;
		text-align: center
	}
	#step2 .stack_holder.potrait {
		width: 500px;
	}
	#step2 .stack_holder.landscape {
		width: 750px;
	}
	.stack_holder .card {
		display: inline-block;
		width: 172px;
		height: 172px;
		margin: 1em;
		border: 2px solid #666;
		cursor: pointer;
	}
	.stack_holder .card img {
		width: 168px;
		height: 168px;
	}
	.caption {
		margin: 0 auto
	}
	.caption .upimg {
		width: 238px;
		height: 358px;
		background: #FFF;
		float: left;
		float: left;
		position: relative;
	}
	.caption .greeting {
		width: 112px;
		height: 358px;
		background: #ccc;
		position: relative;
		float: right;
	}
	.upload_buttons {
		position: relative;
	}
	.upload_buttons button {
		padding: 12px 25px;
		background: #808284;
		color: #fff;
		text-decoration: none;
		font-weight: 700;
		display: inline-block;
		margin: 20px 0 0;
		border: 0;
	}
	.upload_buttons button:hover {
		background: #5a5c5e
	}
	.nav_btns {
		width: 800px;
		margin: 0 auto
	}
	.nav_btns button {
		padding: 10px 26px;
		background: #ff9e15;
		color: #fff;
		text-decoration: none;
		font-weight: 700;
		border: 0;
	}
	.nav_btns button:hover {
		background: #ffb650
	}

	.webcam-preview.potrait {
		min-width: 500px;
		min-height: 500px;
	}

	.webcam-preview.landscape {
		min-width: 750px;
		min-height: 500px;
	}

	.image-placeholder canvas {
		width: 500px;
		height: 500px;
	}
	#file-select {
		visibility: hidden;
		width: 0;
		height: 0;
	}
	#share-card-preview {
		width: 500px;
		height: 500px;
	}
	.loading-indicator {
		position: fixed;
		left: 0;
		z-index: 1000;
		background-color: #ffffff;
		color: #ff9100;
		padding: 5px;
		right: 0;
		top: 50%;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
		width: 140px;
		height: 50px;
		bottom: 0;
		font-size: 24px;
		border: 2px solid #ff9100;
		display: inline-block;
	}
	.container_bg {
		width: 708px;
		/*height: 575px;*/
		margin: 0 auto;
		position: relative;
		/*background: url("<?= $image_path . 'eid_bg.png'; ?>") no-repeat left bottom #f6f6f6*/
	}
	.msg_box {
		width: 314px;
		height: 424px;
		float: right;
		margin-top: 100px;
		margin-right: 10px;
		padding: 10px;
		text-align: center;
	}
	.msg_box span {
		width:312px;
		font-size: 22px;
		font-family: "Droid Arabic Kufi";
		color: rgb( 98, 99, 102 );
		font-weight: bold;

	}

	.msg_box .start_btn {
		font-family: "Arial";
		display:block;
		padding: 10px 0px;
		width:136px;
		margin:0 auto;
		background: #ff9e15;
		border: 0;
		color: #fff;
		text-decoration: none;
		font-weight: 700;
		margin-top: 20px;
	}

	.container_bg hr {
		height: 3px;
		background: #626366;
		border: none
	}
	.container_bg .footer {
		display: inline-block;
		position: relative;
		width: 100%;
		padding: 5px 0;
	}
	.container_bg .footer img {
		float: right
	}

	.container_bg .left_thumbnails {
		display: block;
		float: left;
		width: 340px;
		/* height: 240px; */
		padding: 25px 0 0 20px;
		/*position: absolute;*/

	}

	.container_bg .left_thumbnails img:last-child {
		margin-left:75px;

	}
	#allow-webcam-message {
		font-size: 16px;
		margin: 30px 0 10px 0;
	}

</style>

<!-- Loading Indicator -->
<div class="loading-indicator hide"><?= __('Loading ...', 'kasra-eid-greeting'); ?></div>
<div class="container_bg">
	<div class="left_thumbnails">
		<img src="<?= $image_path . 'side_thumb1.png'; ?>" width="264" height="255">
		<img src="<?= $image_path . 'side_thumb2.png'; ?>" width="264" height="255">
	</div>
	<div class="msg_box"> <span><?= __('Let Kasra help you create and personalize your own Eid greeting to share with your loved ones!', 'kasra-eid-greeting'); ?></span>
		<button class="start_btn"><?= __('Get started!', 'kasra-eid-greeting'); ?></button>
	</div>
	<div style="clear:both"></div>
	<!-- <div class="footer">
		<hr />
		<img src="<?= $image_path . 'kasra_footer.png'; ?>" />
	</div> -->
</div>
<div class="app_container hide">
	<ul id="tabs">
		<li><a href="#step1"><?= __('Step 1', 'kasra-eid-greeting'); ?></a></li>
		<li><a href="#step2"><?= __('Step 2', 'kasra-eid-greeting'); ?></a></li>
		<li><a href="#step3"><?= __('Step 3', 'kasra-eid-greeting'); ?></a></li>
	</ul>
	<div class="tabContent" id="step1">
		<div class="heading">
			<h2><?= __('Select an Eid Card you want to use', 'kasra-eid-greeting'); ?></h2>
		</div>
		<div class="stack_holder">

		</div>
	</div>
	<div class="tabContent" id="step2">

		<div class="choose-photo-step">
			<div class="heading">
				<h2><?= __('Choose your photo', 'kasra-eid-greeting'); ?></h2>
			</div>
			<div class="stack_holder">
				<div class="image-placeholder">
					<canvas id="upload-image-canvas" width="1200" height="1200"></canvas>
				</div>
				<div class="webcam-preview hide"></div>
				<p id="allow-webcam-message" class="hide"><?= __('Please make sure you have pressed the button at the top to allow the browser', 'kasra-eid-greeting'); ?></p>
				<div class="upload_buttons">
					<input name="photo-file" type="file" id="file-select" />
					<button id="take-photo-button"><?= __('take a photo', 'kasra-eid-greeting'); ?></button> &nbsp;
					<button id="upload-photo-button"><?= __('upload a photo', 'kasra-eid-greeting'); ?></button>  
				</div>
			</div>
		</div>

		<br />
		<div class="nav_btns">
			<button style="float:right" class="back-button"><?= sprintf("&lt; %s", __('Back', 'kasra-eid-greeting')); ?></button>
			<button style="float:left" class="next-button hide"><?= sprintf("%s &gt;", __('Next', 'kasra-eid-greeting')); ?></button>
		</div>
		<br />
		<br />
	</div>
	<div class="tabContent" id="step3">
		<div class="heading">
			<h2><?= __('Preview your card', 'kasra-eid-greeting'); ?></h2>
		</div>
		<div class="stack_holder">
			<div class="caption">
				<canvas id="preview-image-canvas" width="1200" height="1200"></canvas>
			</div>

			<div class="image-controls">
				<label for="scale-slider"><?= __('Scale', 'kasra-eid-greeting'); ?></label>
				<input type="range" name="scale-slider" id="scale-slider" step="0.05" min="0.50" max="1.50" />
				<label for="rotate-slider"><?= __('Rotate', 'kasra-eid-greeting'); ?></label>
				<input type="range" name="rotate-slider" id="rotate-slider" min="-18" max="18" />
			</div>
		</div>
		<br />
		<div class="nav_btns">
			<button style="float:left" id="finish-button"><?= sprintf("%s &gt;", __('Next', 'kasra-eid-greeting')); ?></button>
			<button style="float:right" class="back-button"><?= sprintf("&lt; %s", __('Back', 'kasra-eid-greeting')); ?></button>
		</div>
	</div>


	<div class="tabContent" id="step4">
		<div class="heading">
			<h2><?= __('Share your card', 'kasra-eid-greeting'); ?></h2>
		</div>
		<div class="stack_holder">

			<img id="share-card-preview" />


			<div class="upload_buttons">
				<button id="share-card-button"><?= __('Share', 'kasra-eid-greeting'); ?></button>
			</div>
		</div>
		<br />



	</div>

	<br />
	<div style="clear:both"></div>
	<!-- <hr />
	<div class="footer"> <img src="<?= $image_path . 'kasra_footer.png'; ?>" /> </div> -->
</div>
