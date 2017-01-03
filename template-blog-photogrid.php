<?php
/*
Template Name: Blog PhotoGrid
*/

	get_header(); 

	get_template_part('templates/page', 'header'); 

	global $post, $pinnacle, $postcolumn, $blog_fullwidth;
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

   			$blog_grid_column 		= get_post_meta( $post->ID, '_kad_blog_columns', true );
   			$blog_grid_fullwidth 	= get_post_meta( $post->ID, '_kad_blog_photofullwidth', true );
			/*AGY 03.10.16 BEGIN CHANGE: multiple categories selectable, removed old single category variable*/
			$blog_categories = get_post_meta($post->ID, '_kad_text_taxonomy_multicheck', true );
			/*AGY 03.10.16 END CHANGE: multiple categories selectable*/
   			$blog_items 			= get_post_meta( $post->ID, '_kad_blog_items', true ); 
   			if(!kadence_display_sidebar() && $blog_grid_fullwidth == 'yes') {
				$blog_fullwidth = true;
   			} else {
   				$blog_fullwidth = false;
   			}
   			if ($blog_fullwidth) { 
	   			if ($blog_grid_column == '2') {
	   				$itemsize = 'tcol-sxl-3 tcol-xl-4 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';
	   				$postcolumn = '2';
	   			} else if ($blog_grid_column == '3'){
			    	$itemsize = 'tcol-sxl-25 tcol-xl-3 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';
			    	$postcolumn = '3';
			    } else {
			    	$itemsize = 'tcol-sxl-2 tcol-xl-25 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';
			    	$postcolumn = '4';
			   	}
			    $contentclass = "kad-fullwidth";
			} else {
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
			    $contentclass = "";
		    } 
			

			if($blog_items == 'all') {
				$blog_items = '-1';
			} 
			?>
	<div id="content" class="container <?php echo esc_attr($contentclass);?>">
   	  <div class="row">
      	<div class="main <?php echo kadence_main_class();?>" role="main">
      		<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
      		</div>
      		<div id="kad-blog-photo-grid" class="rowtight init-isotope-intrinsic <?php echo esc_attr($scrollclass); ?>" <?php echo $infinit; ?> data-fade-in="<?php echo esc_attr($animate);?>"  data-iso-selector=".b_item" data-iso-style="masonry" data-iso-filter="false">
      		<?php   
      			$temp = $wp_query; 
				$wp_query = null; 
				$wp_query = new WP_Query();
				$wp_query->query(array(
					'paged' => $paged,
					'category__in' =>$blog_categories,
					'posts_per_page' => $blog_items
					)
				);
				if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
				?>
				<div class="<?php echo esc_attr($itemsize);?> b_item kad_blog_item">
                    <?php get_template_part('templates/content', 'post-photo-grid');?>
				</div>
				<?php 
            endwhile; else: ?>
				<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'pinnacle'); ?></li>
			<?php endif; ?>
                

                </div> <!-- Blog Grid -->
				<?php 
					if ($wp_query->max_num_pages > 1) :
        			 	kad_wp_pagenavi();  
					endif; 
				$wp_query = null; 
				$wp_query = $temp;  // Reset 
				wp_reset_query(); 

				do_action('kadence_page_footer'); ?>
			</div><!-- /.main -->
  <?php get_footer(); ?>