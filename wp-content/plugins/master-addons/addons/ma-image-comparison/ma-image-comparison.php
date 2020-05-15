<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;



use MasterAddons\Inc\Helper\Master_Addons_Helper;

/**
 * Author Name: Liton Arefin
 * Author URL: https://jeweltheme.com
 * Date: 1/2/20
 */

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Master_Addons_Image_Comparison extends Widget_Base {

	public function get_name() {
		return 'ma-el-image-comparison';
	}

	public function get_title() {
		return esc_html__( 'Image Comparison', MELA_TD );
	}

	public function get_icon() {
		return 'ma-el-icon eicon-image-before-after';
	}

	public function get_categories() {
		return [ 'master-addons' ];
	}


	protected function _register_controls() {

		$this->start_controls_section(
			'ma_el_image_comparison_section_start',
			[
				'label' => esc_html__( 'Images', MELA_TD )
			]
		);

		$this->add_control(
			'ma_el_before_image',
			array(
				'label'      => __('Before image',MELA_TD ),
				'type'       => Controls_Manager::MEDIA,
				'show_label' => false
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'       => 'before', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude'    => array( 'custom' ),
				'separator'  => 'none',
				'default'    => 'large',
			)
		);


		$this->add_control(
			'ma_el_after_image',
			array(
				'label'      => __('After image',MELA_TD ),
				'type'       => Controls_Manager::MEDIA,
				'show_label' => false
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'       => 'after', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude'    => array( 'custom' ),
				'separator'  => 'none',
				'default'    => 'large',
			)
		);
		$this->end_controls_section();



		/*-----------------------------------------------------------------------------------*/
		/*  style_section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', MELA_TD ),
				'tab'   => Controls_Manager::TAB_STYLE
			)
		);

		$this->add_control(
			'default_offset',
			array(
				'label'       => __( 'Start offset',MELA_TD ),
				'description' => __( 'How much of the before image is visible when the page loads.', MELA_TD ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array('px'),
				'default'     => array(
					'size' => 50
				),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1
					)
				)
			)
		);

		$this->add_control(
			'width',
			array(
				'label'      => __('Width',MELA_TD ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1400,
						'step' => 1,
					)
				),
				'selectors'  => array(
					'{{WRAPPER}} .ma-el-image-comparison' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'height',
			array(
				'label'      => __('Height',MELA_TD ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1400,
						'step' => 1,
					)
				),
				'selectors'  => array(
					'{{WRAPPER}} .ma-el-image-comparison' => 'max-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$before_image = '';
		$after_image  = '';

		if( ! empty( $settings['ma_el_before_image'] ) ) {
			$before_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'before', 'ma_el_before_image' );
		}

		if( ! empty( $settings['ma_el_after_image'] ) ) {
			$after_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'after', 'ma_el_after_image' );
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'       => [ 'ma-el-image-comparison' ],
				'data-offset' => ( (int) $settings['default_offset']['size'] ) / 100
			]
		);

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'wrapper', [
				'class' => 'elementor-clickable',
			] );
		}

		if( ! empty( $settings['ma_el_after_image'] ) ) {
			echo sprintf( '<div class="widget-container ma-el-widget-image-comparison"><div %s >%s %s</div></div>',
				$this->get_render_attribute_string( 'wrapper' ),
				$before_image,
				$after_image
			) ;
		} else {
			echo sprintf( '<div class="widget-container ma-el-widget-image-comparison"><div %s >%s</div></div>',
				$this->get_render_attribute_string( 'wrapper' ),
				$before_image
			);
		}

	}

	protected function _content_template() {
		?>
		<#

		var images = '';

		if ( settings.ma_el_before_image ) {
			var before_image = {
				id: settings.ma_el_before_image.id,
				url: settings.ma_el_before_image.url,
				size: settings.before_size,
				dimension: settings.before_custom_dimension,
				model: view.getEditModel()
			};
			var before_image_url = elementor.imagesManager.getImageUrl( before_image );
			images += '<img src="' + before_image_url + '" />';
		}

		if ( settings.ma_el_after_image ) {
			var after_image = {
				id: settings.ma_el_after_image.id,
				url: settings.ma_el_after_image.url,
				size: settings.after_size,
				dimension: settings.after_custom_dimension,
				model: view.getEditModel()
			};
			var after_image_url = elementor.imagesManager.getImageUrl( after_image );
			images += '<img src="' + after_image_url + '" />';
		}

		view.addRenderAttribute(
			'wrapper',
			{
				'class'      : [ 'ma-el-image-comparison' ],
				'data-offset': Number( settings.default_offset.size )/100
			}
		);

		#>
			<div class="widget-container ma-el-widget-image-comparison">
				<div {{{  view.getRenderAttributeString( 'wrapper' ) }}}>
					{{{ images }}}
				<div>
			</div>
		<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Image_Comparison() );