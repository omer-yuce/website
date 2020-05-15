<?php
namespace MasterAddons\Inc\Controls;

use Elementor\Controls_Manager;
use Elementor\Control_Base_Multiple;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }; 


/**
 * Elementor XY control.
 *
 */
class MA_XY_Position extends Control_Base_Multiple {

	public function get_type() {
		return 'xy_positions';
	}

	public function enqueue() {		
		wp_enqueue_script( 'master-addons-editor', MELA_ADMIN_ASSETS . 'js/editor.js', array( 'jquery' ), MELA_VERSION, true );
	}
	
	public function get_default_value() {
		return parent::get_default_value();
	}
	
	protected function get_default_settings() {
		return array_merge(
			parent::get_default_settings(), [
				'label_block' => false,
				'separator' => 'before'
			]
		);
	}
	
	public function get_sliders() {
		return [
			'x' => [
				'label' => __( 'X', MELA_TD ),
				'min' => 0,
				'max' => 100,
				'step' => 1
			],
			'y' => [
				'label' => __( 'Y', MELA_TD ),
				'min' => 0,
				'max' => 100,
				'step' => 1
			],
		];
	}


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
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title-xypositions"><?php echo $slider['label']; ?></label>
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