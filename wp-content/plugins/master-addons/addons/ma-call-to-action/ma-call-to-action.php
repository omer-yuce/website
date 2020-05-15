<?php
	namespace Elementor;

	use Elementor\Widget_Base;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/25/19
	 */

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Call_To_Action extends Widget_Base {

		public function get_name() {
			return 'ma-call-to-action';
		}

		public function get_title() {
			return esc_html__( 'MA Call to Action', MELA_TD);
		}

		public function get_icon() {
			return 'ma-el-icon eicon-call-to-action';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		protected function _register_controls() {

			/**
			 * Master Call to Action: Content
			 */
			$this->start_controls_section(
				'ma_el_call_to_action_content_section',
				[
					'label' => esc_html__( 'Content', MELA_TD ),
				]
			);

			$this->add_control(
				'ma_el_call_to_action_title',
				[
					'label' => esc_html__( 'CTA Content', MELA_TD ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
					'default' => esc_html__( 'Purchase Master Addons now and unlimited Options', MELA_TD ),
				]
			);


			$this->add_control(
				'ma_el_call_to_action_content_desc',
				[
					'label' => esc_html__( 'Description', MELA_TD ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', MELA_TD ),

					// 'condition' => [
					// 	'ma_el_call_to_action_style_preset' => 'style2',
					// ],
				]
			);

			$this->add_control(
				'ma_el_call_to_action_button_text',
				[
					'label' => esc_html__( 'Button Text', MELA_TD ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Purchase Now', MELA_TD ),
				]
			);

			$this->add_control(
				'ma_el_call_to_action_button_link',
				[
					'label' => __( 'Call To Action URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://jeweltheme.com/shop/master-addons-elementor', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '#',
						'is_external' => true,
					],
				]
			);

			$this->add_control(
				'ma_el_call_to_action_icon',
				[
					'label'     => __( 'Icon', MELA_TD ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-bell',
					'condition' => [
						'ma_el_call_to_action_style_preset' => [ 'style-07'],
					],
				]
			);


			$this->end_controls_section();


			/**
			 * Master Addons: Call to Action Content Section
			 */
			$this->start_controls_section(
				'ma_el_call_to_action_style',
				[
					'label' => esc_html__( 'Style Presets', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_call_to_action_style_preset',
				[
					'label' => esc_html__( 'Style Preset', MELA_TD ),
					'type' => Controls_Manager::SELECT,
					'default' => 'style-01',
					'options' => [
						'style-01' => __( 'Style 01', MELA_TD ),
						'style-02' => __( 'Style 02', MELA_TD ),
						'style-03' => __( 'Style 03', MELA_TD ),
						'style-04' => __( 'Style 04', MELA_TD ),
						'style-05' => __( 'Background Color', MELA_TD ),
						'style-06' => __( 'Gradient Background', MELA_TD ),
						'style-07' => __( 'Gradient with Icon', MELA_TD )
					],
				]
			);


			$this->add_control(
				'ma_el_call_to_action_icon_color',
				[
					'label'		=> esc_html__( 'Icon Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover, 
						{{WRAPPER}} .style-07 .media-left i' => 'color: {{VALUE}};',
					],

					'condition' => [
						'ma_el_call_to_action_style_preset' => [ 'style-07'],
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'ma_el_call_to_action_desc_bg',
					'label' => __( 'Background', MELA_TD ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .ma-el-call-to-action',
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);



			$this->add_control(
				'ma_el_call_to_action_border_color',
				[
					'label'		=> esc_html__( 'Border Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#4b00e7',
					'selectors'	=> [
						'{{WRAPPER}} .style-03 .ma-el-action-content .row' => 'border-left: 10px solid {{VALUE}};',

						'{{WRAPPER}} .style-04 .ma-el-action-content .row' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover' => 'color: {{VALUE}};',
					],

					'condition' => [
						'ma_el_call_to_action_style_preset' => [ 'style-03', 'style-04'],
					],
				]
			);

			$this->end_controls_section();


			$this->start_controls_section(
				'ma_el_call_to_action_title_style_section',
				[
					'label' => __('Title Style', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);


			$this->add_control(
				'ma_el_call_to_action_title_color',
				[
					'label'		=> esc_html__( 'Title Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#393c3f',
					'selectors'	=> [

						'{{WRAPPER}} .style-02 .ma-el-action-title' => 'color: #fff;',
						'{{WRAPPER}} .style-05 .ma-el-action-title' => 'color: #fff;',

						'{{WRAPPER}} .ma-el-action-title' => 'color: {{VALUE}} !important;',
					],

				]
			);


			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_cta_title_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ma-el-action-title',
				]
			);


			$this->end_controls_section();




			$this->start_controls_section(
				'ma_el_call_to_action_desc_style_section',
				[
					'label' => __('Description Style', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_call_to_action_description_color',
				[
					'label'		=> esc_html__( 'Text Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#78909c',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-description' => 'color: {{VALUE}};'
					],
//
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-05','style-06', 'style-07'],
//					],
				]
			);




			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_call_to_action_text_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ma-el-action-description',
				]
			);

			$this->end_controls_section();



			$this->start_controls_section(
				'ma_el_call_to_action_button_section',
				[
					'label' => __('Button Style', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);


			$this->start_controls_tabs( 'ma_el_call_to_action_button_style_tabs' );

			$this->start_controls_tab( 'ma_el_call_to_action_button_style_tab',
				[ 'label' => esc_html__( 'Normal', MELA_TD )
				] );

			$this->add_control(
				'ma_el_call_to_action_button_color',
				[
					'label'		=> esc_html__( 'Button Text Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn'=> 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'          => 'ma_el_call_to_action_button_border',
					'selector'      => '{{WRAPPER}} .ma-el-action-content .ma-el-action-btn',
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);

			$this->add_control('ma_el_call_to_action_button_border_radius',
				[
					'label'         => __('Border Radius', MELA_TD),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'selectors'     => [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn' => 'border-radius: {{SIZE}}{{UNIT}};'
					],
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);

			$this->add_control(
				'ma_el_call_to_action_button_bg_color',
				[
					'label'		=> esc_html__( 'Button Background Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#4b00e7',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn'
						=> 'background-color: {{VALUE}};',
					],

//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_call_to_action_button_hover', [ 'label' => esc_html__( 'Hover',
				MELA_TD )
			] );

			$this->add_control(
				'ma_el_call_to_action_button_hover_color',
				[
					'label'		=> esc_html__( 'Button Text Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover'
						=> 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'          => 'ma_el_call_to_action_border_hover',
					'selector'      => '{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover',
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);

			$this->add_control('ma_el_call_to_action_button_border_hover_radius',
				[
					'label'         => __('Border Radius', MELA_TD),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'selectors'     => [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
					],
//					'condition' => [
//						'ma_el_call_to_action_style_preset' => ['style-06','style-07'],
//					],
				]
			);

			$this->add_control(
				'ma_el_call_to_action_button_bg_hover_color',
				[
					'label'		=> esc_html__( 'Button Hover Background Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#4b00e7',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-action-content .ma-el-action-btn:hover'
						=> 'background-color: {{VALUE}};',
					],
				]
			);


			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_call_to_action_button_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ma-el-action-btn',
				]
			);

			$this->end_controls_section();


			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'maad_el_section_pro',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD )
					]
				);

				$this->add_control(
					'maad_el_control_get_pro',
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
						'description' => '<span class="pro-feature"> Upgrade to <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}



		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$this->add_render_attribute( 'ma_el_call_to_action_wrapper', [
				'class'	=> [
					'ma-el-call-to-action'
				],
				'id' => 'ma-el-action-content-' . $this->get_id()
			]);

			?>

            <section <?php echo $this->get_render_attribute_string( 'ma_el_call_to_action_wrapper' ); ?>>
                <div class="<?php echo esc_attr($settings['ma_el_call_to_action_style_preset'] );?>">
                    <div class="ma-el-action-content">
                        <div class="row">
                            <div class="col-lg-9">

                                <?php if( $settings['ma_el_call_to_action_style_preset'] == "style-07"){ ?>
                                    <div class="ma-cta-icon-section media">

                                        <div class="ma-cta-icon media-left">
                                            <i class="<?php echo $settings['ma_el_call_to_action_icon'];?>"></i>
                                        </div>

                                        <div class="media-body">
                                            <h3 class="ma-el-action-title">
                                                <?php echo esc_html( $settings['ma_el_call_to_action_title'] ); ?>
                                            </h3>
                                            <p class="ma-el-action-description">
                                                <?php echo esc_html( $settings['ma_el_call_to_action_content_desc'] ); ?>
                                            </p>
                                        </div>

                                    </div>
                                <?php } else{ ?>
                                    <h3 class="ma-el-action-title">
		                                <?php echo esc_html( $settings['ma_el_call_to_action_title'] ); ?>
                                    </h3>

                                    <p class="ma-el-action-description">
		                                <?php echo esc_html( $settings['ma_el_call_to_action_content_desc'] ); ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <div class="col-lg-3 text-right">
                                <a href="<?php echo esc_url( $settings['ma_el_call_to_action_button_link']['url'] ); ?>" class="ma-el-action-btn">
                                    <?php echo esc_html( $settings['ma_el_call_to_action_button_text'] ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
			<?php
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Call_To_Action() );

