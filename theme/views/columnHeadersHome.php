<?php // TODO: different markup for tabs and for column headers, because they have different functionality. ?>

<div id="tabs" class="clearfix" data-role="tabbed-columns">
	<nav class="tabNav clearfix">
		<div class="col-title tabs kasraMostShared" data-role="tab" data-panel="#most-shared">
			<h1 href="#most-shared" title="الكثير من قرائنا شاركوا هذه المواضيع مع أصدقائهم" class="tabTitle">شاركوها</h1>
		</div>

		<div class="gutter gutterTablet"></div>

		<div class="col-title tabs kasraMostViewed active" data-role="tab" data-panel="#most-viewed">
			<div class="col-emblem"><i class="icon-kasra-emblem"></i></div>
			<div class="triangle-bottom"></div>
				<h1 href="#most-viewed" title="كن أول من يشارك هذه المواضيع مع أصدقائه" class="tabTitle">ينكسر الان</h1>
		</div>

		<div class="gutter gutterDesktop"></div>

		<div class="col-title tabs kasraNewest" data-role="tab" data-panel="#newest">
			<h1 href="#newest" title="آخر المواضيع على كسرة" class="tabTitle">جديد</h1>
		</div>
	</nav>
