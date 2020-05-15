<?php
	namespace Elementor;

	// Elementor Classes
	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;
	use Elementor\Repeater;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Typography;
	use Elementor\Scheme_Typography;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 10/12/19
	 */
	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * Master Addons: Content Scroll Indicator
	 */
	class Master_Addons_Scroll_Indicator extends Widget_Base {

		public function get_name() {
			return 'ma-scroll-indicator';
		}

		public function get_title() {
			return __( 'Content Scroll Indicator', MELA_TD );
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_icon() {
			return 'ma-el-icon eicon-price-table';
		}

		public function get_keywords() {
			return [ 'scrollbar', 'progress', 'content scroll', 'scroll', 'indicator', 'content scroll indicator'];
		}

    	protected function _register_controls() {

			$this->start_controls_section(
				'ma_el_content_scroll_indicator_section_start',
				[
					'label' => __( 'Scroll Indicator', MELA_TD ),
				]
			);


		    $this->add_control('ma_el_scroll_indicator_bg_color',
			    [
				    'label'         => __('Background Color', MELA_TD ),
				    'type'          => Controls_Manager::COLOR,
				    'default'       => '#fff',
				    'selectors'     => [
					    '{{WRAPPER}} .ma-el-page-scroll-indicator' => 'background: {{VALUE}};',
				    ]
			    ]
		    );

		    $this->add_control(
			    'ma_el_scroll_indicator_height',
			    [
				    'label'         => __( 'Height', MELA_TD ),
				    'type'          => Controls_Manager::NUMBER,
				    'min'			=> 1,
				    'default'       => '5',
				    'selectors'     => [
					    '{{WRAPPER}} .ma-el-page-scroll-indicator,
					    {{WRAPPER}} .ma-el-scroll-indicator' => 'height: {{VALUE}}px;',
				    ]
			    ]
		    );


		    $this->add_control('ma_el_scroll_indicator_progress_bg_color',
			    [
				    'label'         => __('Progress Background Color', MELA_TD ),
				    'type'          => Controls_Manager::COLOR,
				    'default'       => '#007bff',
				    'selectors'     => [
					    '{{WRAPPER}} .ma-el-scroll-indicator' => 'background: {{VALUE}};',
				    ]
			    ]
		    );

			$this->end_controls_tab();
		}


		protected function render() { ?>

			<div class="ma-el-page-scroll-indicator">
	            <div class="ma-el-scroll-indicator"></div>
	        </div>

		<?php }

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Scroll_Indicator());
