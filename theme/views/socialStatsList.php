<div class="articleStat">
    <a href="<?php the_permalink();?>" class="reportLink"><?php the_title();?></a>
    <span><span>Rank: </span><strong><?php echo $post->rank ?></strong></span>
    <span><span>Shares: </span><strong><?php echo $post->allshares ?></strong></span>
    <span><span>Views: </span><strong><?php echo $post->allviews ?></strong></span>
    <span><span>Date: </span><strong><?php the_date();?></strong></span>
</div>