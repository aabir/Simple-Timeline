<?php function timeline_shortcode($atts){ 

if(!is_admin() ){
	wp_enqueue_style( 'simple-timeline', plugin_dir_url( __FILE__ ) . 'css/timeline.css' );
	wp_enqueue_script( 'timeline-js', plugin_dir_url( __FILE__ ). 'js/timeline.js');
}
?>
					
<?php 
	
	function gen_string($string,$min) {
		$text = trim(strip_tags($string));
			if(strlen($text)>$min) {
				$blank = strpos($text,' ');
				if($blank) {
					# limit plus last word
					$extra = strpos(substr($text,$min),' ');
					$max = $min+$extra;
					$r = substr($text,0,$max);
					if(strlen($text)>=$max) $r=trim($r,'.').'...';
				} else {
					# if there are no spaces
					$r = substr($text,0,$min).'...';
				}
					} else {
						# if original length is lower than limit
						$r = $text;
					}
			return $r;
	}

	$args = array(
		'post_type' 		=> 'simple_timeline',
		'post_status' 		=> 'publish',
		'posts_per_page'	=> isset( $atts['per_page'] ) ? $atts['per_page'] : -1,
		'order'				=> 'DESC'
	);
		
	$wp_query = new WP_Query($args);
?>
    	
        <?php $output = '<div id="timelinecontainer">';
		
		//$output .= '<h2 class="time-header"><strong> '.get_the_title().' </strong></h2>';
		
         $i = 0; while ( $wp_query->have_posts() ) : $wp_query->the_post();  $i++; 
		 
         ob_start();
		 	
         $output .= '<div class="work_holder">';
        
         $output .= '<div class="work_description';?> 
		 <?php if($i == 1) $output .=' display';  
         $output.='" id="work'.$i.'">'; ?>
        
         <?php $output .= '<div class="workline top"></div>'; ?>
        	
            <?php $output .= '<div class="featured-img">'; ?>
				<?php if ( has_post_thumbnail() ) {
                       $output .= get_the_post_thumbnail(get_the_ID());
                      } 
                ?>
            <?php $output .= '</div>'; ?>
			
            <?php $output .= '<div class="full-desc">'.nl2br(get_the_content()).' </div>'; ?>
            
          <?php $output .= '<div class="workline bottom"></div>'; ?>
            
        <?php $output .= '</div>
        </div>
		<div class="line"></div>'; ?>
       
        
        <?php $output .= '<div class="year_list'; ?> <?php $output .= '">';?>
        <?php $output .= '<div class="work_year-box">'; ?>
        	
            <?php $output .= '<div class="work_year'; ?> <?php if($i == 1 ) $output .=' year_active';
			$output.='" id="year2work'.$i.'">'; ?>
			
                <?php $output .= '<a href="#" class="click_year" name="work'.$i.'" id="year'.$i.'">'. $year = get_post_meta(get_the_ID(), '_work_year', true).'</a>'; ?>
            <?php $output .= '</div>
        </div>
		    
        <div class="work_list">'; ?>

        	<?php $output .= '<div id="pos'.$i.'">'; ?>
            
            <?php $output .= '<a href="#" class="clickable position'; ?> <?php if($i == 1 ) $output .=' position-active';
			$output.='" name="work'.$i.'" id="yearwork'.$i.'">'.$position = get_post_meta(get_the_ID(), '_work_position', true).'</a>'; ?>
			
            <?php $output .= '</div>'; ?>
            
            
            <?php $output .= '<div class="company">'. $company = get_post_meta(get_the_ID(), '_work_company', true).'</div>'; ?>
            
            <?php $output .= '<div class="preview_desc">'. gen_string($preview_description = get_post_meta(get_the_ID(), '_work_preview_description', true), 100).'</div>
        </div>
        </div>'; ?>
		
        <?php ob_end_clean();
			  endwhile; ?>
        
        <?php echo $output .= '</div>'; ?>
<?php 	
}

add_shortcode("simple_timeline", "timeline_shortcode");
?>