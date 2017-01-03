<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $pinnacle, $postcolumn; 
	
	$blog_title = $pinnacle['blog_title'];
	if(isset($pinnacle['pinnacle_animate_in']) && $pinnacle['pinnacle_animate_in'] == 1) {
		$animate = 1;
	} else {
		$animate = 0;
	} 
	if(isset($pinnacle['blog_grid_display_height']) && $pinnacle['blog_grid_display_height'] == 1) {
		$matchheight = 1;
	} else {
		$matchheight = 0;
	}
	if(!empty($blog_title)) {
		$btitle = $blog_title; 
	} else { 
		$btitle = __('Latest from the Blog', 'pinnacle'); 
	}
	if(isset($pinnacle['home_post_count'])) {
		$blogcount = $pinnacle['home_post_count'];
	} else { 
		$blogcount = '3';
	} 
	if(isset($pinnacle['home_post_column'])) {
		$blog_grid_column = $pinnacle['home_post_column'];
	} else {
		$blog_grid_column ="3";
	}
	if ($blog_grid_column == '2') {
		$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; 
		$postcolumn = '2';
	} else if ($blog_grid_column == '3'){
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
		$postcolumn = '3';
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
		$postcolumn = '4';
	}
 	if(empty($pinnacle['home_post_type'])){
		$blog_cats = get_categories();
	}
	else{
		$blog_cats = $pinnacle['home_post_type'];
	}
					 ?>
<div class="home_blog home-margin clearfix home-padding">
	<div class="clearfix">
		<h3 class="hometitle">
			<?php echo $btitle; ?>		
		</h3>
	</div>
	<div id="kad-blog-grid-home" class="row clearfix init-isotope kad-blog-grid home-latest-posts" data-fade-in="<?php echo esc_attr($animate);?>"  data-iso-selector=".b_item" data-iso-style="masonry" data-iso-filter="false" data-iso-match-height="<?php echo esc_attr($matchheight);?>">
		<?php 
			$temp = $wp_query; 
			$wp_query = null; 
			$wp_query = new WP_Query();
				/*Customizing AGY 28.12.16 - exclude instead of only include- BEGIN*/
			$wp_query->query(array(
				'posts_per_page' => $blogcount,
				'category__in'=> $blog_cats
				)
			);
				/*Customizing AGY 28.12.16 END*/
			$xyz = 0;
				if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="<?php echo esc_attr($itemsize);?> b_item kad_blog_item">
						<?php get_template_part('templates/content', 'post-grid');?>
					</div>
                <?php endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'pinnacle');?></li>
				<?php endif; 
                
				
				$wp_query = null; 
				$wp_query = $temp;  // Reset 
				wp_reset_query(); 

				?>
	</div>
</div> <!--home-blog -->
