<?php

function abs_accordion_sample_metaboxes( $meta_boxes ) {
    $prefix = '_absacc_'; // Prefix for all fields
	
	// mate-box for service 
    $meta_boxes['abs_acc_metabox'] = array(
        'id' => 'abs_acc_metabox',
        'title' => 'Accordion Metabox',
        'pages' => array('acc-items'), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => 'Accordion Icon',
				
                'desc' => '<b style="color:green;">HELP</b><br/>You can get icon code list from <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome icon service</a> <br/> You can see video tutorial how to use icon in ABS Accordion item from <a href="http://youtu.be/UWrgV711Vyk" target="_blank">Here</a>', 'abs_accordion_plugin_textdomain',
				
                'id' => $prefix . 'accordion_icon',
                'type' => 'text'
            )
        ),
    );


    return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'abs_accordion_sample_metaboxes' );























?>