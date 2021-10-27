<?php
/**
 * Elementor custom sorter control.
 *
 * sorting various elements in widgets..
 *
 * @since 1.0.0
 */
class Micemade_Control_Sorter extends \Elementor\Base_Data_Control {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type, in this case 'sorter_label'.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'sorter_label';
	}

	/**
	 * Get control default settings.
	 *
	 * Retrieve the default settings of the control. Used to return
	 * the default settings while initializing the control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'default'     => '',
		];
	}

	/**
	 * Render control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using 'data' JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<input id="<?php echo esc_attr( $control_uid ); ?>" type="hidden" data-setting="{{ data.name }}"  readonly>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

}
