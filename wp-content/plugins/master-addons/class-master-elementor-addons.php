<?php
	namespace MasterAddons;

	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

	if( !class_exists('Master_Elementor_Addons') ){
		final class Master_Elementor_Addons {

			static public $class_namespace = '\\MasterAddons\\Inc\\Classes\\';
			public $controls_manager;

			const VERSION = "1.3.8";

			const MINIMUM_PHP_VERSION = '5.4';

			const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

			private $_localize_settings = [];

			private static $plugin_path;
			private static $plugin_url;
			private static $plugin_slug;
			public static $plugin_dir_url;
			public static $plugin_name = 'Master Addons for Elementor';

			private static $instance = null;

			public $pro_enabled;

			public static $maad_el_default_widgets;
			public static $maad_el_pro_widgets;
			public static $ma_el_extensions;
			public static $ma_el_pro_extensions;


			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;

					self::$instance -> ma_el_init();
				}

				return self::$instance;
			}


			public function __construct() {

				self::$maad_el_default_widgets = [
					'ma-animated-headlines',    // 0
					'ma-call-to-action',        // 1
					'ma-dual-heading',          // 2
					'ma-accordion',             // 3
					'ma-tabs',                  // 4
					'ma-tooltip',               // 5
					'ma-progressbar',           // 6
					'ma-progressbars',          // 7
					'ma-team-members',          // 8
					'ma-team-members-slider',   // 9
					'ma-creative-buttons',      // 10
					'ma-changelog',             // 11
					'ma-infobox',               // 12
					'ma-flipbox',               // 13
					'ma-creative-links',        // 14
					'ma-image-hover-effects',   // 15
					'ma-blog',                  // 16
					['ma-news-ticker','pro'],   // 17
					'ma-timeline',              // 18
					'ma-business-hours',        // 19
					'ma-table-of-contents',     // 20
					['ma-image-hotspot','pro'], // 21
					'ma-image-filter-gallery',  // 22
//					'ma-image-carousel',        // 23
			        // 'ma-scroll-indicator',      // 24
			        'ma-pricing-table',         // 25
//					'ma-image-comparison',      // 26
//					'ma-advanced-image',        // 27
					['ma-restrict-content','pro'],  // 28
					'ma-current-time',      		// 29
					['ma-domain-checker','pro'],    // 30
					'ma-table',
					'ma-navmenu',
					'ma-search',



					// 'ma-twitter-slider',      		// 31

//			        'testimonial-carousel',		// 32
//					'ma-instagram-feed',        // 33
//			        'google-maps',              // 34
					'ma-counter-up',			// 35
					'ma-countdown-timer',		// 36

//					'ma-profile-cards',			// 37
//					'ma-piechart',				// 38

					// Form Elements
					'contact-form-7',           // 15
					'ninja-forms',              // 16
					'wpforms',                  // 18
					['gravity-forms','pro'],    // 17
					'caldera-forms',            // 19
					'weforms',                  // 20

					// Slider Elements
//					'layerslider',              // 21

					// Marketing Addons
					'ma-mailchimp',             // 20

				];


				self::$maad_el_pro_widgets =[
					'gravity-forms',
					'ma-news-ticker',
					'ma-image-hotspot',
				];

				// Extensions
				self::$ma_el_extensions = [
					'particles',
					['animated-gradient','pro'],
					'bg-slider',
					'custom-css',
					'positioning',
					'extras',
					'mega-menu',
					['transition', 'pro'],
					['transforms','pro'],
					['rellax','pro'],
					['reveal','pro']
//					'pseudo-elements', //need to fix before and after
				];

				self::$ma_el_pro_extensions = [
					'animated-gradient',
					'rellax',
					'reveal',
					'transforms',
					'transition'
				];


				// search for pro version
//				$this->pro_enabled = apply_filters( 'ma_el/pro_enabled', false );


				$this->constants();
				$this->maad_el_include_files();
				$this->jltma_load_extensions();
				$this->mela_define_admin_hooks();


				self::$plugin_slug = 'master-addons';
				self::$plugin_path = untrailingslashit( plugin_dir_path( '/', __FILE__ ) );
				self::$plugin_url  = untrailingslashit( plugins_url( '/', __FILE__ ) );

				// Initialize Plugin
				add_action('plugins_loaded', [$this, 'ma_el_plugins_loaded']);


				//Init Function
//				add_action( 'init', [ $this, 'ma_el_init' ] );

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_actions_links' ] );



				add_action( 'elementor/init', [ $this, 'mela_category' ] );

				add_action('elementor/init', [$this, 'jltma_add_actions_to_elementor'], 0);

				// Enqueue Styles and Scripts
				add_action( 'wp_enqueue_scripts', [ $this, 'maad_el_enqueue_scripts' ], 20 );


				// Elementor Dependencies
				add_action( 'elementor/editor/before_enqueue_scripts'  , array( $this, 'jltma_editor_scripts' ) );
				add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'jltma_editor_scripts' ]);

//				add_action( 'elementor/editor/footer', [ $this, 'load_footer_scripts' ]);


				// Add Elementor Widgets
				add_action( 'elementor/widgets/widgets_registered', [ $this, 'jltma_init_widgets' ] );

				//Register Controls
				add_action( 'elementor/controls/controls_registered'   , array( $this, 'jltma_register_controls' ) );

				//		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'ma_el_enqueue_frontend_scripts' ] );
				//		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'ma_el_enqueue_frontend_styles' ] );


				//Body Class
				add_action( 'body_class', [ $this, 'jltma_body_class' ] );

			}


			public function ma_el_init() {

				$this->mela_load_textdomain();
				$this->ma_el_image_size();

				//Redirect Hook
				add_action( 'admin_init', [ $this, 'mael_ad_redirect_hook' ]);

			}

			public static function jltma_elementor() {
				return \Elementor\Plugin::$instance;
			}

			// Initialize
			public function ma_el_plugins_loaded(){

				// Check if Elementor installed and activated
				if ( ! did_action( 'elementor/loaded' ) ) {
					add_action( 'admin_notices', array( $this, 'mela_admin_notice_missing_main_plugin' ) );
					return;
				}

				// Check for required Elementor version
				if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
					add_action( 'admin_notices', array( $this, 'mela_admin_notice_minimum_elementor_version' ) );
					return;
				}

				// Check for required PHP version
				if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
					add_action( 'admin_notices', array( $this, 'mela_admin_notice_minimum_php_version' ) );
					return;
				}

			}


			public function constants() {

				//Defined Constants

				if ( ! defined( 'MELA' ) ) {
					define( 'MELA', self::$plugin_name );
				}

				if (!defined('MA_EL_BADGE')) {
					define( 'MA_EL_BADGE', '<span class="ma-el-badge"></span>' );
				}

				if ( ! defined( 'MELA_VERSION' ) ) {
					define( 'MELA_VERSION', self::version() );
				}

				if ( ! defined( 'MA_EL_SCRIPT_SUFFIX' ) ) {
					define( 'MA_EL_SCRIPT_SUFFIX', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
				}

				if ( ! defined( 'MELA_BASE' ) ) {
					define( 'MELA_BASE', plugin_basename( __FILE__ ) );
				}

				if ( ! defined( 'MELA_PLUGIN_URL' ) ) {
					define( 'MELA_PLUGIN_URL', self::mela_plugin_url() );
				}

				if ( ! defined( 'MELA_PLUGIN_PATH' ) ) {
					define( 'MELA_PLUGIN_PATH', self::mela_plugin_path() );
				}

				if ( ! defined( 'MELA_PLUGIN_PATH_URL' ) ) {
					define( 'MELA_PLUGIN_PATH_URL', self::mela_plugin_dir_url() );
				}

				if ( ! defined( 'MELA_IMAGE_DIR' ) ) {
					define( 'MELA_IMAGE_DIR', self::mela_plugin_dir_url() . '/assets/images/' );
				}

				if ( ! defined( 'MELA_ADMIN_ASSETS' ) ) {
					define( 'MELA_ADMIN_ASSETS', self::mela_plugin_dir_url() . '/inc/admin/assets/' );
				}

				if ( ! defined( 'MAAD_EL_ADDONS' ) ) {
					define( 'MAAD_EL_ADDONS', plugin_dir_path( __FILE__ ) . 'addons/' );
				}

				if ( ! defined( 'MELA_TEMPLATES' ) ) {
					define( 'MELA_TEMPLATES', plugin_dir_path( __FILE__ ) . 'inc/template-parts/' );
				}

				// Master Addons Text Domain
				if ( ! defined( 'MELA_TD' ) ) {
					define( 'MELA_TD', $this->mela_load_textdomain() );
				}

				if ( ! defined( 'MELA_FILE' ) ) {
					define( 'MELA_FILE', __FILE__ );
				}

				if ( ! defined( 'MELA_DIR' ) ) {
					define( 'MELA_DIR', dirname( __FILE__ ) );
				}

				if(ma_el_fs()->can_use_premium_code()){
					if ( ! defined( 'MASTER_ADDONS_PRO_ADDONS_VERSION' ) ) {
						define( 'MASTER_ADDONS_PRO_ADDONS_VERSION', ma_el_fs()->can_use_premium_code() );
					}
				}

			}



			function mela_category() {

				\Elementor\Plugin::instance()->elements_manager->add_category(
					'master-addons',
					[
						'title' => esc_html__( 'Master Addons', MELA_TD ),
						'icon'  => 'font',
					],
					1 );
			}

			public function ma_el_image_size() {
				add_image_size( 'master_addons_team_thumb', 250, 330, true );
			}


			public static function activated_widgets() {

				$maad_el_default_settings = array_fill_keys( ma_el_array_flatten( self::$maad_el_default_widgets ),true );

				$maad_el_get_settings     = get_option( 'maad_el_save_settings', $maad_el_default_settings );
				$maad_el_new_settings     = array_diff_key( $maad_el_default_settings, $maad_el_get_settings );

				if ( ! empty( $maad_el_new_settings ) ) {
					$maad_el_updated_settings = array_merge( $maad_el_get_settings, $maad_el_new_settings );
					update_option( 'maad_el_save_settings', $maad_el_updated_settings );
				}

				return $maad_el_get_settings = get_option( 'maad_el_save_settings', $maad_el_default_settings );

			}




			public static function activated_extensions() {

				$ma_el_default_extensions_settings = array_fill_keys( ma_el_array_flatten( self::$ma_el_extensions ),true );

				$maad_el_get_extension_settings     = get_option( 'ma_el_extensions_save_settings', $ma_el_default_extensions_settings );
				$maad_el_new_extension_settings     = array_diff_key( $ma_el_default_extensions_settings, $maad_el_get_extension_settings );

				if ( ! empty( $maad_el_new_extension_settings ) ) {
					$maad_el_updated_extension_settings = array_merge( $maad_el_get_extension_settings,
						$maad_el_new_extension_settings );
					update_option( 'ma_el_extensions_save_settings', $maad_el_updated_extension_settings );
				}

				return $maad_el_get_extension_settings = get_option( 'ma_el_extensions_save_settings',$ma_el_default_extensions_settings );

			}


			public function jltma_add_actions_to_elementor(){

				$classes = glob( MELA_PLUGIN_PATH . '/inc/classes/JLTMA_*.php');

				// include all classes
				foreach ($classes as $key => $value) {
					require_once $value;
				}

				// instance all classes
				foreach ($classes as $key => $value) {
					$name = pathinfo($value, PATHINFO_FILENAME);
					$class = self::$class_namespace . $name;
					$this->jltma_classes[strtolower($name)] = new $class();
				}
			}

			public function jltma_register_controls($controls_manager){

				$controls_manager = \Elementor\Plugin::$instance->controls_manager;

				$controls = array(
					'jltma-visual-select' => array(
						'file'  => MELA_PLUGIN_PATH . '/inc/controls/visual-select.php',
						'class' => 'MasterAddons\Inc\Controls\MA_Control_Visual_Select',
						'type'  => 'single'
					),
					'transforms' => array(
						'file'  => MELA_PLUGIN_PATH . '/inc/controls/transforms.php',
						'class' => 'MasterAddons\Inc\Controls\MA_Control_Transforms',
						'type'  => 'single'
					),
					'transform-element' => array(
						'file'  => MELA_PLUGIN_PATH . '/inc/controls/group/transforms.php',
						'class' => 'MasterAddons\Inc\Controls\MA_Transform_Element',
						'type'  => 'group'
					),
					'xy_positions' => array(
						'file'  => MELA_PLUGIN_PATH . '/inc/controls/xy-position.php',
						'class' => 'MasterAddons\Inc\Controls\MA_XY_Position',
						'type'  => 'single'
					),


				);

				foreach ( $controls as $control_type => $control_info ) {
					if( ! empty( $control_info['file'] ) && ! empty( $control_info['class'] ) ){

						include_once( $control_info['file'] );

						if( class_exists( $control_info['class'] ) ){
							$class_name = $control_info['class'];
						} elseif( class_exists( __NAMESPACE__ . '\\' . $control_info['class'] ) ){
							$class_name = __NAMESPACE__ . '\\' . $control_info['class'];
						}

						if( $control_info['type'] === 'group' ){
							$controls_manager->add_group_control( $control_type, new $class_name() );
						} else {
							$controls_manager->register_control( $control_type, new $class_name() );
						}

					}
				}

			}

			public function jltma_init_widgets() {

				$activated_widgets = $this->activated_widgets();

				foreach ( ma_el_array_flatten( self::$maad_el_default_widgets ) as $widget ) {

					if ( $activated_widgets[ $widget ] == true ) {
						if( in_array( $widget, self::$maad_el_pro_widgets )){
							if ( ma_el_fs()->can_use_premium_code() ) {
								require_once MAAD_EL_ADDONS . $widget . '/' . $widget . '.php';
							}
						} else {
							require_once MAAD_EL_ADDONS . $widget . '/' . $widget . '.php';
						}
					}

				}

			}




			public function jltma_load_extensions(){

				// Extension
				$activated_extensions = self::activated_extensions();

				foreach ( self::$ma_el_extensions as $extensions ) {

					$is_pro = "";

					if ( isset( $extensions ) ) {
						if ( is_array( $extensions ) ) {
							$is_pro = $extensions[1];
							$extensions = $extensions[0];

							if ( ma_el_fs()->can_use_premium_code() ) {
								if ( $activated_extensions[ $extensions ] == true && $is_pro == "pro" ) {
									include_once MELA_PLUGIN_PATH . '/inc/modules/' . $extensions . '/' . $extensions . '.php';
								}
							}
						}
					}

					if ( $activated_extensions[ $extensions ] == true && $is_pro !="pro") {
						// if ( $extensions == 'mega-menu' ) {
							// if( !function_exists( 'master_mega_menu' )){
								// include MELA_PLUGIN_PATH . '/inc/modules/mega-menu/mega-menu.php';
							// }
						// }else{
							include_once MELA_PLUGIN_PATH . '/inc/modules/' . $extensions . '/' .  $extensions . '.php';
						// }
					}

				}

			}


			/**
			 *
			 * Enqueue Elementor Editor Styles
			 *
			 */
			public function jltma_editor_scripts() {
				wp_enqueue_style( 'master-addons-editor', MELA_PLUGIN_URL . '/assets/css/master-addons-editor.css' );
				wp_enqueue_script( 'master-addons-editor', MELA_ADMIN_ASSETS . 'js/editor.js', array( 'jquery' ), MELA_VERSION, true );
			}


			/**
			 * Enqueue Plugin Styles and Scripts
			 *
			 */
			public function maad_el_enqueue_scripts() {

				$is_activated_widget = $this->activated_widgets();
				$is_activated_extensions = $this->activated_extensions();

				wp_enqueue_style( 'bootstrap', MELA_PLUGIN_URL . '/assets/css/bootstrap.min.css' );

				wp_enqueue_style( 'master-addons-main-style', MELA_PLUGIN_URL . '/assets/css/master-addons-styles.css' );


				/*
				 * Register Styles
				 */

//				wp_register_style( 'master-addons-main-style', MELA_PLUGIN_URL . '/assets/css/master-addons-styles.css' );


				wp_register_style( 'master-addons-headlines', MELA_PLUGIN_URL . '/assets/css/headlines.css' );

				wp_register_style( 'gridder', MELA_PLUGIN_URL . '/assets/vendor/gridder/css/jquery.gridder.min.css' );

				wp_register_style( 'fancybox', MELA_PLUGIN_URL . '/assets/vendor/fancybox/jquery.fancybox.min.css' );

				wp_register_style( 'master-addons-pricing-table', MELA_PLUGIN_URL . '/assets/css/pricing-table.css' );



				/*
				 * Register Scripts
				 */
				wp_register_script( 'bootstrap', MELA_PLUGIN_URL . '/assets/js/bootstrap.min.js', array( 'jquery' ), MELA_VERSION, true );

				wp_register_script( 'ma-animated-headlines', MELA_PLUGIN_URL . '/assets/js/animated-main.js', array( 'jquery' ),	'1.0', true );

				wp_register_script( 'master-addons-progressbar', MELA_PLUGIN_URL . '/assets/js/loading-bar.js', [ 'jquery' ], self::VERSION, true );

				wp_register_script( 'jquery-stats', MELA_PLUGIN_URL . '/assets/js/jquery.stats.js', [ 'jquery' ], MELA_VERSION, true );

				wp_register_script( 'master-addons-waypoints', MELA_PLUGIN_URL . '/assets/vendor/jquery.waypoints.min.js', [ 'jquery' ], self::VERSION, true );

				wp_register_script( 'master-addons-team-members', MELA_PLUGIN_URL . '/assets/vendor/owlcarousel/owl.carousel.min.js', [ 'jquery' ], MELA_VERSION, true );

				wp_register_script( 'gridder', MELA_PLUGIN_URL . '/assets/vendor/gridder/js/jquery.gridder.min.js', ['jquery'], MELA_VERSION, true );

				wp_register_script( 'isotope', MELA_PLUGIN_URL . '/assets/js/isotope.js', array('jquery'), MELA_VERSION, true );

				wp_register_script( 'ma-news-ticker', MELA_PLUGIN_URL . '/assets/vendor/newsticker/js/newsticker.js',array('jquery'), MELA_VERSION, true );

				wp_register_script( 'jquery-rss', MELA_PLUGIN_URL . '/assets/vendor/newsticker/js/jquery.rss.min.js',
					array('jquery'), MELA_VERSION, true );

				wp_register_script( 'magnific-popup', MELA_PLUGIN_URL . '/assets/vendor/magnific-popup/jquery.magnific-popup.min.js',array('jquery'), MELA_VERSION, true );

				wp_register_script( 'ma-counter-up', MELA_PLUGIN_URL . '/assets/js/counterup.min.js',array('jquery'), MELA_VERSION, true );

				wp_register_script( 'ma-countdown', MELA_PLUGIN_URL . '/assets/vendor/countdown/jquery.countdown.js',array( 'jquery' ), self::VERSION, true );

				wp_register_script( 'tocbot', MELA_PLUGIN_URL . '/assets/vendor/tocbot/tocbot.min.js',array('jquery'),self::VERSION, true );

				wp_register_script( 'fancybox', MELA_PLUGIN_URL . '/assets/vendor/fancybox/jquery.fancybox.min.js',array('jquery'),self::VERSION, true );

				//Reveal
				wp_register_script( 'ma-el-reveal-lib', MELA_PLUGIN_URL . '/assets/vendor/reveal/revealFx.js',array('jquery'),self::VERSION, true );
				// wp_register_script( 'ma-el-reveal', MELA_PLUGIN_URL . '/assets/js/elements-reveal.js',array('jquery'),self::VERSION, true );
				wp_register_script( 'ma-el-anime-lib', MELA_PLUGIN_URL . '/assets/vendor/anime/anime.min.js',array('jquery'),self::VERSION, true );

				//Rellax
				wp_register_script( 'ma-el-rellaxjs-lib', MELA_PLUGIN_URL . '/assets/vendor/rellax/rellax.min.js',array('jquery'),self::VERSION, true );
				// wp_register_script( 'ma-el-rellax', MELA_PLUGIN_URL . '/assets/vendor/anime/anime.min.js',array('jquery'),self::VERSION, true );


				// Master Addons Dependencies

				//Progressbar
				if ( $is_activated_widget['ma-progressbar'] ) {
					wp_enqueue_script('master-addons-progressbar');
					wp_enqueue_script( 'master-addons-waypoints');
				}

				//Progressbar
				if ( $is_activated_extensions['mega-menu'] ) {
					wp_enqueue_script('bootstrap');
				}


				//Team Members
				if ( $is_activated_widget['ma-team-members'] ) {
					wp_enqueue_script( 'master-addons-team-members' );
					wp_enqueue_style( 'gridder' );
					wp_enqueue_script( 'gridder' );
				}


				//Pricing Table
				if ( $is_activated_widget['ma-pricing-table'] ) {
					wp_enqueue_style( 'master-addons-pricing-table' );
				}

				//Restrict Content
				if ( $is_activated_widget['ma-restrict-content'] ) {
					wp_enqueue_style( 'fancybox' );
					wp_enqueue_script( 'fancybox' );
				}


				//Animated Headlines
				if ( $is_activated_widget['ma-animated-headlines'] ) {
					wp_enqueue_style( 'master-addons-headlines' );
					wp_enqueue_style( 'ma-animated-headlines' );
				}

				//Creative Buttons
				if ( $is_activated_widget['ma-creative-buttons'] ) {
					wp_enqueue_style( 'ma-creative-buttons', MELA_PLUGIN_URL . '/assets/vendor/creative-btn/buttons.css' );

					if ( ma_el_fs()->can_use_premium_code() ) {
						wp_enqueue_style( 'ma-creative-buttons-pro', MELA_PLUGIN_URL . '/assets/vendor/creative-btn/buttons-pro.css' );
					}
				}


				//Creative Links
				if ( $is_activated_widget['ma-creative-links'] ) {
					//Free Version Codes
					wp_enqueue_style( 'ma-creative-links', MELA_PLUGIN_URL . '/assets/vendor/creative-links/creative-links.css' );

					// Premium Version Codes
					if ( ma_el_fs()->can_use_premium_code() ) {
						wp_enqueue_style( 'ma-creative-links-pro', MELA_PLUGIN_URL . '/assets/vendor/creative-links/creative-links-pro.css' );
					}
				}

				//Image Hover Effects
				if ( $is_activated_widget['ma-image-hover-effects'] ) {
					wp_enqueue_style( 'ma-image-hover-effects-free', MELA_PLUGIN_URL . '/assets/vendor/image-hover-effects/image-hover-free.css' );
				}

				//Table of Contents
				if ( $is_activated_widget['ma-table-of-contents'] ) {
					wp_enqueue_script( 'tocbot' );
				}


				//News Ticker
				if ( $is_activated_widget['ma-news-ticker'] ) {
					wp_enqueue_script( 'ma-news-ticker' );
				}


				//Counter Up
				if ( $is_activated_widget['ma-counter-up'] ) {
					wp_enqueue_script( 'ma-counter-up' );
				}

				// Countdown Timer
				// if ( $is_activated_widget['ma-countdown-timer'] ) {
				// 	wp_enqueue_script('master-addons-countdown');
				// }


				//MA Blog
				if ( $is_activated_widget['ma-blog'] ) {
					wp_enqueue_script( 'isotope' );
				}

				//MA Blog
				if ( $is_activated_widget['ma-image-filter-gallery'] ) {
					wp_enqueue_script( 'isotope' );

					wp_enqueue_style( 'fancybox' );
					wp_enqueue_script( 'fancybox' );
				}


				//Google Maps
				//		if ( $is_activated_widget['google-maps'] ) {
				//			wp_enqueue_script( 'master-addons-google-map-api', 'https://maps.googleapis.com/maps/api/js?key='
				//.get_option
				//('exad_google_map_api_option'), array('jquery'),'1.8', false );
				//			// Gmap 3 Js
				//			wp_enqueue_script( 'master-addons-gmap3', MELA_PLUGIN_URL . 'assets/js/vendor/gmap3.min.js', array(
				// 'jquery' )
				//, self::VERSION, true );
				//		}


				// Master Addons Scripts */
				wp_enqueue_script( 'master-addons-scripts', MELA_PLUGIN_URL . '/assets/js/master-addons-scripts.js', [ 'jquery' ], self::VERSION, true );

				$localize_data = array(
					'plugin_url'    => MELA_PLUGIN_URL,
					'ajaxurl'       => admin_url( 'admin-ajax.php' )
				);
				wp_localize_script( 'master-addons-scripts', 'jltma_scripts', $localize_data );
			}


			public function is_elementor_activated( $plugin_path = 'elementor/elementor.php' ) {
				$installed_plugins_list = get_plugins();

				return isset( $installed_plugins_list[ $plugin_path ] );
			}


			/*
			 * Activation Plugin redirect hook
			 */
			public function mael_ad_redirect_hook() {
				if ( is_plugin_active( 'elementor/elementor.php' ) ) {
					if ( get_option( 'ma_el_update_redirect', false ) ) {
						delete_option( 'ma_el_update_redirect' );
						delete_transient( 'ma_el_update_redirect' );
						if ( ! isset( $_GET['activate-multi'] ) && $this->is_elementor_activated() ) {
							wp_redirect( 'admin.php?page=master-addons-settings' );
							exit;
						}
					}
				}
			}


			public static function version() {
				return self::VERSION;
			}


			// Text Domains
			public function mela_load_textdomain() {
				load_plugin_textdomain( 'mela' );
			}


			// Admin Hooks
			public function mela_define_admin_hooks() {

			}

			// Plugin URL
			public static function mela_plugin_url() {

				if ( self::$plugin_url ) {
					return self::$plugin_url;
				}

				return self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );

			}

			// Plugin Path
			public static function mela_plugin_path() {
				if ( self::$plugin_path ) {
					return self::$plugin_path;
				}

				return self::$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			// Plugin Dir Path
			public static function mela_plugin_dir_url() {

				if ( self::$plugin_dir_url ) {
					return self::$plugin_dir_url;
				}

				return self::$plugin_dir_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
			}


			public function plugin_actions_links( $links ) {
				if ( is_admin() ) {
					$links[] = sprintf( '<a href="admin.php?page=master-addons-settings">' . __( 'Settings', MELA_TD ) . '</a>' );
					$links[] = '<a href="https://master-addons.com/contact-us" target="_blank">' . esc_html__( 'Support', MELA_TD ) . '</a>';
					$links[] = '<a href="https://master-addons.com/docs/" target="_blank">' . esc_html__( 'Documentation',
							MELA_TD ) . '</a>';
				}

				// go pro
				if (!$this->pro_enabled) {
					$links[] = sprintf('<a href="https://master-addons.com/" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Go Pro') . '</a>');
				}

				return $links;
			}


			// Include Files
			public function maad_el_include_files() {

				// Helper Class
				include_once MELA_PLUGIN_PATH . '/inc/classes/helper-class.php';

				// Dashboard Settings
				include_once MELA_PLUGIN_PATH . '/inc/admin/dashboard-settings.php';

				//Utils
				include_once MELA_PLUGIN_PATH . '/inc/classes/utils.php';

				require_once MELA_PLUGIN_PATH . '/inc/templates/templates.php';

				require_once MELA_PLUGIN_PATH . '/inc/classes/JLTMA_Extension_Prototype.php';
			}



			public function jltma_body_class( $classes ) {
				global $pagenow;

				if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ], true ) && \Elementor\Utils::is_post_support() ) {
					$post = get_post();

					$mode_class = \Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID ) ? 'elementor-editor-active' : 'elementor-editor-inactive liton-arefin';

					$classes .= ' ' . $mode_class;
				}

				return $classes;
			}


//			public function jltma_body_class( $classes ){
//				if(class_exists('\Elementor\Plugin')) {
//					if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
//						$classes[] = 'master-addons';
//					}
//				}
//				return $classes;
//			}


			public function get_localize_settings() {
				return $this->_localize_settings;
			}

			public function add_localize_settings( $setting_key, $setting_value = null ) {
				if ( is_array( $setting_key ) ) {
					$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

					return;
				}

				if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
					$this->_localize_settings[ $setting_key ] = $setting_value;

					return;
				}

				$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
			}


			public function mela_admin_notice_missing_main_plugin() {
				$plugin = 'elementor/elementor.php';

				if ( $this->is_elementor_activated() ) {
					if ( ! current_user_can( 'activate_plugins' ) ) {
						return;
					}
					$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
					$message = __( 'Master Addons requires <b>Elementor plugin to be active. Please activate Elementor to continue.', MELA_TD );
					$button_text = __( 'Activate Elementor', MELA_TD );

				} else {
					if ( ! current_user_can( 'install_plugins' ) ) {
						return;
					}

					$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
					$message = sprintf( __( 'Master Addons requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', MELA_TD ), '<strong>', '</strong>' );
					$button_text = __( 'Install Elementor', MELA_TD );
				}




				$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message , $button );

			}

			public function mela_admin_notice_minimum_elementor_version() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MELA_TD ),
					'<strong>' . esc_html__( 'Master Addons for Elementor', MELA_TD ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', MELA_TD ) . '</strong>',
					self::MINIMUM_ELEMENTOR_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}

			public function mela_admin_notice_minimum_php_version() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MELA_TD ),
					'<strong>' . esc_html__( 'Master Addons for Elementor', MELA_TD ) . '</strong>',
					'<strong>' . esc_html__( 'PHP', MELA_TD ) . '</strong>',
					self::MINIMUM_PHP_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}




		}


		Master_Elementor_Addons::get_instance();

	}