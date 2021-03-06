<?php
namespace Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Css_Filter;

/**
 * Sina_Ext_Controls Class for extends controls
 *
 * @since 3.0.1
 */
class Sina_Ext_Controls{
	/**
	 * Instance
	 *
	 * @since 3.1.13
	 * @var Sina_Ext_Controls The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.1.13
	 * @return Sina_Ext_Controls An Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->controls_files();

		add_action('elementor/controls/controls_registered', [$this, 'controls'], 15 );
		add_action('elementor/element/common/_section_style/before_section_end', [$this, 'register_controls']);
	}

	private function controls_files(){
		require_once SINA_EXT_INC .'controls/icon.php';
		require_once SINA_EXT_INC .'controls/gradient-text.php';
	}

	public function controls( $manager ) {
		$manager->unregister_control( $manager::ICON );
		$manager->register_control( $manager::ICON, new \Sina_Extension\Sina_Ext_Icon());
		$manager->add_group_control( Sina_Ext_Gradient_Text::get_type(), new \Sina_Extension\Sina_Ext_Gradient_Text());
	}

	public function register_controls($elems) {
		$elems->add_control(
			'sina_transform_effects',
			[
				'label' => '<strong>'.__( 'Sina Transform Effects', 'sina-ext' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'translate' => __( 'Translate', 'sina-ext' ),
					'scaleX' => __( 'Scale X', 'sina-ext' ),
					'scaleY' => __( 'Scale Y', 'sina-ext' ),
					'scaleZ' => __( 'Scale Z', 'sina-ext' ),
					'rotateX' => __( 'Rotate X', 'sina-ext' ),
					'rotateY' => __( 'Rotate Y', 'sina-ext' ),
					'rotateZ' => __( 'Rotate Z', 'sina-ext' ),
					'skewX' => __( 'Skew X', 'sina-ext' ),
					'skewY' => __( 'Skew Y', 'sina-ext' ),
					'none' => __( 'None', 'sina-ext' ),
				],
				'separator' => 'before',
				'default' => 'none',
			]
		);

		$elems->start_controls_tabs( 'sina_transform_effects_tabs' );

		$elems->start_controls_tab(
			'sina_transform_effects_normal',
			[
				'label' => __( 'Normal', 'sina-ext' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX',
			[
				'label' => __( 'Translate X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY',
			[
				'label' => __( 'Translate Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX.SIZE || 0}}px, {{sina_transform_effects_translateY.SIZE || 0}}px);',
					'(tablet){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX',
			[
				'label' => __( 'Scale X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY',
			[
				'label' => __( 'Scale Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ',
			[
				'label' => __( 'Scale Z', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX',
			[
				'label' => __( 'Rotate X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY',
			[
				'label' => __( 'Rotate Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ',
			[
				'label' => __( 'Rotate Z', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX',
			[
				'label' => __( 'Skew X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY',
			[
				'label' => __( 'Skew Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters',
				'selector' => '{{WRAPPER}}',
			]
		);

		$elems->end_controls_tab();

		$elems->start_controls_tab(
			'sina_transform_effects_hover',
			[
				'label' => __( 'Hover', 'sina-ext' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX_hover',
			[
				'label' => __( 'Translate X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY_hover',
			[
				'label' => __( 'Translate Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '-10',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover.SIZE || 0}}px, {{sina_transform_effects_translateY_hover.SIZE || 0}}px);',
					'(tablet){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX_hover',
			[
				'label' => __( 'Scale X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY_hover',
			[
				'label' => __( 'Scale Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ_hover',
			[
				'label' => __( 'Scale Z', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX_hover',
			[
				'label' => __( 'Rotate X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY_hover',
			[
				'label' => __( 'Rotate Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ_hover',
			[
				'label' => __( 'Rotate Z', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX_hover',
			[
				'label' => __( 'Skew X', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '10',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY_hover',
			[
				'label' => __( 'Skew Y', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters_hover',
				'selector' => '{{WRAPPER}}:hover',
			]
		);
		$elems->add_control(
			'sina_transform_effects_duration',
			[
				'label' => __( 'Transition Duration', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 100,
						'min' => 0,
						'max' => 10000,
					],
				],
				'default' => [
					'size' => '400',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transition: all {{SIZE}}ms;',
				],
			]
		);

		$elems->end_controls_tab();

		$elems->end_controls_tabs();
	}
}