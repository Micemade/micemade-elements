<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Micemade_CF7_Forms extends Widget_Base {

	public function get_name() {
		return 'micemade-cf7-forms';
	}

	public function get_title() {
		return __( 'Micemade Contact Form 7 Forms', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-mail';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Contact Form 7', 'micemade-elements' ),   //section name for controler view
			]
		);

		$this->add_control(
			'cf7_slug',
			[
				'label'       => esc_html__( 'Select Contact Form', 'micemade-elements' ),
				'description' => esc_html__( 'Contact form 7 - plugin must be installed and there must be some contact forms made with the contact form 7', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => apply_filters( 'micemade_posts_array', 'wpcf7_contact_form' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings();
		$cf7_slug = $settings['cf7_slug'];

		if ( ! empty( $cf7_slug ) ) {

			if ( $post = get_page_by_path( $cf7_slug, OBJECT, 'wpcf7_contact_form' ) ) {
				$id = $post->ID;
			} else {
				$id = 0;
			}

			echo'<div class="elementor-shortcode">';

				echo do_shortcode( '[contact-form-7 id="' . $id . '"]' );

			echo '</div>';
		}

	}
}
