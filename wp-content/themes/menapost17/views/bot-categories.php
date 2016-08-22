<?php foreach ($params as $key => $param):
 $q = $param[2]((get_query_var('paged')) ? get_query_var('paged') : 1,$param[0]);
  if ($q->have_posts()) :  
    while ($q->have_posts()): $q->the_post(); ?>
            <?php get_template_part('views/'.$param[1]);?>
        <?php 
    endwhile; 
endif;
endforeach; ?>