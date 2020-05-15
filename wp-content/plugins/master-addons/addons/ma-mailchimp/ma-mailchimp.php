<?php
	namespace Elementor;

	//	namespace MasterAddons\Widget\Addons;

	use Elementor\Widget_Base;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 1/1/20
	 */


	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Mailchimp extends Widget_Base {

		public function get_name() {
			return 'ma-el-mailchimp';
		}

		public function get_title() {
			return __( 'MA Mailchimp', MELA_TD );
		}

		public function get_icon() {
			return 'ma-el-icon eicon-mailchimp';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function jltma_get_forms(){

			$options = array( 0 => __('Select Form', MELA_TD));

			if( !function_exists('mc4wp_get_forms')){
				return $options;
			}
			$forms = mc4wp_get_forms();
			foreach ($forms as $form ){
				$options[ $form->ID ] = $form->name;
			}

			return $options;
		}


		protected function _register_controls() {

			/*
			 * Content Tab
			 */
			$this->start_controls_section(
				'ma_el_mailchimp_form_section',
				[
					'label'      => __('Form', MELA_TD )
				]
			);

//			You can edit your sign-up form in the Mailchimp for WordPress form settings.

			$this->add_control(
				'ma_el_mailchimp_form_type',
				[
					'label'       => __( 'Form Type', MELA_TD ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'default',
					'options'     => array(
						'default' => __( 'Defaults'  , MELA_TD ),
						'custom'  => __( 'Custom'  , MELA_TD )
					)
				]
			);

			$this->add_control(
				'ma_el_mailchimp_form_id',
				array(
					'label'       => __( 'MailChimp Sign-Up Form', MELA_TD ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'default'     => 0,
					'options'     => $this->jltma_get_forms(),
					'condition'   => array(
						'ma_el_mailchimp_form_type' => array('default')
					)
				)
			);

			$this->add_control(
				'ma_el_mailchimp_html',
				array(
					'label'       => __( 'Custom Form', MELA_TD ),
					'type'        => Controls_Manager::CODE,
					'language'    => 'html',
					'description' => __( 'Enter your custom form markup', MELA_TD ),
					'condition'   => array(
						'ma_el_mailchimp_form_type' => array('custom')
					)
				)
			);

			$this->end_controls_section();


		}


		public function jltma_render_custom_form($content){
			$settings = $this->get_settings_for_display();

			if(!empty( $settings['ma_el_mailchimp_html'] )){
				$content = $settings['ma_el_mailchimp_html'];
			}
			return $content;
		}


		protected function render() {
			$settings = $this->get_settings_for_display();

			// Check whether required resources are available
			if( ! function_exists('mc4wp_show_form') ) {
				Master_Addons_Helper::jltma_elementor_plugin_missing_notice( array( 'plugin_name' => __( 'MailChimp',
					MELA_TD ) ) );
				return;
			}

			if(  $settings['ma_el_mailchimp_form_type'] === 'custom' ) {
				add_filter( 'mc4wp_form_content', array( $this, 'jltma_render_custom_form'), 10, 1 );
				$settings['ma_el_mailchimp_form_id'] = 0;
			} elseif( get_post_type( $settings['ma_el_mailchimp_form_id'] ) !== 'mc4wp-form' ){
				$settings['ma_el_mailchimp_form_id'] = 0;
			}

			return mc4wp_show_form( $settings['ma_el_mailchimp_form_id'] );


		}



	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Mailchimp() );

