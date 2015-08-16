<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('Abs_Acc_Settings_API_Test' ) ):
class Abs_Acc_Settings_API_Test {

    private $settings_api;

    function __construct() {
        $this->settings_api = new Abs_Acc_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'ABS Accordion Settings', 'ABS Accordion Settings', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'abs_acc_basics',
                'title' => __( 'Basic Settings', 'wedevs' )
            ),
            array(
                'id' => 'abs_acc_styles',
                'title' => __( 'Style Settings', 'wedevs' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'abs_acc_basics' => array(
                array(
                    'name'    => 'accordion_order',
                    'label'   => __( 'Accordion showing order', 'wedevs' ),
                    'desc'    => __( 'Tell the plugin your accordion need show ascending order or distending order. Default Ascending Order.', 'wedevs' ),
                    'type'    => 'radio',
					'default' => 'ASC',
                    'options' => array(
                        'ASC' => 'Ascending Order',
                        'DISC'  => 'Distending Order'
                    )
                ),
                array(
                    'name'    => 'extra_collapse_button',
                    'label'   => __( 'Extra Hide Show Button ', 'wedevs' ),
                    'desc'    => __( 'Tell the plugin your accordion need exert show or hide button . Default Hide.', 'wedevs' ),
					'default' => 'none',
                    'type'    => 'radio',
                    'options' => array(
                        'none' => 'Hide',
                        'block'  => 'Show'
                    )
                ),
				array(
					'name' => 'animation_duration',
					'label' => __( 'Accordion Animation Duration', 'wedevs' ),
					'desc' => __( 'Tell the accordion animation duration in millisecond. For best result use 300 to 500 millisecond. Default value 400.', 'wedevs' ),
					'type' => 'number',
					'default' => '400'
				)
            ),
            'abs_acc_styles' => array(
                array(
                    'name'    => 'title_bg_color',
                    'label'   => __( 'Title Background Color ', 'wedevs' ),
                    'desc'    => __( 'Select a color for accordion title background color. Default #c1c1c1', 'wedevs' ),
                    'type'    => 'color',
                    'default' => '#c1c1c1'
                ),
                array(
                    'name'    => 'title_color',
                    'label'   => __( 'Title Color ', 'wedevs' ),
                    'desc'    => __( 'Select a color for accordion title color. Default #777777', 'wedevs' ),
                    'type'    => 'color',
                    'default' => '#777777'
                ),
                array(
                    'name'    => 'border_color',
                    'label'   => __( 'Border Color ', 'wedevs' ),
                    'desc'    => __( 'Select a color for accordion border color. Default #eeeeee', 'wedevs' ),
                    'type'    => 'color',
                    'default' => '#eeeeee'
                ),
				array(
					'name' => 'title_font_size',
					'label' => __( 'Title Font Size', 'wedevs' ),
					'desc' => __( 'Tell the accordion title font size in pixel. For best result use 16 to 20 pixel. Default value 18.', 'wedevs' ),
					'type' => 'number',
					'default' => '18'
				),
                array(
                    'name'    => 'content_color',
                    'label'   => __( 'Content Color ', 'wedevs' ),
                    'desc'    => __( 'Select a color for accordion content color. Default #999999', 'wedevs' ),
                    'type'    => 'color',
                    'default' => '#999999'
                ),
				array(
					'name' => 'content_font_size',
					'label' => __( 'Content Font Size', 'wedevs' ),
					'desc' => __( 'Tell the accordion content font size in pixel. For best result use 13 to 17 pixel. Default value 15.', 'wedevs' ),
					'type' => 'number',
					'default' => '15'
				)
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
