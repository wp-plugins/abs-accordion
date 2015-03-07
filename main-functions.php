<?php

/*
Plugin Name: ABS Accordion
Plugin URI: http://www.absiddik.net/demo/wp/plugin/abs-accordion
Description: This plugin will enable accordion in your wordpress theme. You can embed accordion via shortcode in everywhere you want, even in theme files. 
Author: AB Siddik
Version: 1.0.3
Author URI: http://absiddik.net
*/


/*
 *Latest Jquery For ABS Accordion Plugin.
 */
function abs_accordion_latest_jquery() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'init', 'abs_accordion_latest_jquery' );

/**
 * Main Jquery and Style for ABS Accordion Plugin,
 */
function abs_faq_main_jquery() {
	wp_enqueue_script( 'abs-accordion-js', plugins_url( '/js/paper-collapse.min.js', __FILE__ ), array('jquery'), 1.0, false);

	wp_enqueue_style( 'abs-accordion-css', plugins_url( '/css/paper-collapse.css', __FILE__ ));
	
	wp_enqueue_style( 'prefix-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.3.0' );
}

add_action( 'init', 'abs_faq_main_jquery' );

/* This sortcode use for latest_news  */
function abs_accordion_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '',
	), $atts, 'category_post' ) );
	
    $q = new WP_Query(
        array( 'acc_cat' => $category, 'posts_per_page' => -1, 'post_type' => 'acc-items')
        );
	$list = '<div class="accordion-box">';

	while($q->have_posts()) : $q->the_post();
		//get the ID of your post in the loop
		$idd = get_the_ID();
		
		global $post;
		$accordion_icon = get_post_meta($idd, 'accordion_icon', true);
			
			$list .= '<div class="collapse-card">
					  <div class="title">';
			if($accordion_icon){
				$list .= '<i class="fa '.$accordion_icon.' fa-2x fa-fw"></i>';
			}else{
				$list .= '<i class="fa fa-question-circle fa-2x fa-fw"></i>';
			}
				$list .='<strong>'.get_the_title().'</strong>
				</div>
				<div class="body">
					<p>'.get_the_content().'</p>
				</div>
				</div>
				';        
	endwhile;
	$list.= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('abs_accordion', 'abs_accordion_shortcode');


/* ABS accordion shortcode button*/
function abs_accordion_buttons() {
	add_filter ("mce_external_plugins", "my_external_js");
	add_filter ("mce_buttons", "our_awesome_buttons");
}

function my_external_js($plugin_array) {
	$plugin_array['absaccordion'] = plugins_url('js/custom-button.js', __FILE__);
	return $plugin_array;
}

function our_awesome_buttons($buttons) {
	array_push ($buttons, 'abs_accordion');
	return $buttons;
}
add_action ('init', 'abs_accordion_buttons');





/*This custom post for ABS Accordion*/
add_action( 'init', 'abs_accordion_custompost' );

function abs_accordion_custompost() {
	$labels = array(
		'name'               => _x( 'Accordion Item', 'abs-faq-panel' ),
		'singular_name'      => _x( 'Accordion Item',  'abs-faq-panel' ),
		'menu_name'          => _x( 'Accordion Items', 'abs-faq-panel' ),
		'name_admin_bar'     => _x( 'Accordion Item',  'abs-faq-panel' ),
		'add_new'            => _x( 'Add New Accordion', 'abs-faq-panel' ),
		'add_new_item'       => __( 'Add New Accordion', 'abs-faq-panel' ),
		'new_item'           => __( 'New Accordion', 'abs-faq-panel' ),
		'edit_item'          => __( 'Edit Accordion', 'abs-faq-panel' ),
		'view_item'          => __( 'View Accordion', 'abs-faq-panel' ),
		'all_items'          => __( 'All Accordions', 'abs-faq-panel' ),
		'search_items'       => __( 'Search Accordions', 'abs-faq-panel' ),
		'parent_item_colon'  => __( 'Parent Accordions:', 'abs-faq-panel' ),
		'not_found'          => __( 'No Accordions found.', 'abs-faq-panel' ),
		'not_found_in_trash' => __( 'No Accordions found in Trash.', 'abs-faq-panel' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'faq-item' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor')
	);

	register_post_type( 'acc-items', $args );
}

/* ----This Code for Woki Item Custom texonomy------*/
function abs_accordion_custom_post_taxonomy() {
	register_taxonomy(
		'acc_cat',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'acc-items',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => 'Accordion Catagory',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'accordion-cat', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
	);
	
}
add_action( 'init', 'abs_accordion_custom_post_taxonomy'); 




/**
 * Custom mata box for ABS Accordion
 */
function abs_accordion_add_meta_box() {

	$screens = array( 'acc-items' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'abs_accordion_meta_box_id',
			__( 'Accordion Icon', 'abs_accordion_plugin_textdomain' ),
			'abs_accordion_plugin_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'abs_accordion_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function abs_accordion_plugin_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'abs_accordion_plugin_meta_box', 'abs_accordion_plugin_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$accordion_icon = get_post_meta($idd, 'accordion_icon', true);
	
	echo '<label for="abs_accordion_new_field">';
	_e( 'Put the icon code', 'abs_accordion_plugin_textdomain' );
	echo '</label> ';
	
	echo '<input type="text" id="abs_accordion_new_field" name="abs_accordion_new_field" value="' . esc_attr( $accordion_icon ) . '" size="25" />';
	
	echo'<br/>';

	echo '<label for="abs_accordion_new_field">';
	_e( '<b style="color:green;">HELP</b><br/>You can get icon code list from <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome icon service</a> <br/> You can see video tutorial how to use icon in ABS Accordion item from <a href="http://youtu.be/UWrgV711Vyk" target="_blank">Here</a>', 'abs_accordion_plugin_textdomain' );
	echo '</label> ';
	
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function abs_accordion_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['abs_accordion_plugin_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['abs_accordion_plugin_meta_box_nonce'], 'abs_accordion_plugin_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['abs_accordion_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['abs_accordion_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'accordion_icon', $my_data );
}
add_action( 'save_post', 'abs_accordion_save_meta_box_data' );

?>