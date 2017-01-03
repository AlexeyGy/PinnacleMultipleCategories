<?php
/*
Template Name: Blog Grid
*/
	
	get_header(); 

	get_template_part('templates/page', 'header');


	global $post, $pinnacle, $postcolumn;
	?>
	<div id="content" class="container">
   		<div class="row">
   			<?php
   			if(isset($pinnacle['pinnacle_animate_in']) && $pinnacle['pinnacle_animate_in'] == 1) {
   				$animate = 1;
   			} else {
   				$animate = 0;
   			} 
   			if(isset($pinnacle['blog_infinitescroll']) && $pinnacle['blog_infinitescroll'] == 1) {
		         $infinit = 'data-nextselector=".wp-pagenavi a.next" data-navselector=".wp-pagenavi" data-itemselector=".kad_blog_item" data-itemloadselector=".kad_blog_fade_in" data-infiniteloader="'.get_template_directory_uri() . '/assets/img/loader.gif"';
		         $scrollclass = 'init-infinit';
		    } else {
		        $infinit = '';
		        $scrollclass = '';
		    }
   			if(isset($pinnacle['blog_grid_display_height']) && $pinnacle['blog_grid_display_height'] == 1) {
   				$matchheight = 1;
   			} else {
   				$matchheight = 0;
   			}
			/*AGY 03.10.16 BEGIN CHANGE: multiple categories selectable, removed old single category variable*/
   			$blog_grid_column 	= get_post_meta( $post->ID, '_kad_blog_columns', true );
			$blog_items 		= get_post_meta( $post->ID, '_kad_blog_items', true ); 
			$blog_categories = get_post_meta($post->ID, '_kad_text_taxonomy_multicheck', true );
			/*AGY 03.10.16 END CHANGE: multiple categories selectable*/
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
			if($blog_items == 'all') {
				$blog_items = '-1';
			} 
			?>
      		<div class="main <?php echo kadence_main_class();?>" role="main">
      			<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
				</div>
      			
      			<div id="kad-blog-grid-page" class="rowtight kad-blog-grid init-isotope <?php echo esc_attr($scrollclass); ?>" <?php echo $infinit; ?> data-fade-in="<?php echo esc_attr($animate);?>"  data-iso-selector=".b_item" data-iso-match-height="<?php echo esc_attr($matchheight);?>" data-iso-style="masonry" data-iso-filter="false">
      			<?php   
      				$temp = $wp_query; 
					$wp_query = null; 
					$wp_query = new WP_Query();
					/*AGY 03.10.16 BEGIN CHANGE: multiple categories selectable*/
					$wp_query->query(array(
						'paged' => $paged,
						'category__in' =>$blog_categories,
						'posts_per_page' => $blog_items
						)
					);
					/*AGY 03.10.16 END CHANGE: multiple categories selectable*/
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
					?>
						<div class="<?php echo esc_attr($itemsize);?> b_item kad_blog_item">
							<?php get_template_part('templates/content', 'post-grid');?>
						</div>
					
					<?php 
                    endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'pinnacle'); ?></li>
					<?php endif; ?>
                

                </div> <!-- Blog Grid -->
				<?php 
					if ($wp_query->max_num_pages > 1) : 
						if(function_exists('kad_wp_pagenavi')) { 
        					kad_wp_pagenavi(); 
        				}
					endif; 

				$wp_query = null; 
				$wp_query = $temp;  // Reset 
				wp_reset_query();
		
				do_action('kadence_page_footer'); ?>
			</div><!-- /.main -->
  	
  	<?php get_footer(); ?>
  