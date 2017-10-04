<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_Mailchimp extends Widget_Base {

	public function get_name() {
		return 'micemade-mc4wp-forms';
	}

	public function get_title() {
		return __( 'Micemade MailChimp 4 WP Forms', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-mailchimp';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

    protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'MailChimp for WP', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'mc4wp_slug',
			[
				'label' => esc_html__( 'Select Newsletter Form', 'micemade-elements' ),
                'description' => esc_html__('"MailChimp for WP" - plugin must be installed and there must be some newsletter forms made with the plugin','micemade-elements'),
				'type' => Controls_Manager::SELECT,
				'options' => apply_filters( 'micemade_posts_array' ,'mc4wp-form' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
        
        $settings = $this->get_settings();
        $mc4wp_slug = $settings['mc4wp_slug'];
       
        if( ! empty( $mc4wp_slug ) ) {
            
            if ( $post = get_page_by_path( $mc4wp_slug, OBJECT, 'mc4wp-form' ) )
                $id = $post->ID;
            else
                $id = 0;

            echo'<div class="elementor-shortcode">';
                
                echo do_shortcode('[mc4wp_form id="'. $id .'"]');    
            
            echo '</div>';  
        }

    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Mailchimp() );