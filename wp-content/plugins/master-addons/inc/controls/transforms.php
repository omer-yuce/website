<?php
namespace MasterAddons\Inc\Controls;
use Elementor\Control_Base_Multiple;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; };

/**
 * Elementor Transforms Multiple Control
 */
class MA_Control_Transforms extends Control_Base_Multiple {

	public function get_type() {
		return 'transforms';
	}

	public function enqueue() {
		wp_enqueue_script( 'master-addons-editor', MELA_ADMIN_ASSETS . 'js/editor.js', array( 'jquery' ), MELA_VERSION, true );
	}
	
	//Get Controls default value
	public function get_default_value() {
		return parent::get_default_value();
	}

	protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}
	/**
	 * Get Sliders
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'angle' => [
				'label' => __( 'Angle', MELA_TD ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'rotate_x' => [
				'label' => __( 'Rotate X', MELA_TD ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'rotate_y' => [
				'label' => __( 'Rotate Y', MELA_TD ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'translate_x' => [
				'label' => __( 'Translate X', MELA_TD ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'translate_y' => [
				'label' => __( 'Translate Y', MELA_TD ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'translate_z' => [
				'label' => __( 'Translate Z', MELA_TD ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'scale' => [
				'label' => __( 'Scale', MELA_TD ),
				'min' => 0.1,
				'max' => 3,
				'step' => 0.1
			],
			
		];
	}
	/**
	 * Render Sliders Output on the Editor
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title control-title-first">{{{ data.label }}}</label>
			<button href="#" class="reset-controls" title="Reset"><i class="fa fa-close"></i></button>
		</div>
		<?php
		foreach ( $this->get_sliders() as $slider_name => $slider ) :
			$control_uid = $this->get_control_uid( $slider_name );
			?>
			<div class="elementor-control-field elementor-control-type-slider">
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title-transforms"><?php echo $slider['label']; ?></label>
				<div class="elementor-control-input-wrapper">
					<div class="elementor-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
					<div class="elementor-slider-input">
						<input id="<?php echo esc_attr( $control_uid ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" step="<?php echo esc_attr( $slider['step'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php
	}
}