<?php
	namespace Elementor;
	use \Elementor\Widget_Base;
	use \Elementor\Controls_Manager as Controls_Manager;
	use \Elementor\Group_Control_Border as Group_Control_Border;
	use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
	use \Elementor\Group_Control_Typography as Group_Control_Typography;
	use \Elementor\Scheme_Typography as Scheme_Typography;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 8/28/19
	 */

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Image_Hover_Effects extends Widget_Base {

	    public static $ma_el_image_hover_effects;


		public function get_name() {
			return 'ma-image-hover-effects';
		}

		public function get_title() {
			return esc_html__( 'MA Image Hover Effects', MELA_TD );
		}

		public function get_icon() {
			return 'ma-el-icon eicon-image-rollover';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_keywords() {
			return [ 'hover', 'image', 'effects', 'image hover', 'banner', 'banner image' ];
		}


		public static function ma_el_image_hover_effects() {

			return self::$ma_el_image_hover_effects =
				[
					'lily' 	            => __( 'Lily', 	                            MELA_TD ),
					'sadie' 	        => __( 'Sadie', 	                        MELA_TD ),
					'roxy'              => __( 'Roxy', 	                            MELA_TD ),
					'bubba'             => __( 'Bubba', 	                        MELA_TD ),
					'romeo'             => __( 'Romeo', 	                        MELA_TD ),
					'layla'             => __( 'Layla', 	                        MELA_TD ),
					'honey'             => __( 'Honey', 	                        MELA_TD ),
					'oscar'             => __( 'Oscar', 	                        MELA_TD ),
					'marley'            => __( 'Marley', 	                        MELA_TD ),
					'ruby'              => __( 'Ruby', 	                            MELA_TD ),
					'milo'              => __( 'Milo', 	                            MELA_TD ),
					'dexter'            => __( 'Dexter', 	                        MELA_TD ),
					'sarah'             => __( 'Sarah', 	                        MELA_TD ),
					'zoe'               => __( 'Zoe', 	                            MELA_TD ),
					'chico'             => __( 'Chico', 	                        MELA_TD ),
					'julia'             => __( 'Julia', 	                        MELA_TD ),
					'goliath'           => __( 'Goliath', 	                        MELA_TD ),
					'hera'              => __( 'Hera', 	                            MELA_TD ),
					'winston'           => __( 'Winston', 	                        MELA_TD ),
					'selena'            => __( 'Selena', 	                        MELA_TD ),
					'terry'             => __( 'Terry', 	                        MELA_TD ),
					'phoebe'            => __( 'Phoebe', 	                        MELA_TD ),
					'apollo'            => __( 'Apollo', 	                        MELA_TD ),
					'kira'              => __( 'Kira', 	                            MELA_TD ),
					'steve'             => __( 'Steve', 	                        MELA_TD ),
					'moses'             => __( 'Moses', 	                        MELA_TD ),
					'jazz'              => __( 'Jazz', 	                            MELA_TD ),
					'ming'              => __( 'Ming', 	                            MELA_TD ),
					'lexi'              => __( 'Lexi', 	                            MELA_TD ),
					'duke'              => __( 'Duke', 	                            MELA_TD ),
				];
		}

		protected function _register_controls() {


			/*
			* Master Addons: Effects Controls & Image Hover Effects Section Start
			*/

			$this->start_controls_section(
				'ma-image-hover-effect-section',
				[
					'label' => __( 'Effects & Image', MELA_TD ),
				]
			);


			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {

				$this->add_control(
					'ma_el_main_image_effect',
					[
						'label'       => esc_html__( 'Hover Effect', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'sadie',
						'options'     => self::ma_el_image_hover_effects()
					]
				);

				//Free Version Codes

			} else {

				$this->add_control(
					'ma_el_main_image_effect',
					[
						'label'       => esc_html__( 'Hover Effect', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'sadie',
						'options'     => [
							'lily' 	                    => __( 'Lily', MELA_TD ),
							'sadie' 	                => __( 'Sadie', MELA_TD ),
							'roxy'                      => __( 'Roxy', MELA_TD ),
							'bubba'                     => __( 'Bubba', MELA_TD ),
							'romeo'                     => __( 'Romeo', MELA_TD ),
							'layla'                     => __( 'Layla', MELA_TD ),
							'honey'                     => __( 'Honey', MELA_TD ),
							'oscar'                     => __( 'Oscar', MELA_TD ),

							'ma-el-img-hover1'          => __( 'Marley (Pro)',MELA_TD ),
							'ma-el-img-hover2'          => __( 'Ruby (Pro)', MELA_TD ),
							'ma-el-img-hover3'          => __( 'Milo (Pro)', MELA_TD ),
							'ma-el-img-hover4'          => __( 'Dexter (Pro)', MELA_TD ),
							'ma-el-img-hover5'          => __( 'Sarah (Pro)', MELA_TD ),
							'ma-el-img-hover6'          => __( 'Zoe (Pro)', MELA_TD ),
							'ma-el-img-hover7'          => __( 'Chico (Pro)', MELA_TD ),
							'ma-el-img-hover8'          => __( 'Julia (Pro)', MELA_TD ),
							'ma-el-img-hover9'          => __( 'Goliath (Pro)', MELA_TD ),
							'ma-el-img-hover10'         => __( 'Hera (Pro)', MELA_TD ),
							'ma-el-img-hover11'         => __( 'Winston (Pro)', MELA_TD ),
							'ma-el-img-hover12'         => __( 'Selena (Pro)',MELA_TD ),
							'ma-el-img-hover13'         => __( 'Terry (Pro)', MELA_TD ),
							'ma-el-img-hover14'         => __( 'Phoebe (Pro)', MELA_TD ),
							'ma-el-img-hover15'         => __( 'Apollo (Pro)', MELA_TD ),
							'ma-el-img-hover16'         => __( 'Kira (Pro)', MELA_TD ),
							'ma-el-img-hover17'         => __( 'Steve (Pro)', MELA_TD ),
							'ma-el-img-hover18'         => __( 'Moses (Pro)', MELA_TD ),
							'ma-el-img-hover19'         => __( 'Jazz (Pro)', MELA_TD ),
							'ma-el-img-hover20'         => __( 'Ming (Pro)', MELA_TD ),
							'ma-el-img-hover21'         => __( 'Lexi (Pro)', MELA_TD ),
							'ma-el-img-hover22'         => __( 'Duke (Pro)', MELA_TD ),
						],
						'description' => sprintf( '20+ more effects on <a href="%s" target="_blank">%s</a>',
							esc_url_raw( admin_url('admin.php?page=master-addons-settings-pricing') ),
							__( 'Upgrade Now', MELA_TD ) )
					]
				);
			}


			$this->add_control('ma_el_main_image',
				[
					'label'			=> __( 'Upload Image', MELA_TD ),
					'description'	=> __( 'Select an Image', MELA_TD ),
					'type'			=> Controls_Manager::MEDIA,
					'default'		=> [
						'url'	=> Utils::get_placeholder_image_src()
					],
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'image_thumbnail_size',
					'default' => 'full',
					'condition' => [
						'ma_el_main_image[url]!' => '',
					],
				]
			);


			$this->add_control('ma_el_image_link_url_switch',
				[
					'label'         => __('Image Link?', MELA_TD),
					'type'          => Controls_Manager::SWITCHER
				]
			);


			$this->add_control('ma_el_main_image_more_text',
				[
					'label'         => __('Link Text',MELA_TD),
					'type'          => Controls_Manager::TEXT,
					'dynamic'       => [ 'active' => true ],
					'default'       => __('Read More',MELA_TD),
					'condition'     => [
						'ma_el_image_link_url_switch'   => 'yes'
					]
				]
			);



			$this->add_control(
				'ma_el_main_image_more_link_url',
				[
					'label'       => __( 'Link URL', MELA_TD ),
					'type'        => Controls_Manager::URL,
					'label_block' => true,
					'default'     => [
						'url'         => '#',
						'is_external' => '',
					],
					'condition'		=> [
						'ma_el_image_link_url_switch'    => 'yes'
					],
					'show_external' => true,
				]
			);

			$this->add_control('ma_el_main_image_height',
				[
					'label'			=> __( 'Height', MELA_TD ),
					'type'			=> Controls_Manager::SELECT,
					'options'		=> [
						'default'		=> __('Default', MELA_TD),
						'custom'		=> __('Custom', MELA_TD)
					],
					'default'		=> 'default',
					'description'	=> __( 'Choose if you want to set a custom height for the banner or keep it as it is', MELA_TD )
				]
			);

			$this->add_responsive_control('ma_el_main_image_custom_height',
				[
					'label'			=> __( 'Min Height', MELA_TD ),
					'type'			=> Controls_Manager::NUMBER,
					'description'	=> __( 'Set a minimum height value in pixels', MELA_TD ),
					'condition'		=> [
						'ma_el_main_image_height' => 'custom'
					],
					'selectors'		=> [
						'{{WRAPPER}} .ma-el-image-hover-effect' => 'height: {{VALUE}}px;'
					]
				]
			);

			$this->add_responsive_control('ma_el_main_image_vertical_align',
				[
					'label'			=> __( 'Vertical Align', MELA_TD ),
					'type'			=> Controls_Manager::SELECT,
					'condition'		=> [
						'ma_el_main_image_height' => 'custom'
					],
					'options'		=> [
						'flex-start'	=> __('Top', MELA_TD),
						'center'		=> __('Middle', MELA_TD),
						'flex-end'		=> __('Bottom', MELA_TD),
						'inherit'		=> __('Full', MELA_TD)
					],
					'default'       => 'flex-start',
					'selectors'		=> [
						'{{WRAPPER}} .ma-el-image-hover-effect figure' => 'align-items: {{VALUE}}; -webkit-align-items: {{VALUE}};'
					]
				]
			);

			$this->end_controls_section();






			/*
			 *  Master Addons: Style Controls
			 */
			$this->start_controls_section(
				'ma_el_main_image_content_heading_section',
				[
					'label' => esc_html__( 'Heading', MELA_TD )
				]
			);

			$this->add_control('ma_el_main_image_title',
				[
					'label'			=> __( 'Title', MELA_TD ),
					'placeholder'	=> __( 'Title for this Image', MELA_TD ),
					'type'			=> Controls_Manager::TEXT,
					'dynamic'       => [ 'active' => true ],
					'default'		=> __( 'Master <span>Addons</span>', MELA_TD ),
					'label_block'	=> false
				]
			);


			$this->add_control(
				'title_html_tag',
				[
					'label'   => __( 'HTML Tag', MELA_TD ),
					'type'    => Controls_Manager::SELECT,
					'options' => Master_Addons_Helper::ma_el_title_tags(),
					'default' => 'h2',
				]
			);

			$this->end_controls_section();





			/*
			 *  Master Addons: Sub Heading
			 */
			$this->start_controls_section(
				'ma_el_main_image_content_subheading_section',
				[
					'label' => __( 'Sub Heading', MELA_TD ),
					'condition'     => [
						"ma_el_main_image_effect"   => [
							"honey",
						]
					]
				]
			);

			$this->add_control('ma_el_main_image_sub_title',
				[
					'label'			=> __( 'Sub Title', MELA_TD ),
					'placeholder'	=> __( 'Sub Title for this Image', MELA_TD ),
					'type'			=> Controls_Manager::TEXT,
					'default'		=> __( 'Elementor', MELA_TD ),
					'label_block'	=> false
				]
			);


			$this->end_controls_section();





            /*
             *  Master Addons: Image Descriptions
             */
			$this->start_controls_section(
				'ma_el_main_image_desc_section',
				[
					'label'			=> __( 'Description', MELA_TD ),
					'type'			=> Controls_Manager::HEADING,
					'condition'     => [
						"ma_el_main_image_effect"   => [
						        "lily",
						        "zoe",
						        "sadie",
						        "layla",
						        "oscar",
						        "marley",
						        "dexter",
						        "sarah",
						        "chico",
						        "kira",
                                "apollo",
							    "steve",
							    "moses",
							    "jazz",
							    "ming",
							    "lexi",
							    "duke",
							    "milo",
							    "bubba",
							    "goliath",
							    "selena",
							    "roxy",
							    "bubba",
							    "romeo",
							    "ruby"
                        ]
					]
				]
			);

			$this->add_control('ma_el_main_image_desc',
				[
					'label'			=> __( 'Description', MELA_TD ),
					'description'	=> __( 'Give the description to this banner', MELA_TD ),
					'type'			=> Controls_Manager::TEXTAREA,
					'dynamic'       => [ 'active' => true ],
					'default'		=> __( 'Master Addons gives your website a vibrant and lively style, you would love.', MELA_TD ),
					'label_block'	=> true,
					'condition'     => [
						'ma_el_main_image_effect!'   => ['julia']
					]

				]
			);


			$this->end_controls_section();


            /*
             *  Master Addons: Set 2 Image Descriptions
             */
			$this->start_controls_section(
				'ma_el_main_image_desc_set2_heading',
				[
					'label'			=> __( 'Description', MELA_TD ),
					'type'			=> Controls_Manager::HEADING,
					'description'   => __('Write Description Each line', MELA_TD),
					'condition'     => [
						'ma_el_main_image_effect'   => ['julia']
					]
				]
			);

			$repeater = new Repeater();


			$repeater->add_control('ma_el_main_image_desc_set2',
				[
					'label'         => __('Read More Text',MELA_TD),
					'type'          => Controls_Manager::TEXTAREA,
					'dynamic'       => [ 'active' => true ],
					'default'       => 'Julia dances in the deep dark',
				]
			);


			$this->add_control(
				'ma_el_main_image_desc_set2_tabs',
				[
					'type'                  => Controls_Manager::REPEATER,
					'default'               => [
						[ 'ma_el_main_image_desc_set2' => 'Julia dances in the deep dark' ],
						[ 'ma_el_main_image_desc_set2' => 'She loves the smell of the ocean' ],
						[ 'ma_el_main_image_desc_set2' => 'And dives into the morning light' ]
					],
					'fields'                => array_values( $repeater->get_controls() ),
					'title_field'           => '{{ma_el_main_image_desc_set2}}'
				]
			);


			$this->end_controls_section();







			/*
			 *  Master Addons: Image Hover Social Links
			 */
			$this->start_controls_section(
				'ma_el_main_image_social_link_section',
				[
					'label' => esc_html__( 'Social Links', MELA_TD ),
					'condition'     => [
						'ma_el_main_image_effect' => ['zoe','hera','winston','terry','phoebe','kira']
					]
				]
			);


			/* Icons Dependencies for Styles */

			$this->add_control('ma_el_main_image_icon_heading',
				[
					'label'			=> __( 'Social Icons', MELA_TD ),
					'type'			=> Controls_Manager::HEADING,
					'description'   => __('Select Social Icons', MELA_TD)
				]
			);
			$repeater = new Repeater();


			$repeater->add_control(
				'ma_el_main_image_icon',
				[
					'label'     => __( 'Icon', MELA_TD ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-wordpress'
				]
			);

			$repeater->add_control(
				'ma_el_main_image_icon_link',
				[
					'label' => __( 'Icon Link', MELA_TD ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '#',
						'is_external' => true,
					]
				]
			);

			$this->add_control(
				'ma_el_main_image_icon_tabs',
				[
					'type'                  => Controls_Manager::REPEATER,
					'default'               => [
						[ 'ma_el_main_image_icon' => 'fa fa-wordpress' ],
						[ 'ma_el_main_image_icon' => 'fa fa-facebook' ],
						[ 'ma_el_main_image_icon' => 'fa fa-twitter' ],
						[ 'ma_el_main_image_icon' => 'fa fa-instagram' ],
					],
					'fields'                => array_values( $repeater->get_controls() ),
					'title_field'           => '{{ma_el_main_image_icon}}'
				]
			);


			$this->end_controls_section();



			/*
			 * Image Hover Style Section
			 */
			$this->start_controls_section('ma_el_main_image_hover_style_section',
				[
					'label' 		=> __( 'Image', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control('ma_el_main_image_bg_color',
				[
					'label' 		=> __( 'Background Color', MELA_TD ),
					'type' 			=> Controls_Manager::COLOR,
					'default'       => '',
					'selectors' 	=> [
						'{{WRAPPER}} .ma-el-image-hover-effect figure' => 'background: {{VALUE}};'
					]
				]
			);

			$this->add_control('ma_el_main_image_opacity',
				[
					'label' => __( 'Image Opacity', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => .8
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect figure img' => 'opacity: {{SIZE}};'
					]
				]
			);

			$this->add_control('ma_el_main_image_hover_opacity',
				[
					'label' => __( 'Hover Opacity', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 1
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1
						]
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect figure:hover img' => 'opacity: {{SIZE}};'
					]
				]
			);


			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name' => 'image_filters',
					'label'     => __('Image Filter', MELA_TD),
					'selector' => '{{WRAPPER}} .ma-el-image-hover-effect figure img',
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'      => 'hover_image_filters',
					'label'     => __('Hover Image Filter', MELA_TD),
					'selector'  => '{{WRAPPER}} .ma-el-image-hover-effect figure:hover img'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'          => 'ma_el_main_image_border',
					'selector'      => '{{WRAPPER}} .ma-el-image-hover-effect figure'
				]
			);

			$this->add_responsive_control(
				'ma_el_main_image_border_radius',
				[
					'label' => __( 'Border Radius', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect figure' => 'border-radius: {{SIZE}}{{UNIT}}'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'label'             => __('Box Shadow',MELA_TD),
					'name'              => 'ma_el_main_image_shadow',
					'selector'          => '{{WRAPPER}} .ma-el-image-hover-effect figure'
				]
			);
			$this->end_controls_section();



            /*
             * Title Color
             */
			$this->start_controls_section('ma_el_main_image_title_style',
				[
					'label' 		=> __( 'Title', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control('ma_el_main_image_title_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1
					],
					'default' => "#fff",
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect .ma-el-image-hover-title' => 'color: {{VALUE}};'
					]
				]
			);

			$this->add_control('jltma_main_image_border_color',
				[
					'label'			=> __( 'Border Color', MELA_TD ),
					'type'			=> Controls_Manager::COLOR,
					'selectors'     => [
						'{{WRAPPER}} .ma-el-image-hover-effect figcaption::before'    => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .ma-el-image-hover-effect figcaption::after'    => 'border-color: {{VALUE}};',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_main_image_title_typography',
					'selector' => '{{WRAPPER}} .ma-el-image-hover-effect .ma-el-image-hover-title',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'label'             => __('Box Shadow',MELA_TD),
					'name'              => 'ma_el_main_image_title_shadow',
					'selector'          => '{{WRAPPER}} .ma-el-image-hover-title'
				]
			);

			$this->end_controls_section();


			/*
			 * Description Style
			 */


			$this->start_controls_section('ma_el_main_image_desc_style_section',
				[
					'label' 		=> __( 'Description', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control('ma_el_main_image_desc_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect p' => 'color: {{VALUE}};'
					],
				]
			);



			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'          => 'ma_el_main_image_desc_typography',
					'selector'      => '{{WRAPPER}} .ma-el-image-hover-effect p',
					'scheme'        => Scheme_Typography::TYPOGRAPHY_3,
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'label'             => __('Text Shadow',MELA_TD),
					'name'              => 'ma_el_main_image_desc_text_shadow',
					'selector'          => '{{WRAPPER}} .ma-el-image-hover-effect p',
				]
			);

			$this->end_controls_section();



			/*
			 * Social Icons Style
			 */

			$this->start_controls_section('ma_el_main_image_icon_hover_style_section',
				[
					'label' 		=> __( 'Social Icons', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE,
					'condition'     => [
						'ma_el_main_image_effect' => ['zoe','hera']
					]
				]
			);

			$this->start_controls_tabs( 'ma_el_main_image_icon_style_tabs' );

			$this->start_controls_tab( 'ma_el_main_image_icon_style_tab_normal',
				[ 'label' => esc_html__( 'Normal', MELA_TD )
				] );

			$this->add_control('ma_el_main_image_icon_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2
					],
//					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect .icon-links a' => 'color: {{VALUE}};'
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_main_image_icon_style_tab_hover',
                [ 'label' => esc_html__( 'Hover', MELA_TD )
			] );

			$this->add_control('ma_el_main_image_icon_hover_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3
					],
//					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-hover-effect .icon-links a:hover' => 'color: {{VALUE}};'
					],
				]
			);

			$this->end_controls_section();


		}


		private function render_image( $image_id, $settings ) {
			$image_thumbnail_size = $settings['image_thumbnail_size_size'];
			if ( 'custom' === $image_thumbnail_size ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'full', $settings );
			} else {
				$image_src = wp_get_attachment_image_src( $image_id, $image_thumbnail_size );
				$image_src = $image_src[0];
			}

			return sprintf( '<img src="%s"  class="circled" alt="%s" />', esc_url($image_src), esc_html( get_post_meta( $image_id, '_wp_attachment_image_alt', true) ) );
		}



		protected function render() {

			$settings = $this->get_settings_for_display();

            // First 15 Effects
			foreach( array_slice(self::ma_el_image_hover_effects(), 0, 15) as $key=>$ma_el_image_hover_value ){
				$ma_el_image_hover_sets = "one";
			}

			// Last 15 Effects
			foreach( array_slice(self::ma_el_image_hover_effects(), 15, 30) as $key=>$ma_el_image_hover_value ){
				$ma_el_image_hover_sets = "two";
			}


			$this->add_render_attribute( 'ma_el_image_hover_effect_wrapper', [
				'class'	=> [
				    'ma-el-image-hover-effect',
					'ma-el-image-hover-effect-' . $ma_el_image_hover_sets,
					'ma-el-image-hover-effect-' . esc_attr($settings['ma_el_main_image_effect'] )
				],
				'id' => 'ma-el-image-hover-effect-' . $this->get_id()
			]);


			$this->add_render_attribute( 'ma_el_image_hover_effect_heading', ['class'	=> 'ma-el-image-hover-title']);


			$ma_el_main_image = $this->get_settings_for_display( 'ma_el_main_image' );

			$ma_el_main_image_effect = $settings['ma_el_main_image_effect'];
			$ma_el_main_image_alt = Control_Media::get_image_alt( $settings['ma_el_main_image'] );


			// Image Link
			if( $settings['ma_el_image_link_url_switch']=="yes"){
				$this->add_render_attribute( 'ma_el_image_hover_link', [
					'class'	=> [ 'ma-image-hover-read-more' ],
					'href'	=> esc_url($settings['ma_el_main_image_more_link_url']['url'] ),
				]);

				if( $settings['ma_el_main_image_more_link_url']['is_external'] ) {
					$this->add_render_attribute( 'ma_el_image_hover_link', 'target', '_blank' );
				}

				if( $settings['ma_el_main_image_more_link_url']['nofollow'] ) {
					$this->add_render_attribute( 'ma_el_image_hover_link', 'rel', 'nofollow' );
				}
			}

			?>

				<div <?php echo $this->get_render_attribute_string( 'ma_el_image_hover_effect_wrapper' ); ?>>


                    <figure class="effect-<?php echo esc_attr( $settings['ma_el_main_image_effect'] );?>">

                        <?php //echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_thumbnail_size');?>

                        <?php echo $this->render_image( $settings['ma_el_main_image']['id'], $settings );?>

                        <figcaption>
							<div class="ma-image-hover-content">
								<<?php echo $settings['title_html_tag'];?> <?php echo $this->get_render_attribute_string( 'ma_el_image_hover_effect_heading' ); ?>>

                                    <?php echo $this->parse_text_editor($settings['ma_el_main_image_title']); ?>

                                    <?php $ma_el_main_image_sub_title = array( "honey");
                                        if (in_array($ma_el_main_image_effect, $ma_el_main_image_sub_title)) { ?>
                                         <i><?php echo $settings['ma_el_main_image_sub_title']; ?></i>
                                    <?php } ?>

                                </<?php echo $settings['title_html_tag'];?>>


                                <?php
	                                // Social Icons Sets
	                                $ma_el_main_image_socials_array = array( "hera","zoe","winston","terry","phoebe","kira");
	                                if (in_array($ma_el_main_image_effect, $ma_el_main_image_socials_array)) { ?>
                                    <p class="icon-links">
                                        <?php foreach( $settings['ma_el_main_image_icon_tabs'] as $index => $tab ) { ?>
                                            <a href="<?php echo esc_url_raw( $tab['ma_el_main_image_icon_link']['url'] );?>">
                                                <span class="<?php echo $tab['ma_el_main_image_icon']; ?>"></span>
                                            </a>
                                        <?php } ?>
                                    </p>
                                <?php } ?>

	                        <?php
		                        // Design Specific Descriptions for Set 1
                                //if( $settings['ma_el_main_image_effect'] == "julia" ){?>
		                        <?php if(isset($settings['ma_el_main_image_desc_set2_tabs'])){
		                            foreach( $settings['ma_el_main_image_desc_set2_tabs'] as $index => $tab ) {
			                        $ma_el_main_image_effect_one_array=array( "julia" );
			                        if (in_array($ma_el_main_image_effect,$ma_el_main_image_effect_one_array)) {
                                ?>
                                    <p class="ma-el-image-hover-desc"><?php echo $tab['ma_el_main_image_desc_set2'];?></p>
		                        <?php } } }
                            //		}


	                            // Design Specific Descriptions for Set 1
                                $ma_el_main_image_effect_array=array( "zoe","goliath","selena","apollo","steve","moses","jazz","ming","lexi","duke",
	                                "lily","sadie","oscar","layla","marley","dexter","sarah","chico","hera","kira","milo","roxy","bubba","romeo","ruby");
                                if (in_array($ma_el_main_image_effect,$ma_el_main_image_effect_array)) { ?>
                                    <p class="ma-el-image-hover-desc">
                                        <?php echo htmlspecialchars_decode( $settings['ma_el_main_image_desc'] ); ?>
                                    </p>
                                <?php } ?>


							</div>

	                    <?php if( $settings['ma_el_image_link_url_switch']=="yes" && $settings['ma_el_main_image_more_link_url']['url'] !=""){?>
	                        <a <?php echo $this->get_render_attribute_string( 'ma_el_image_hover_link' ); ?>></a>
	                    <?php } ?>

						</figcaption>

					</figure>



				</div>
		<?php
		}

		protected function _content_template() {}

	}

Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Image_Hover_Effects() );