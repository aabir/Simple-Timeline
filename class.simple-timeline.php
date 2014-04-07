<?php

final class Simple_Timeline_Plugin {

	// This is the main class to init. 	
	
	public function __construct(){
		$plugin_name = 'simple_timeline_plugin';
		
		add_action( 'init', array( __CLASS__, "simple_timeline_cpt" ) );
		
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'timeline_column' ), 10, 2 ); // Then register column
		add_filter( 'manage_edit-simple_timeline_columns', array( __CLASS__, 'timeline_columns' ), 5 ); //First give CPT name
		
		add_action("add_meta_boxes", array( __CLASS__, 'simple_timeline_add_custom_box' ));
		add_action("save_post", array( __CLASS__, 'simple_timeline_save_postdata' ));
		
	}
	
	public static function install () {
		update_option( 'ctimeline_activated', time() );
	}
	
	
	public static function timeline_columns ( $columns ) {

		unset( $columns['date'] );
		$columns['position'] = 'Position';
		$columns['company'] = 'Company';
		$columns['timeline_thumbnail'] = 'Thumbnail';
		$columns['date'] = 'Date';

		return $columns;

	}
	
	public static function timeline_column ( $column, $post_id ) {

		global $post;
		
		if( $post->post_type != 'simple_timeline' )
			return;

		switch( $column ) {

			case 'timeline_thumbnail':

				if( has_post_thumbnail( $post->ID ) )
					echo wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), array( 64, 64 ) );
				else
					echo 'No thumbnail supplied';

				break;
				
			case 'position':	
				$position = get_post_meta($post->ID, '_work_position', true);
				//echo $position == '' ? '<em>N/A</em>' : $position;
				echo $position;
				break;
			
			case 'company':	
				$company = get_post_meta($post->ID, '_work_company', true);	
				//echo $company = '' ? '<em>N/A</em>' : $company;
				echo $company;
				break;

			default:

				/*$value = get_post_meta( $post->ID, $column, true );
				echo $value == '' ? '<em>N/A</em>' : $value;*/
		}
		
	}

	public function simple_timeline_cpt() {
		
		$labels = array(
			'name'               => _x( 'Simple Timeline', 'post type general name' ),
			'singular_name'      => _x( 'Simple Timeline', 'post type singular name' ),
			'add_new'            => _x( 'Add New Timeline', 'book' ),
			'add_new_item'       => __( 'Add New Timeline' ),
			'edit_item'          => __( 'Edit Timeline' ),
			'new_item'           => __( 'New Timeline' ),
			'all_items'          => __( 'All Timeline' ),
			'view_item'          => __( 'View Timeline' ),
			'search_items'       => __( 'Search Timeline' ),
			'not_found'          => __( 'Not Found' ),
			'not_found_in_trash' => __( 'Not Found Timeline in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Timeline'
		);
		
		$args = array(
			'labels' => $labels,
			'description'   => 'Holds Simple Timeline and Simple Timeline specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail'),
			'has_archive'   => true
		);
		register_post_type( 'simple_timeline', $args );	
	}
	
	public function simple_timeline_add_custom_box(){
		global $plugin_name;
		add_meta_box( 
			"options",
			__("Simple Timeline Data", $plugin_name),
			array( __CLASS__, 'simple_timeline_theme_inner_custom_box' ),
			"simple_timeline",
			"normal",
			"high"
		);
	}
	
	public function simple_timeline_theme_inner_custom_box($post){
	
		wp_nonce_field(plugin_basename( __FILE__ ), $plugin_name . "_noncename");
	
		echo '
		<table>
		<tr>
			<td>
				<label for="work_year">' . __('Year', $plugin_name) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="work_year" name="work_year" value="' . esc_attr(get_post_meta($post->ID, $plugin_name . "_work_year", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="work_position">' . __('Position', $plugin_name) . ':</label>
			</td>
			<td>
				<input class="regular-text" type="text" id="work_position" name="work_position" value="' . esc_attr(get_post_meta($post->ID, $themename . "_work_position", true)) . '" />
			</td>
		</tr>
		<tr>
			<td>
				<label for ="work_company">' .__('Work Company', $plugin_name) . ':</label> 
			</td>
			<td>
				<input class="regular-text" type="text" id="work_company" name="work_company" value="' . esc_attr(get_post_meta($post->ID, $plugin_name . "_work_company", true)) . '" />
			</td>
			
		</tr>
		<tr>
			<td>
				<label for="work_preview_description">' . __('Work Preview Short Description (Display 100 characters)', $plugin_name) . ':</label>
			</td>
			<td>
				<textarea rows="4" cols="50" id="work_preview_description" name="work_preview_description">' . esc_attr(get_post_meta($post->ID, $plugin_name . "_work_preview_description", true)) . '</textarea>
			</td>
		</tr>';
		
		echo '</table>';
	}
	
	
	public function simple_timeline_save_postdata($post_id){
		global $plugin_name;
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return;
	
		if (!wp_verify_nonce($_POST[$plugin_name . '_noncename'], plugin_basename( __FILE__ )))
			return;
	
		if(!current_user_can('edit_post', $post_id))
			return;
			
		update_post_meta($post_id, $plugin_name . "_work_year", $_POST["work_year"]);
		update_post_meta($post_id, $plugin_name . "_work_position", $_POST["work_position"]);
		update_post_meta($post_id, $plugin_name . "_work_company", $_POST["work_company"]);
		update_post_meta($post_id, $plugin_name . "_work_preview_description", $_POST["work_preview_description"]);
	}	
}
?>