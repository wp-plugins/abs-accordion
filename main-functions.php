<?php

/*
Plugin Name: ABS Accordion
Plugin URI: http://www.absiddik.net/demo/wp/plugin/abs-accordion
Description: This plugin will enable accordion in your wordpress theme. You can embed accordion via shortcode in everywhere you want, even in theme files. 
Author: AB Siddik
Version: 1.6
Author URI: http://wexteam.com
*/



/*------------Add Setting function------------*/

require_once dirname( __FILE__ ) . '/class.settings-api.php';
require_once dirname( __FILE__ ) . '/acc-settings-filed.php';

new Abs_Acc_Settings_API_Test();



/*------------trigger setting api class------------*/

function abs_acc_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}



/*------------Latest Jquery For ABS Accordion Plugin------------*/

function abs_accordion_latest_jquery() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'init', 'abs_accordion_latest_jquery' );



/*------------Main Jquery and Style for ABS Accordion Plugin------------*/

function abs_faq_main_jquery() {
	wp_enqueue_script( 'abs-accordion-js', plugins_url( '/js/paper-collapse.min.js', __FILE__ ), array('jquery'), 1.0, false);
	wp_enqueue_script( 'abs-accordion-active-js', plugins_url( '/js/active.js', __FILE__ ), array('jquery'), 1.5, false);

	wp_enqueue_style( 'abs-accordion-css', plugins_url( '/css/paper-collapse.css', __FILE__ ));
	
	wp_enqueue_style( 'prefix-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.3.0' );
}
add_action( 'init', 'abs_faq_main_jquery' );



/*-----------Add style in header for settings options--------*/
function style_for_setting_options(){
?>
<style type="text/css">
		.collapse-card__close_handler{
			display:<?php echo abs_acc_get_option( 'extra_collapse_button', 'abs_acc_basics', 'none' );?>
		}
		.collapse-card__title{
			background-color:<?php echo abs_acc_get_option( 'title_bg_color', 'abs_acc_styles', '#c1c1c1' );?>;
			color:<?php echo abs_acc_get_option( 'title_color', 'abs_acc_styles', '#777777' );?> ;
		}
		.collapse-card{
			border-color:<?php echo abs_acc_get_option( 'border_color', 'abs_acc_styles', '#eeeeee' );?> ;
		}
		.collapse-card__title{
				font-size: <?php echo abs_acc_get_option( 'title_font_size', 'abs_acc_styles', '18' );?>px;
		}
		.collapse-card__body p{
			color:<?php echo abs_acc_get_option( 'content_color', 'abs_acc_styles', '#999999' );?>;
			font-size:<?php echo abs_acc_get_option( 'content_font_size', 'abs_acc_styles', '15' );?>px;
		}
	</style>
<?php
}

add_action('wp_head','style_for_setting_options');

/*-----------Add script in footer for settings options--------*/
function script_for_setting_options(){
?>
<script type="text/javascript">
	jQuery(function () {
		jQuery(".collapse-card").paperCollapse({
			animationDuration: <?php echo abs_acc_get_option( 'animation_duration', 'abs_acc_basics', '400' );?>,
			
		})
	})
</script>
<?php
}

add_action('wp_footer','script_for_setting_options');

/*------------This sortcode use for ABS Accordion------------*/

function abs_accordion_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '',
	), $atts, 'category_post' ) );
	
    $q = new WP_Query(
        array( 
			'acc_cat' => $category,
			'posts_per_page' => -1,
			'post_type' => 'acc-items',
			'order' => abs_acc_get_option( 'accordion_order', 'abs_acc_basics', 'ASC' ),
		)
    );
	$list = '<div class="accordion-box">';

	while($q->have_posts()) : $q->the_post();
		//get the ID of your post in the loop
		$idd = get_the_ID();
		
		global $post;
		$accordion_icon = get_post_meta($idd, '_absacc_accordion_icon', true);
			
			$list .= '
					<div class="collapse-card">
						<div class="collapse-card__heading" >
							<div class="collapse-card__title">';
								if($accordion_icon){
									$list .= '<i class="fa '.$accordion_icon.' fa-2x fa-fw"></i>';
								}else{
									$list .= '<i class="fa fa-question-circle fa-2x fa-fw"></i>';
								}
									$list .='<strong>'.get_the_title().'</strong>
							</div>
						</div>
						<div class="collapse-card__body">
							<p>'.get_the_content().'</p>
							<div class="collapse-card__close_handler mt1 align-right mouse-pointer">
								Show less <i class="fa fa-chevron-up"></i>
							</div>
						</div>
					</div>
					
					
					';        
	endwhile;
	$list.= '
	</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('abs_accordion', 'abs_accordion_shortcode');



/*------------ABS accordion shortcode button------------*/

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



/*------------This custom post for ABS Accordion------------*/

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



/*------------This Code for ABS Accordion Custom taxonomy------------*/

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


/*--------------Meta-box settings for ABS accordion----------------*/
add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
function be_initialize_cmb_meta_boxes() {
    if ( !class_exists( 'cmb_Meta_Box' ) ) {
        require_once( 'inc/cmb/init.php' );
    }
}
// Custom metaboxs option
require_once('inc/cmb/cmb-option.php');


?>