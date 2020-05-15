<?php
	namespace Elementor;

	// Elementor Classes
    use Elementor\Widget_Base;
    use Elementor\Group_Control_Typography;
    use Elementor\Scheme_Color;
    use Elementor\Scheme_Typography;    
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 02/04/2020
	 */

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Current_Time extends Widget_Base {

		public function get_name() {
			return 'ma-el-current-time';
		}

		public function get_title() {
			return esc_html__( 'MA Current Time', MELA_TD );
		}

		public function get_icon() {
			return 'ma-el-icon eicon-clock-o';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		protected function _register_controls() {

			/**
			 * Current Time
			 */
			$this->start_controls_section(
				'ma_el_current_time_content',
				[
					'label' => esc_html__( 'General', MELA_TD ),
				]
			);

            $this->add_control(
                'ma_el_current_time_type',
                array(
                    'label'       => __('Type of time', MELA_TD),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'custom',
                    'options'     => array(
                       'custom'    => __('Custom'   , MELA_TD ),
                       'mysql'     => __('MySql' , MELA_TD ),
                       'timestamp' => __('TimeStamp'    , MELA_TD )
                    )
                )
            );
    
            $this->add_control(
                'ma_el_current_time_date_format',
                array(
                    'label'        => __('Date Format String', MELA_TD ),
                    'type'         => Controls_Manager::SELECT,
                    'default'      => get_option( 'date_format' ),
                    'description' => '<span class="pro-feature"> <a href="' . esc_url_raw('https://developer.wordpress.org/reference/functions/the_time/') . '" target="_blank">Date Time Format Examples </a> </span>',
                    'condition'    => array(
                        'ma_el_current_time_type' => array('custom'),
                    )
                )
            );

            $this->add_responsive_control(
                'ma_el_current_time_date_alignment',
                array(
                    'label'      => __('Alignment', MELA_TD),
                    'type'       => Controls_Manager::CHOOSE,
                    'options'    => array(
                        'left' => array(
                            'title' => __( 'Left', MELA_TD ),
                            'icon' => 'fa fa-align-left',
                        ),
                        'center' => array(
                            'title' => __( 'Center', MELA_TD ),
                            'icon' => 'fa fa-align-center',
                        ),
                        'right' => array(
                            'title' => __( 'Right', MELA_TD ),
                            'icon' => 'fa fa-align-right',
                        ),
                    ),
                    'toggle'     => true,
                    'selectors'  => array(
                        '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                    )
                )
            );
    
            $this->end_controls_section();
            

            /*
            * Style for Current Time
            */
            $this->start_controls_section(
                'ma_el_current_time_style',
                array(
                    'label'      => __('Text', MELA_TD ),
                    'tab'       => Controls_Manager::TAB_STYLE,
                )
            );

            $this->add_control(
                'ma_el_current_time_text_color',
                array(
                    'label' => __( 'Color', MELA_TD ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} .ma-el-current-time' => 'color: {{VALUE}};',
                    )
                )
            );
    
            $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                array(
                    'name' => 'ma_el_current_time_text_shadow',
                    'label' => __( 'Text Shadow', MELA_TD ),
                    'selector' => '{{WRAPPER}} .ma-el-current-time',
                )
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name' => 'ma_el_current_time_text_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ma-el-current-time'
                )
            );

            $this->end_controls_section();


		}

		protected function render() {
				$settings = $this->get_settings_for_display();
                $current_time_type     = $settings['ma_el_current_time_type'] === 'custom' ? $settings['ma_el_current_time_date_format'] : $settings['ma_el_current_time_type'];

                echo sprintf( '<div class="ma-el-current-time">%s</div>', current_time( esc_html( $current_time_type ) ) );
            
		}



	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Current_Time() );