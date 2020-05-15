<?php
	namespace Elementor;
	use Elementor\Widget_Base;
	use MasterAddons;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Headlines extends Widget_Base {

		public function get_name() {
			return 'ma-headlines';
		}

		public function get_title() {
			return esc_html__( 'MA Animated Headlines', MELA_TD );
		}

		public function get_icon() {
			return 'ma-el-icon eicon-animated-headline';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_script_depends() {
			return [
				'ma-animated-headlines',
				'master-addons-scripts',
			];
		}

		protected function _register_controls() {

			/**
			 * Master Headlines Content Section
			 */
			$this->start_controls_section(
				'ma_el_headlines_content',
				[
					'label' => esc_html__( 'Content', MELA_TD ),
				]
			);

			$this->add_control(
				'title_html_tag',
				[
					'label'   => __( 'HTML Tag', MELA_TD ),
					'type'    => Controls_Manager::SELECT,
					'options' => Master_Addons_Helper::ma_el_title_tags(),
					'default' => 'h3',
				]
			);

			$this->add_control(
				'ma_el_headlines_first_heading',
				[
					'label' => esc_html__( 'First Heading', MELA_TD ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Master Addons', MELA_TD ),
				]
			);

			$repeater = new Repeater();


			$repeater->add_control(
				'ma_el_headlines_second_heading',
				[
					'label'                 => __( 'More Titles', MELA_TD ),
					'type'                  => Controls_Manager::TEXT,
					'default'               => __( 'Minimal Template', MELA_TD ),
					'dynamic'               => [
						'active'   => true,
					],
				]
			);



			$this->add_control(
				'tabs',
				[
					'type'                  => Controls_Manager::REPEATER,
					'default'               => [
						[ 'ma_el_headlines_second_heading' => esc_html__( 'Ultimate Addons', MELA_TD ) ],
						[ 'ma_el_headlines_second_heading' => esc_html__( 'Elementor Widgets', MELA_TD ) ],
						[ 'ma_el_headlines_second_heading' => esc_html__( 'Unique Design', MELA_TD ) ],
						[ 'ma_el_headlines_second_heading' => esc_html__( 'Unlimited Variations', MELA_TD ) ],
						[ 'ma_el_headlines_second_heading' => esc_html__( 'Unlimited Possibilities', MELA_TD ) ],
					],
					'fields'                => array_values( $repeater->get_controls() ),
					'title_field'           => '{{ma_el_headlines_second_heading}}',
				]
			);


			$this->end_controls_section();


			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'ma_el_section_pro',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD )
					]
				);

				$this->add_control(
					'ma_el_control_get_pro',
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
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with
Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}



			/*
            * Style Presets
            */
			$this->start_controls_section(
				'ma_el_headlines_style_preset_section',
				[
					'label' => esc_html__( 'Style Presets', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);


			$this->add_responsive_control(
				'ma_el_headlines_alignment',
				[
					'label' => esc_html__( 'Alignment', MELA_TD ),
					'type' => Controls_Manager::CHOOSE,
					'label_block' => true,
					'options' => [
						'flex-start' => [
							'title' => esc_html__( 'Left', MELA_TD ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', MELA_TD ),
							'icon' => 'fa fa-align-center',
						],
						'flex-end' => [
							'title' => esc_html__( 'Right', MELA_TD ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .ma-el-animated-headline' => 'justify-content: {{VALUE}};',
					],
				]
			);
			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {

				$this->add_control(
					'ma_el_headlines_style_preset',
					[
						'label'       	=> esc_html__( 'Style Preset', MELA_TD ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'rotate-1',
						'label_block' 	=> false,
						'options' 		=> [
                            'rotate-1'          => esc_html__( 'Rotate 1', MELA_TD ),
                            'rotate-2'          => esc_html__( 'Rotate 2', MELA_TD ), //problem
                            'rotate-3'          => esc_html__( 'Rotate 3', MELA_TD ),//problem
                            'type'              => esc_html__( 'Typing', MELA_TD ),
                            'loading-bar'       => esc_html__( 'Loading Bar', MELA_TD ),
                            'slide'             => esc_html__( 'Slide', MELA_TD ),
                            'clip'              => esc_html__( 'Clip', MELA_TD ),
                            'zoom'              => esc_html__( 'Zoom', MELA_TD ),
                            'scale'             => esc_html__( 'Scale', MELA_TD ),
                            'push'              => esc_html__( 'Push', MELA_TD )
						],
					]
				);

			//Free Version Codes
			} else {
				$this->add_control(
					'ma_el_headlines_style_preset',
					[
						'label'       	=> esc_html__( 'Style Preset', MELA_TD ),
						'type' 			=> Controls_Manager::SELECT,
						'default' 		=> 'rotate-1',
						'label_block' 	=> false,
						'options' 		=> [
							'rotate-1'          => esc_html__( 'Rotate 1', MELA_TD ),
							'rotate-2'          => esc_html__( 'Rotate 2', MELA_TD ), //problem
							'rotate-3'          => esc_html__( 'Rotate 3', MELA_TD ),//problem
							'loading-bar'       => esc_html__( 'Loading Bar', MELA_TD ),
							'ma-heading-pro-1'  => esc_html__( 'Typing (Pro)', MELA_TD ),
							'ma-heading-pro-2'  => esc_html__( 'Slide (Pro)', MELA_TD ),
							'ma-heading-pro-3'  => esc_html__( 'Clip (Pro)', MELA_TD ),
							'ma-heading-pro-4'  => esc_html__( 'Zoom (Pro)', MELA_TD ),
							'ma-heading-pro-5'  => esc_html__( 'Scale (Pro)', MELA_TD ),
							'ma-heading-pro-6'  => esc_html__( 'Push (Pro)', MELA_TD )
						],
						'description' => sprintf( '7+ more effects on <a href="%s" target="_blank">%s</a>',
							esc_url_raw( admin_url('admin.php?page=master-addons-settings-pricing') ),
							__( 'Upgrade Now', MELA_TD ) )
					]
				);
			}

			$this->end_controls_section();

			/*
            * Master Headlines First Part Styling Section
            */

			$this->start_controls_section(
				'ma_el_headlines_first_heading_styles',
				[
					'label' => esc_html__( 'First Heading', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);


			$this->add_control(
				'ma_el_headlines_first_text_color',
				[
					'label'		=> esc_html__( 'Text Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .first-heading'
						=> 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ma_el_headlines_first_bg_color',
				[
					'label' => __( 'Background', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#704aff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .first-heading'
						=> 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_headlines_first_heading_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .first-heading',
				]
			);

			$this->end_controls_section();

			/*
			* Master Headlines Second Part Styling Section
			*/
			$this->start_controls_section(
				'ma_el_headlines_second_heading_styles',
				[
					'label' => esc_html__( 'Second Heading', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control(
				'ma_el_headlines_second_text_color',
				[
					'label'		=> esc_html__( 'Text Color', MELA_TD ),
					'type'		=> Controls_Manager::COLOR,
					'default' => '#132C47',
					'selectors'	=> [
						'{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .second-heading' =>
							'color: {{VALUE}}; font-style: normal; font-weight: normal;',
					],
				]
			);

			$this->add_control(
				'ma_el_headlines_second_bg_color',
				[
					'label' => __( 'Background', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .second-heading' =>
							'background-color: {{VALUE}}; line-height:1.3;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_headlines_second_heading_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ma-el-animated-heading .ma-el-animated-heading-wrapper .ma-el-animated-heading-title .second-heading',
				]
			);


			$this->end_controls_section();



			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'ma_el_section_pro_style_section',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD ),
                        'tab' => Controls_Manager::TAB_STYLE
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
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with
Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}

		}

		protected function render() {
			$settings = $this->get_settings_for_display();

			switch ($settings['ma_el_headlines_style_preset']){
				case "rotate-2":
					$letters_class = "letters";
					break;
				case "rotate-3":
					$letters_class = "letters";
					break;
				case "type":
					$letters_class = "letters";
					break;
				case "scale":
					$letters_class = "letters";
					break;
				default:
					$letters_class = "";
			}

			?>

                <div id="ma-el-heading-<?php echo esc_attr($this->get_id()); ?>" class="ma-el-animated-heading">
                    <div class="ma-el-animated-heading-wrapper">
                        <<?php echo $settings['title_html_tag']; ?>
                            class="ma-el-animated-heading-title ma-el-animated-headline <?php echo esc_html(
                                    $letters_class . ' ' . $settings['ma_el_headlines_style_preset'] ); ?> main-title">
                            <span class="first-heading">
                                <?php echo esc_html( $settings['ma_el_headlines_first_heading'] ); ?>
                            </span>
                            <span class="ma-el-words-wrapper">
                                <?php foreach( $settings['tabs'] as $index => $tab ) { ?>
                                    <b class="second-heading <?php echo ($index==0) ? "is-visible": "";?>">
                                        <?php echo $tab['ma_el_headlines_second_heading']; ?>
                                    </b>
                                <?php } ?>
                            </span>
                        </<?php echo $settings['title_html_tag']; ?>>
                    </div>
                </div>
            </div>

			<?php
		}


	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Headlines() );