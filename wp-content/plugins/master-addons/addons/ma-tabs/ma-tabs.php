<?php
	namespace Elementor;
	use Elementor\Widget_Base;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Tabs extends Widget_Base {

		public function get_name() {
			return 'ma-tabs';
		}

		public function get_title() {
			return esc_html__( 'MA Tabs', MELA_TD);
		}

		public function get_icon() {
			return 'ma-el-icon eicon-tabs';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}


		protected function _register_controls() {

			/**
			 * MA Tabs Content Settings
			 */
			$this->start_controls_section(
				'ma_el_section_tabs_content_settings',
				[
					'label' => esc_html__( 'Content', MELA_TD ),
					'seperator' => 'before',
				]
			);

			$repeater = new Repeater();



			$repeater->add_control(
				'ma_el_tab_show_as_default',
				[
					'name' => 'ma_el_tab_show_as_default',
					'label' => __( 'Set as Default', MELA_TD ),
					'type' => Controls_Manager::SWITCHER,
					'return_value' => 'active',
				]
			);


			$repeater->add_control(
				'ma_el_tabs_icon_type',
				[
					'label'       => esc_html__( 'Icon Type', MELA_TD ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'options'     => [
						'none' => [
							'title' => esc_html__( 'None', MELA_TD ),
							'icon'  => 'fa fa-ban',
						],
						'icon' => [
							'title' => esc_html__( 'Icon', MELA_TD ),
							'icon'  => 'fa fa-gear',
						],
						'image' => [
							'title' => esc_html__( 'Image', MELA_TD ),
							'icon'  => 'fa fa-picture-o',
						],
					],
					'default'       => 'icon',
				]
			);
			$repeater->add_control(
				'ma_el_tab_title_icon',
				[
					'label' => esc_html__( 'Icon', MELA_TD ),
					'type' => Controls_Manager::ICON,
					'default' => 'fa fa-home',
					'condition' => [
						'ma_el_tabs_icon_type' => 'icon'
					]
				]
			);
			$repeater->add_control(
				'ma_el_tab_title_image',
				[
					'label' => esc_html__( 'Image', MELA_TD ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'condition' => [
						'ma_el_tabs_icon_type' => 'image'
					]
				]
			);

			$repeater->add_control(
				'ma_el_tab_title',
				[
					'label' => esc_html__( 'Tab Title', MELA_TD ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Tab Title', MELA_TD ),
					'dynamic' => [ 'active' => true ]
				]
			);


			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {

				$repeater->add_control(
					'ma_tabs_content_type',
					[
						'label'                 => esc_html__( 'Content Type', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'label_block'           => false,
						'options'               => [
							'content'   => __( 'Content', MELA_TD ),
							'section'   => __( 'Saved Section', MELA_TD ),
							'widget'    => __( 'Saved Widget', MELA_TD ),
							'template'  => __( 'Saved Page Template', MELA_TD ),
						],
						'default'               => 'content',
					]
				);

				$repeater->add_control(
					'ma_el_tab_content',
					[
						'label'                 => esc_html__( 'Tab Content', MELA_TD ),
						'type'                  => Controls_Manager::WYSIWYG,
						'default'               => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', MELA_TD ),
						'condition'             => [
							'ma_tabs_content_type'	=> 'content',
						],
					]
				);


				$repeater->add_control(
					'saved_widget',
					[
						'label'                 => __( 'Choose Widget', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'widget' ),
						'default'               => '-1',
						'condition'             => [
							'ma_tabs_content_type'    => 'widget',
						],
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'ma_tabs_content_type',
									'operator'  => '==',
									'value'     => 'widget',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'saved_section',
					[
						'label'                 => __( 'Choose Section', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'section' ),
						'default'               => '-1',
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'ma_tabs_content_type',
									'operator'  => '==',
									'value'     => 'section',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'templates',
					[
						'label'                 => __( 'Choose Template', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'page' ),
						'default'               => '-1',
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'ma_tabs_content_type',
									'operator'  => '==',
									'value'     => 'template',
								],
							],
						],
					]
				);

			} else {
				$repeater->add_control(
					'ma_el_tab_content',
					[
						'label'                 => esc_html__( 'Tab Content', MELA_TD ),
						'type'                  => Controls_Manager::WYSIWYG,
						'default'               => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', MELA_TD ),
//						'condition'             => [
//							'ma_tabs_content_type'	=> 'content',
//						],
					]
				);

			}


			$this->add_control(
				'ma_el_tabs',
				[
					'type'                  => Controls_Manager::REPEATER,
					'default'               => [
						[ 'ma_el_tab_title' => esc_html__( 'Tab Title One', MELA_TD ) ],
						[ 'ma_el_tab_title' => esc_html__( 'Tab Title Two', MELA_TD ) ],
						[ 'ma_el_tab_title' => esc_html__( 'Tab Title Three', MELA_TD ) ],
					],
					'fields'                => array_values( $repeater->get_controls() ),
					'title_field'           => '{{ma_el_tab_title}}',
				]
			);


			$this->add_control(
				'ma_el_tabs_effect',
				[
					'label'       => esc_html__( 'Tab Effect', MELA_TD ),
					'type'        => Controls_Manager::SELECT,
					'label_block' => false,
					'options' 		=> [
						'hover'       	=> esc_html__( 'Hover', MELA_TD ),
						'click'     	=> esc_html__( 'Click', MELA_TD )
					],
					'default'       => 'hover',
				]
			);

			$this->end_controls_section();



			/**
			 * -------------------------------------------
			 * Tab Style MA Tabs Generel Style
			 * -------------------------------------------
			 */
			$this->start_controls_section(
				'ma_el_section_tabs_style_preset_settings',
				[
					'label' => esc_html__( 'General Styles', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			if ( ma_el_fs()->can_use_premium_code() ) {
				$this->add_control(
					'ma_el_tabs_preset',
					[
						'label'       	=> esc_html__( 'Style Preset', MELA_TD ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'two',
						'label_block' 	=> false,
						'options' 		=> [
								'two'       => esc_html__( 'Horizontal Tabs', MELA_TD ),
								'three'     => esc_html__( 'Vertical Tabs', MELA_TD ),
								'four'      => esc_html__( 'Left Active Border', MELA_TD ),
						]
					]
				);
			} else{
				$this->add_control(
					'ma_el_tabs_preset',
					[
						'label'       	=> esc_html__( 'Style Preset', MELA_TD ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'two',
						'label_block' 	=> false,
						'options' 		=> [
								'two'       => esc_html__( 'Horizontal Tabs', MELA_TD ),
								'three'     => esc_html__( 'Vertical Tabs', MELA_TD ),
								'four'      => esc_html__( 'Left Active Border', MELA_TD ),
						],

						'description' => sprintf( 'For more Styles <a href="%s" target="_blank">%s</a>',
							esc_url_raw( ma_el_fs()->get_upgrade_url() ),
							__( 'Upgrade Now', MELA_TD ) )
					]
				);
			}
			$this->add_control(
				'ma_el_tabs_icon_show',
				[
					'label' => esc_html__( 'Enable Icon', MELA_TD ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'yes',
					'return_value' => 'yes',
				]
			);

			$this->end_controls_section();



			/**
			 * -------------------------------------------
			 * Tab Style MA Tabs Heading Style
			 * -------------------------------------------
			 */
			$this->start_controls_section(
				'ma_el_section_tabs_heading_style_settings',
				[
					'label' => esc_html__( 'Heading', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_tab_heading_typography',
					'selector' => '{{WRAPPER}} .ma-el-advance-tab .ma-el-tab-title',
				]
			);


			$this->start_controls_tabs( 'ma_el_tabs_header_tabs' );
			// Normal State Tab
			$this->start_controls_tab( 'ma_el_tabs_header_normal', [ 'label' => esc_html__( 'Normal',
				MELA_TD )
			] );

			$this->add_control(
				'ma_el_tab_text_color',
				[
					'label' => esc_html__( 'Text Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li span, {{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li i' => 'color: {{VALUE}};'
					],
				]
			);

			$this->add_control(
				'ma_el_tab_bg_color',
				[
					'label' => esc_html__( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li' => 'background: {{VALUE}};'
					],
				]
			);

			$this->add_control(
				'ma_el_tab_border_color',
				[
					'label' => esc_html__( 'Bottom Border Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#e5e5e5',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab.two .ma-el-advance-tab-nav li' => 'border-bottom: 1px solid {{VALUE}};'
					],
					'condition' => [
						'ma_el_tabs_preset' => 'two'
					]
				]
			);


			$this->add_control(
				'ma_el_tabs_heading_padding',
				[
					'label' => esc_html__( 'Padding', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'ma_el_tabs_heading_margin',
				[
					'label' => esc_html__( 'Margin', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'ma_el_tabs_heading_box_shadow',
                    'selector'      => '{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'          => 'ma_el_tabs_heading_box_border',
                    'separator'     => 'before',
                    'selector'      => '{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li',
                ]
			);

			$this->add_control(
				'ma_el_tabs_heading_border_radius',
				[
					'label'         => __('Border Radius', MELA_TD ),
					'type'          => Controls_Manager::DIMENSIONS,
					'size_units'    => ['px', '%' ,'em'],
					'selectors'     => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li'
					]
				]
			);


			$this->end_controls_tab();

			// Active State Tab

			$this->start_controls_tab( 'ma_el_tabs_header_active', [ 'label' => esc_html__( 'Active',
				MELA_TD )
			] );
			$this->add_control(
				'ma_el_tab_text_color_active',
				[
					'label' => esc_html__( 'Text Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#0a1724',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active span, {{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active i' => 'color: {{VALUE}};'
					],
				]
			);

			$this->add_control(
				'ma_el_tab_bg_color_active',
				[
					'label' => esc_html__( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active, {{WRAPPER}} .ma-el-advance-tab.four .ma-el-advance-tab-nav li::before' => 'background: {{VALUE}};',
						'{{WRAPPER}} .ma-el-advance-tab.three .ma-el-advance-tab-nav li::before' => 'border-left-color: {{VALUE}};'
					],
				]
			);

			$this->add_control(
				'ma_el_tab_border_color_active',
				[
					'label' => esc_html__( 'Bottom Border Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#704aff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab.two .ma-el-advance-tab-nav li.active' => 'border-bottom: 1px solid {{VALUE}};',
						'{{WRAPPER}} .ma-el-advance-tab.four .ma-el-advance-tab-nav li::after' => 'background: {{VALUE}};'
					],
					'condition' => [
						'ma_el_tabs_preset' => 'two'
					]
				]
			);

			$this->add_control(
				'ma_el_tab_border_left_color_active',
				[
					'label' => esc_html__( 'Bottom Left Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#704aff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab.four .ma-el-advance-tab-nav li::after' => 'background: {{VALUE}};'
					],
					'condition' => [
						'ma_el_tabs_preset' => 'four'
					]
				]
			);


			$this->add_control(
				'ma_el_tabs_heading_active_padding',
				[
					'label' => esc_html__( 'Padding', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'ma_el_tabs_heading_active_margin',
				[
					'label' => esc_html__( 'Margin', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'ma_el_tabs_heading_active_box_shadow',
                    'selector'      => '{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'          => 'ma_el_tabs_heading_active_box_border',
                    'separator'     => 'before',
                    'selector'      => '{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active',
                ]
			);

			$this->add_control(
				'ma_el_tabs_heading_active_border_radius',
				[
					'label'         => __('Border Radius', MELA_TD ),
					'type'          => Controls_Manager::DIMENSIONS,
					'size_units'    => ['px', '%' ,'em'],
					'selectors'     => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-nav li.active'
					]
				]
			);




			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

			/**
			 * -------------------------------------------
			 * Tab Style MA Tabs Content Style
			 * -------------------------------------------
			 */
			$this->start_controls_section(
				'ma_el_section_tabs_tab_content_style_settings',
				[
					'label' => esc_html__( 'Content', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'exclusive_tabs_content_title_color',
				[
					'label' => esc_html__( 'Title Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#0a1724',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-content .ma-el-advance-tab-content-title' =>
							'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_tabs_content_title_typography',
					'label' => esc_html__( 'Title Typography', MELA_TD ),
					'selector' => '{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-content-title',
				]
			);
			$this->add_control(
				'exclusive_tabs_content_bg_color',
				[
					'label' => esc_html__( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-content ' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'exclusive_tabs_content_text_color',
				[
					'label' => esc_html__( 'Text Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#333',
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-content ' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_tabs_content_typography',
					'label' => esc_html__( 'Text Typography', MELA_TD ),
					'selector' => '{{WRAPPER}} .ma-el-advance-tab.two .ma-el-advance-tab-content p',
				]
			);
			$this->add_control(
				'ma_el_tabs_content_padding',
				[
					'label' => esc_html__( 'Padding', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'default' => [
						'top' => 40,
						'right' => 40,
						'bottom' => 40,
						'left' => 40,
						'isLinked' => true,
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-advance-tab .ma-el-advance-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


			$this->end_controls_section();




			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'ma_el_section_pro_style_section',
					[
						'label' => esc_html__( 'Upgrade to Pro Nested Tabs', MELA_TD ),
					]
				);

				$this->add_control(
					'ma_el_control_get_pro_style_tab',
					[
						'label' => esc_html__( 'Unlock more possibilities', MELA_TD ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'1' => [
								'title' => esc_html__( '', MELA_TD ),
								'icon' => 'fa fa-unlock-alt',
							],
						],
						'default' => '1',
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}


		}

		protected function render() {

			$settings = $this->get_settings_for_display();

			$this->add_render_attribute(
				'ma_el_tab_wrapper',
				[
					'id'     => "ma-el-advance-tabs-{$this->get_id()}",
					'class'	 => [ 'ma-el-advance-tab', $settings['ma_el_tabs_preset'] ],
					'data-tab-effect' => $settings['ma_el_tabs_effect']
				]
			);


			?>
			<div <?php echo $this->get_render_attribute_string('ma_el_tab_wrapper'); ?> data-tabs>

				<ul class="ma-el-advance-tab-nav">
					<?php foreach( $settings['ma_el_tabs'] as $tab ) : ?>
						<li class="<?php echo esc_attr( $tab['ma_el_tab_show_as_default'] ); ?>" data-tab>
							<?php if( $settings['ma_el_tabs_icon_show'] === 'yes' ) :
								if( $tab['ma_el_tabs_icon_type'] === 'icon' ) : ?>
									<i class="<?php echo esc_attr( $tab['ma_el_tab_title_icon'] ); ?>"></i>
								<?php elseif( $tab['ma_el_tabs_icon_type'] === 'image' ) : ?>
									<img src="<?php echo esc_attr( $tab['ma_el_tab_title_image']['url'] );
									?>">
								<?php endif; ?>
							<?php endif; ?>
							<span class="ma-el-tab-title"><?php echo $tab['ma_el_tab_title']; ?></span></li>
					<?php endforeach; ?>
				</ul>


				<?php foreach( $settings['ma_el_tabs'] as $tab ) : $ma_el_find_default_tab[] = $tab['ma_el_tab_show_as_default'];?>
					<div class="ma-el-advance-tab-content <?php echo esc_attr( $tab['ma_el_tab_show_as_default']
					); ?>">
						<h3 class="ma-el-advance-tab-content-title">
							<?php echo $tab['ma_el_tab_title'];?>
						</h3>
						<p><?php

                                // Nested Accordion Available for Premium Version
								if ( ma_el_fs()->can_use_premium_code() ) {

									if ( $tab['ma_tabs_content_type'] == 'content' ) {

										echo do_shortcode( $tab['ma_el_tab_content'] );

									} else if ( $tab['ma_tabs_content_type'] == 'section' && ! empty( $tab['saved_section'] ) ) {

										echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_section'] );

									} else if ( $tab['ma_tabs_content_type'] == 'template' && ! empty( $tab['templates'] ) ) {

										echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['templates'] );

									} else if ( $tab['ma_tabs_content_type'] == 'widget' && ! empty( $tab['saved_widget'] ) ) {

										echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_widget'] );

									}

									// Free Version Code
								} else{

										echo do_shortcode( $tab['ma_el_tab_content'] );
								}


								?></p>
					</div>
				<?php endforeach; ?>

			</div>
			<?php
		}



		public function get_page_template_options( $type = '' ) {

			$page_templates = Master_Addons_Helper::ma_get_page_templates( $type );

			$options[-1]   = __( 'Select', MELA_TD );

			if ( count( $page_templates ) ) {
				foreach ( $page_templates as $id => $name ) {
					$options[ $id ] = $name;
				}
			} else {
				$options['no_template'] = __( 'No saved templates found!', MELA_TD );
			}

			return $options;
		}


	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Tabs() );