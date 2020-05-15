<?php
namespace PrimeSlider\Modules\Multiscroll\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

use PrimeSlider\Prime_Slider_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Multiscroll extends Widget_Base {

	public function get_name() {
		return 'prime-slider-multiscroll';
	}

	public function get_title() {
		return BDTPS . esc_html__( 'Multiscroll', 'bdthemes-prime-slider' );
	}

	public function get_icon() {
		return 'bdt-widget-icon ps-wi-multiscroll bdt-new';
	}

	public function get_categories() {
		return [ 'prime-slider' ];
	}

	public function get_keywords() {
		return [ 'multiscroll', 'slider', 'fancy', 'slideshow', 'advanced' ];
	}

	public function get_style_depends() {
		return [ 'ps-multiscroll' ];
	}

	public function get_script_depends() {
		return [ 'jquery-multiscroll', 'easings', 'ps-multiscroll' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/UGBnjbp90eA';
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Multiscroll Layout', 'bdthemes-prime-slider' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Item', 'bdthemes-prime-slider' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => esc_html__( 'MultiScroll', 'bdthemes-prime-slider' ),
						'slide_image' => ['url' => BDTPS_ASSETS_URL . 'images/gems-1.png'],
						'left_background_color' => '#ad218d',
						'right_background_color' => '#bb1f98',
					],
					[
						'title' => esc_html__( 'MultiScroll', 'bdthemes-prime-slider' ),
						'slide_image' => ['url' => BDTPS_ASSETS_URL . 'images/gems-2.png'],
						'left_background_color' => '#4287ec',
						'right_background_color' => '#498cef',
					],
					[
						'title' => esc_html__( 'MultiScroll', 'bdthemes-prime-slider' ),
						'slide_image' => ['url' => BDTPS_ASSETS_URL . 'images/gems-3.png'],
						'left_background_color' => '#82007d',
						'right_background_color' => '#8a0c85',
					],
				],
				'fields' => [
					
					[
						'name'        => 'sub_title',
						'label'       => esc_html__( 'Sub Title', 'bdthemes-prime-slider' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'Subtitle Goes Here' , 'bdthemes-prime-slider' ),
						'label_block' => true,
						'dynamic'     => [ 'active' => true ],
					],

					[
						'name'        => 'title',
						'label'       => esc_html__( 'Title', 'bdthemes-prime-slider' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'Slide Title Here' , 'bdthemes-prime-slider' ),
						'label_block' => true,
						'dynamic'     => [ 'active' => true ],
					],
					
					[
						'name'          => 'title_link',
						'label'         => esc_html__( 'Title Link', 'bdthemes-prime-slider' ),
						'type'          => Controls_Manager::URL,
						'default'       => ['url' => ''],
						'show_external' => false,
						'dynamic'       => [ 'active' => true ],
						'condition'     => [
							'title!' => ''
						]
					],

					[
						'name'        => 'description',
						'label'       => esc_html__( 'Description', 'bdthemes-prime-slider' ),
						'type'        => Controls_Manager::TEXTAREA,
						'default'     => esc_html__( 'Lorem ipsum dolor sit amet.' , 'bdthemes-prime-slider' ),
						'label_block' => true,
						'dynamic'     => [ 'active' => true ],
					],

					[
						'name'        => 'slide_button',
						'label'       => esc_html__( 'Button Text', 'bdthemes-prime-slider' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'View Details' , 'bdthemes-prime-slider' ),
						'label_block' => true,
						'dynamic'     => [ 'active' => true ],
					],
					
					[
						'name'          => 'button_link',
						'label'         => esc_html__( 'Button Link', 'bdthemes-prime-slider' ),
						'type'          => Controls_Manager::URL,
						'default'       => ['url' => '#'],
						'show_external' => false,
						'dynamic'       => [ 'active' => true ],
						'condition'     => [
							'slide_button!' => ''
						]
					],

					[
						'name'          => 'left_background_color',
						'label' => __( 'Left Background', 'bdthemes-prime-slider' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-mltiscroll-slider {{CURRENT_ITEM}}.bdt-ms-section-left' => 'background: {{VALUE}};',
						],
					],

					[
						'name'          => 'right_background_color',
						'label' => __( 'Right Background', 'bdthemes-prime-slider' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bdt-mltiscroll-slider {{CURRENT_ITEM}}.bdt-ms-section-right' => 'background: {{VALUE}};',
						],
					],

					[
						'name'    => 'slide_image',
						'label'   => esc_html__( 'Image', 'bdthemes-prime-slider' ),
						'type'    => Controls_Manager::MEDIA,
						'dynamic' => [ 'active' => true ],
						'default' => [
							'url' => BDTPS_ASSETS_URL . 'images/gems-'.rand(1,3).'.png',
						],
					],

				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'bdthemes-prime-slider' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'full',
				'prefix_class' => 'bdt-mltiscroll-slider--thumbnail-size-',
			]
		);

		$this->add_control(
            'content_position',
            [
                'label'   => __( 'Content Position', 'bdthemes-prime-slider' ),
                'type' 	  => Controls_Manager::SELECT,
				'default' => 'center',
                'options' => [
                    'center'     => __( 'Center', 'bdthemes-prime-slider' ),
                    'text-left'  => __( 'Left', 'bdthemes-prime-slider' ),
                    'text-right' => __( 'Right', 'bdthemes-prime-slider' ),
                    // 'right'      => __( 'Right', 'bdthemes-prime-slider' ),
                    // 'left'       => __( 'Left', 'bdthemes-prime-slider' ),
                ],
            ]
		);
		
		$this->add_responsive_control(
            'content_max_width',
            [
                'label' => __( 'Content Max Width', 'bdthemes-prime-slider' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 1200,
                        'min' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_responsive_control(
			'slide_text_align',
			[
				'label'   => __( 'Alignment', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-prime-slider' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-prime-slider' ),
						'icon'  => 'fas fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-prime-slider' ),
						'icon'  => 'fas fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bdthemes-prime-slider' ),
						'icon'  => 'fas fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'   => esc_html__( 'Show Image', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_subtitle',
			[
				'label'   => esc_html__( 'Show Sub Title', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Show Title', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_description',
			[
				'label'   => esc_html__( 'Show Description', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'   => esc_html__( 'Show Button', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_shadow_title',
			[
				'label'   => esc_html__( 'Show Shadow Title', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'   => __( 'Title HTML Tag', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'condition' => [
					'show_title' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_settings',
			[
				'label' => esc_html__( 'Additional Settings', 'bdthemes-prime-slider' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'scrollingSpeed',
			[
				'label'   => esc_html__( 'Scrolling Speed', 'bdthemes-prime-slider' ),
				'type' => Controls_Manager::SLIDER,
				'default' 		 => [
					'size' 			=> 700,
				],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'min' => 100,
                    ]
                ],
			]
		);

		$this->add_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
            'navigationPosition',
            [
                'label'   => __( 'Navigation Position', 'bdthemes-prime-slider' ),
                'type' 	  => Controls_Manager::SELECT,
				'default' => 'right',
                'options' => [
                    'right'      => __( 'Right', 'bdthemes-prime-slider' ),
                    'left'       => __( 'Left', 'bdthemes-prime-slider' ),
				],
				'condition' => [
					'navigation' => 'yes'
				]
            ]
		);

		$this->add_control(
			'loopBottom',
			[
				'label'   => esc_html__( 'loop Bottom', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'loopTop',
			[
				'label'   => esc_html__( 'loop Top', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'css3',
			[
				'label'   => esc_html__( 'Easing Effect', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();


		//Style
		$this->start_controls_section(
			'section_style_slider',
			[
				'label' => esc_html__( 'Multiscroll Content', 'bdthemes-prime-slider' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'item_shadow_title',
			[
				'label'     => esc_html__( 'Shadow Title Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .ms-section.shadow-title.bdt-ms-section-right:before, {{WRAPPER}} .bdt-mltiscroll-slider .ms-section.shadow-title.bdt-ms-section-left:before' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'bdthemes-prime-slider' ),
				'selector'    => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-prime-slider' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-prime-slider' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-prime-slider' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-title' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_sub_title',
			[
				'label'     => esc_html__( 'Subtitle', 'bdthemes-prime-slider' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_subtitle' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'sub_title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sub_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-subtitle',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label'     => esc_html__( 'Description', 'bdthemes-prime-slider' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-description' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-content .bdt-mltiscroll-slider-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => esc_html__( 'Button', 'bdthemes-prime-slider' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-prime-slider' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'types'    => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'bdthemes-prime-slider' ),
				'selector'    => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-prime-slider' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'border_radius_advanced_show!' => 'yes',
				],
			]
		);

		$this->add_control(
			'border_radius_advanced_show',
			[
				'label' => __( 'Advanced Radius', 'bdthemes-prime-slider' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'border_radius_advanced',
			[
				'label'       => esc_html__('Radius', 'bdthemes-prime-slider'),
				'description' => sprintf(__('For example: <b>%1s</b> or Go <a href="%2s" target="_blank">this link</a> and copy and paste the radius value.', 'bdthemes-prime-slider'), '30% 70% 82% 18% / 46% 62% 38% 54%', 'https://9elements.github.io/fancy-border-radius/'),
				'type'        => Controls_Manager::TEXT,
				'size_units'  => [ 'px', '%' ],
				'separator'   => 'after',
				'default'     => '30% 70% 82% 18% / 46% 62% 38% 54%',
				'selectors'   => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a'     => 'border-radius: {{VALUE}}; overflow: hidden;',
				],
				'condition' => [
					'border_radius_advanced_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-prime-slider' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-prime-slider' ),
				'selector'  => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-prime-slider' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a:hover'  => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_hover_background',
				'types'    => [ 'gradient' ],
				'selector'  => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_line_color',
			[
				'label'     => esc_html__( 'Line Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a:before' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-mltiscroll-slider .bdt-mltiscroll-slider-button a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_Navigation',
			[
				'label' => esc_html__( 'Navigation', 'bdthemes-prime-slider' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'tab_navigation_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-prime-slider' ),
			]
		);

		$this->add_control(
			'navigation_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav li span' => 'background: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'navigation_border',
				'label'       => esc_html__( 'Border', 'bdthemes-prime-slider' ),
				'selector'    => '{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav span',
			]
		);

		$this->add_control(
			'navi_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav span' => 'border-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'navigation_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-prime-slider' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_width',
			[
				'label' => __( 'Width (px)', 'bdthemes-prime-slider' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav span, {{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_height',
			[
				'label' => __( 'Height (px)', 'bdthemes-prime-slider' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav span, {{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_horizontal_offset_right',
			[
				'label'   => __( 'Horizontal Offset', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigationPosition' => 'right'
				]
			]
		);

		$this->add_responsive_control(
			'navigation_horizontal_offset_left',
			[
				'label'   => __( 'Horizontal Offset', 'bdthemes-prime-slider' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigationPosition' => 'left'
				]
			]
		);

		$this->add_responsive_control(
			'navigation_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-prime-slider' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_navigation_active',
			[
				'label' => esc_html__( 'Active', 'bdthemes-prime-slider' ),
			]
		);

		$this->add_control(
			'navigation_hover_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav li a.active span' => 'background: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'navigation_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'bdthemes-prime-slider' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'navigation_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-mltiscroll-slider #multiscroll-nav ul li a.active span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


	}

	protected function rendar_item_image($content) {
		$settings = $this->get_settings_for_display();

		if ('' == $settings['show_image']) {
			return;
		}

		$slide_image = Group_Control_Image_Size::get_attachment_image_src( $content['slide_image']['id'], 'thumbnail_size', $settings);
		if ( ! $slide_image ) {
			$slide_image = $content['slide_image']['url'];
		}

		?>

			<div class="bdt-multiscroll-image bdt-position-center">
				<img src="<?php echo esc_url($slide_image); ?>" alt="<?php echo get_the_title(); ?>">
			</div>

		<?php
	}

	protected function rendar_item_content($content) {
		$settings = $this->get_settings_for_display();

		?>
			<div class="bdt-mltiscroll-slider-content bdt-position-center">
				<div class="bdt-position-relative">
					<?php if ($content['sub_title'] && ( 'yes' == $settings['show_subtitle'] )) : ?>
						<div class="bdt-mltiscroll-slider-subtitle">
							<?php echo wp_kses_post($content['sub_title']); ?>
						</div>
					<?php endif; ?>
		
					<?php if ($content['title'] && ( 'yes' == $settings['show_title'] )) : ?>
						<<?php echo esc_html($settings['title_html_tag']); ?> class="bdt-mltiscroll-slider-title">
							<?php if ( '' !== $content['title_link']['url'] ) : ?>
								<a href="<?php echo esc_url( $content['title_link']['url'] ); ?>">
							<?php endif; ?>
								<?php echo wp_kses_post($content['title']); ?>
							<?php if ( '' !== $content['title_link']['url'] ) : ?>
								</a>
							<?php endif; ?>
						</<?php echo esc_html($settings['title_html_tag']); ?>>
					<?php endif; ?>
		
					<?php if ($content['description'] && ( 'yes' == $settings['show_description'] )) : ?>
						<div class="bdt-mltiscroll-slider-description">
							<?php echo wp_kses_post($content['description']); ?>
						</div>
					<?php endif; ?>

				</div>
			</div>

		<?php
	}

	protected function render_content_center() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="bdt-content-center">
			<div id="left-side" class="ms-left">
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>
				
				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					<div class="intro">
					<?php $this->rendar_item_content($slide); ?>
					<?php $this->rendar_item_image($slide); ?>
					</div>
				</div>

			<?php endforeach;
			?>
			</div>
			
			<div id="right-side" class="ms-right">
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>

				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					<div class="intro">
					<?php $this->rendar_item_content($slide); ?>
					<?php $this->rendar_item_image($slide); ?>
					</div>

					<?php if ($slide['slide_button'] && ( 'yes' == $settings['show_button'] )) : ?>
						<div class="bdt-mltiscroll-slider-button">
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								<a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>">
							<?php endif; ?>
								<?php echo wp_kses_post($slide['slide_button']); ?>
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				</div>

			<?php endforeach;
			?>
			</div>
		</div>

		<?php
	}

	protected function render_content_text_left() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="bdt-content-text-left">
			<div id="left-side" class="ms-left">
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>
    
				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					<div class="intro">
						<?php $this->rendar_item_content($slide); ?>
					</div>
				</div>

			<?php endforeach;
			?>
			</div>
			
			<div id="right-side" class="ms-right">
			
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>
				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					
					<?php $this->rendar_item_image($slide); ?>

					<?php if ($slide['slide_button'] && ( 'yes' == $settings['show_button'] )) : ?>
						<div class="bdt-mltiscroll-slider-button">
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								<a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>">
							<?php endif; ?>
								<?php echo wp_kses_post($slide['slide_button']); ?>
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

			<?php endforeach;
			?>
			</div>
		</div>
		<?php
	}

	protected function render_content_text_right() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="bdt-content-text-right">
			<div id="left-side" class="ms-left">
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>
    
				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-left', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-left', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					<?php $this->rendar_item_image($slide); ?>
				</div>

			<?php endforeach;
			?>
			</div>
			
			<div id="right-side" class="ms-right">
			
			<?php
			foreach ( $settings['slides'] as $slide ) : ?>
					    
				<?php
				if ('yes' == $settings['show_shadow_title']) {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'shadow-title', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				} else {
					$this->add_render_attribute('ms_section', 'class', ['ms-section', 'bdt-ms-section', 'bdt-ms-section-right', 'elementor-repeater-item-' . esc_attr($slide['_id'])], true);
					$this->add_render_attribute('ms_section', 'id', 'bdt-ms-section-right', true);
				}
				?>

				<div <?php echo $this->get_render_attribute_string('ms_section'); ?> data-label="<?php echo $slide['title']; ?>">
					<div class="intro">
					<?php $this->rendar_item_content($slide); ?>
					</div>
					<?php if ($slide['slide_button'] && ( 'yes' == $settings['show_button'] )) : ?>
						<div class="bdt-mltiscroll-slider-button">
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								<a href="<?php echo esc_url( $slide['button_link']['url'] ); ?>">
							<?php endif; ?>
								<?php echo wp_kses_post($slide['slide_button']); ?>
							<?php if ( '' !== $slide['button_link']['url'] ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

			<?php endforeach;
			?>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$settings         = $this->get_settings_for_display();
		$id = 'bdt-' . $this->get_id();

		$this->add_render_attribute('multiscroll_slider', 'class', 'bdt-mltiscroll-slider', true);
		$this->add_render_attribute(
			[
				'multiscroll_slider' => [
					'data-settings' => [
						wp_json_encode(array_filter([
							"scrollingSpeed"     => $settings["scrollingSpeed"]["size"],
							"navigation"         => ("yes" == $settings["navigation"]) ? true : false,
							"navigationPosition" => $settings["navigationPosition"],
							"loopBottom"         => ("yes" == $settings["loopBottom"]) ? true : false,
							"loopTop"            => ("yes" == $settings["loopTop"]) ? true : false,
							"css3"               => ("yes" == $settings["css3"]) ? false : true,
				        ]))
					]
				]
			]
		);

		?>
		<div <?php echo $this->get_render_attribute_string('multiscroll_slider'); ?> id="<?php echo esc_attr($id); ?>">

			<?php
			if ( 'text-right' == $settings['content_position'] ) {
				$this->render_content_text_right();
			} elseif ( 'text-left' == $settings['content_position'] ) {
				$this->render_content_text_left();
			} else {
				$this->render_content_center();
			}
			?>

		</div>
		<?php
	}

}
