<?php
/*
Template Name: Blog
*/

 global $post, $pinnacle;

	get_header(); 

	get_template_part('templates/page', 'header'); ?>
	
    <div id="content" class="container">
   		<div class="row">
   			<?php if(kadence_display_sidebar()) {
   				$display_sidebar = true; 
   				$fullclass = '';
   			} else {
   				$display_sidebar = false; 
   				$fullclass = 'fullwidth';
   			}
   			if(get_post_meta( $post->ID, '_kad_blog_summery', true ) == 'full') {
   				$summery = 'full'; 
   				$postclass = "single-article fullpost";
   			} else {
   				$summery = 'normal'; 
   				$postclass = 'postlist';
   			}
   			if(isset($pinnacle['blog_infinitescroll']) && $pinnacle['blog_infinitescroll'] == 1) {
		        $infinit = 'data-nextselector=".wp-pagenavi a.next" data-navselector=".wp-pagenavi" data-itemselector=".post" data-itemloadselector=".kad-animation" data-infiniteloader="'.get_template_directory_uri() . '/assets/img/loader.gif"';
         		$scrollclass = 'init-infinit-norm';
		    } else {
		        $infinit = '';
		        $scrollclass = '';
		    }
			/*AGY 03.10.16 BEGIN CHANGE: multiple categories selectable, removed old single category variable*/
			$blog_categories = get_post_meta($post->ID, '_kad_text_taxonomy_multicheck', true );
			/*AGY 03.10.16 END CHANGE: multiple categories selectable*/
			$blog_items = get_post_meta( $post->ID, '_kad_blog_items', true ); 
			if($blog_items == 'all') {$blog_items = '-1';} 

   			?>
    <div class="main <?php echo kadence_main_class();?> <?php echo esc_attr($postclass) .' '. esc_attr($fullclass); ?>" role="main">
      	<div class="entry-content" itemprop="mainContentOfPage">
					<?php get_template_part('templates/content', 'page'); ?>
		</div>
		<div class="kt_blog_archive <?php echo esc_attr($scrollclass);?>" <?php echo $infinit;?>>
      		<?php 
					$temp = $wp_query; 
					$wp_query = null; 
					$wp_query = new WP_Query();
					/*AGY 03.10.16 BEGIN CHANGE: multiple categories selectable*/
					$wp_query->query(array(
						'paged' => $paged,
						'category__in' =>$blog_categories,
						'posts_per_page' => $blog_items));
					/*AGY 03.10.16 END CHANGE: multiple categories selectable*/
					$count =0;
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php if($summery == 'full') {
							if($display_sidebar){
					            global $kt_feat_width; 
					            $kt_feat_width = 848;
					        } else {
					            global $kt_feat_width; 
					            $kt_feat_width = 1170;
					        }
							get_template_part('templates/content', 'fullpost'); 
						} else {
							if($display_sidebar){
								global $kt_post_with_sidebar; 
                				$kt_post_with_sidebar = true;
					        } else {
					            global $kt_feat_width; 
					            $kt_post_with_sidebar = false;
					        }
						 	get_template_part('templates/content', get_post_format()); 
						} 
                    endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'pinnacle'); ?></li>
					<?php endif; 
                
				if ($wp_query->max_num_pages > 1) : 
				 	if(function_exists('kad_wp_pagenavi')) { 
        				kad_wp_pagenavi();    
        		 	} 
        		endif; 

				$wp_query = null; 
				$wp_query = $temp;
				wp_reset_query(); 
		?>
		</div>
		<?php
		do_action('kadence_page_footer'); ?>
	</div><!-- /.main -->
  <?php get_footer(); ?>