<?php

if ( !class_exists('MyStickyElementsPage_pro') ) {

	class MyStickyElementsPage_pro {

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'mystickyelements_load_plugin_textdomain' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'mystickyelements_admin_enqueue_script' ), 99 );
			add_action( 'admin_menu', array( $this, 'add_mystickyelement_plugin_page' ) );
			add_action( 'wp_ajax_mystickyelement-social-tab', array( $this, 'mystickyelement_social_tab_add' ) );
			add_action( 'wp_ajax_mystickyelement_delete_db_record', array( $this, 'mystickyelement_delete_db_record' ) );
			
			add_action( 'wp_ajax_myStickyelements_intro_popup_action', array( $this, 'myStickyelements_intro_popup_action' ) );

			add_filter( 'plugin_action_links_mystickyelements/mystickyelements.php', array( $this, 'settings_link' )  );
			
			add_action( 'admin_footer', array( $this, 'mystickyelements_deactivate' ) );			
			/* Send message to owner */
			add_action( 'wp_ajax_mystickyelements_admin_send_message_to_owner', array( $this, 'mystickyelements_admin_send_message_to_owner' ) );
			add_action( 'wp_ajax_mystickyelements_plugin_deactivate', array( $this, 'mystickyelements_plugin_deactivate' ) );

            add_action("wp_ajax_sticky_element_update_status", array($this, 'update_status'));
		}

        public function update_status() {
            if(!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'my_sticky_elements_update_nonce')) {
                $status = self::sanitize_options($_REQUEST['status']);
                $email = self::sanitize_options($_REQUEST['email']);
                update_option("mysticky_element_update_message", 2);
                if($status == 1) {
                    $url = 'https://go.premio.io/api/update.php?email='.$email.'&plugin=elements';
                    $handle = curl_init();
                    curl_setopt($handle, CURLOPT_URL, $url);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handle);
                    curl_close($handle);
                }
            }
            echo "1";
            die;
        }

		public function settings_link($links) {
			$settings_link = '<a href="'.admin_url("admin.php?page=my-sticky-elements-settings").'">Settings</a>';
			$links['need_help'] = '<a href="https://premio.io/help/mystickyelements/?utm_source=pluginspage" target="_blank">'.__( 'Need help?', 'mystickyelements' ).'</a>';
			
			$links['go_pro'] = '<a href="'.admin_url("admin.php?page=my-sticky-elements-upgrade").'" style="color: #FF5983;font-weight: bold;">'.__( 'Upgrade', 'mystickyelements' ).'</a>';			
			
			array_unshift($links, $settings_link);
			return $links;
		}

		/*
		 * Load Plugin text domain.
		 */

		public function mystickyelements_load_plugin_textdomain() {
			load_plugin_textdomain('mystickyelements', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		}

		/*
		 * enqueue admin side script and style.
		 */
		public  function mystickyelements_admin_enqueue_script( ) {

			if ( isset($_GET['page']) && ( $_GET['page'] == 'my-sticky-elements-settings' || $_GET['page'] == 'my-sticky-elements-leads' || $_GET['page'] == 'my-sticky-elements-new-widget' ) ) {
                $is_shown = get_option("mysticky_element_update_message");
			    if($is_shown != 1) {
                    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700');
                    wp_enqueue_style('font-awesome-css', plugins_url('/css/font-awesome.min.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                    wp_enqueue_style('wp-color-picker');
                    wp_enqueue_style('mystickyelements-admin-css', plugins_url('/css/mystickyelements-admin.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                    wp_style_add_data('mystickyelements-admin-css', 'rtl', 'replace');
					
					wp_enqueue_style('select2-css', plugins_url('/css/select2.min.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                    wp_enqueue_style('mystickyelements-front-css', plugins_url('/css/mystickyelements-front.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                    wp_enqueue_style('mystickyelements-help-css', plugins_url('/css/mystickyelements-help.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                    wp_style_add_data('mystickyelements-help-css', 'rtl', 'replace');
					wp_enqueue_style( 'wp-jquery-ui-dialog' );
					wp_enqueue_script( 'jquery-ui-dialog' );
                    wp_enqueue_script('wp-color-picker');
                    wp_enqueue_script('jquery-ui-sortable');
                    wp_enqueue_script('jquery-effects-shake');
                    wp_enqueue_media();
					wp_enqueue_script('select2-js', plugins_url('/js/select2.min.js', __FILE__), array( 'jquery' ), MY_STICKY_ELEMENT_VERSION, true ) ;
					wp_enqueue_script('confetti-js', plugins_url('/js/confetti.min.js', __FILE__), array( 'jquery' ), MY_STICKY_ELEMENT_VERSION, false ) ;
                    wp_enqueue_script('mystickyelements-js', plugins_url('/js/mystickyelements-admin.js', __FILE__), array('jquery'), MY_STICKY_ELEMENT_VERSION, true);
                } else {
                    wp_enqueue_style('email-update-css', plugins_url('/css/email-update.css', __FILE__), array(), MY_STICKY_ELEMENT_VERSION);
                }
			}
		}

		/*
		 * Add My Sticky Element Page in admin menu.
		 */
		public function add_mystickyelement_plugin_page() {
			add_menu_page(
				'Settings Admin',
				'myStickyelements',
				'manage_options',
				'my-sticky-elements-settings',
				array( $this, 'mystickyelements_admin_settings_page' ),
				'dashicons-sticky'
			);
			add_submenu_page(
				'my-sticky-elements-settings',
				'Settings Admin',
				'Settings',
				'manage_options',
				'my-sticky-elements-settings',
				array( $this, 'mystickyelements_admin_settings_page' )
			);
			add_submenu_page(
				'my-sticky-elements-settings',
				'Settings Admin',
				'+ Create New Widget',
				'manage_options',
				'my-sticky-elements-new-widget',
				array( $this, 'mystickyelements_admin_new_widget_page' )
			);
			add_submenu_page(
				'my-sticky-elements-settings',
				'Settings Admin',
				'Contact Form Leads',
				'manage_options',
				'my-sticky-elements-leads',
				array( $this, 'mystickyelements_admin_leads_page' )
			);
			add_submenu_page(
				'my-sticky-elements-settings',
				'Upgrade to Pro',
				'Upgrade to Pro',
				'manage_options',
				'my-sticky-elements-upgrade',
				array( $this, 'mystickyelements_admin_upgrade_to_pro' )
			);
		}

		public static function sanitize_options($value, $type = "") {
			if ( !is_array($value)) {
				$value = stripslashes($value);
			}
			if($type == "int") {
				$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
			} else if($type == "email") {
				$value = sanitize_email($value);
			} else if($type == "url") {
				$value = esc_url_raw($value);
			} else if($type == "sql") {
				$value = esc_sql($value);
			} else {
				$value = sanitize_text_field($value);
			}
			return $value;
		}

		public function mystickyelements_admin_upgrade_to_pro() {
			include_once 'upgrade-to-pro.php';
		}

		/*
		 * My Sticky Elements Settings Page
		 *
		 */
		public function mystickyelements_admin_settings_page() {
			global $wpdb;

			if ( isset($_POST['mystickyelement-submit']) && !wp_verify_nonce( $_POST['mystickyelement-submit'], 'mystickyelement-submit' ) ) {

				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickyelements'). '</p></strong></div>';

			} else if (  isset($_POST['general-settings']) && !empty($_POST['general-settings']) && wp_verify_nonce( $_POST['mystickyelement-submit'], 'mystickyelement-submit' )) {
				/* Save/Update Contact Form tab */
		
				$contact_field = filter_var_array( $_POST['contact-field'], FILTER_SANITIZE_STRING );
				update_option('mystickyelements-contact-field', $contact_field);

				$post = array();
				if(isset($_POST['contact-form'])) {
					$contact = $_POST['contact-form'];

					if(isset($contact['enable'])) {
						$post['enable'] = self::sanitize_options($contact['enable'], "int");
					}

					if(isset($contact['name'])) {
						$post['name'] = self::sanitize_options($contact['name'], "int");
					}

					if(isset($contact['name_require'])) {
						$post['name_require'] = self::sanitize_options($contact['name_require'], "int");
					}

					if(isset($contact['name_value'])) {
						$post['name_value'] = self::sanitize_options($contact['name_value']);
					}

					if(isset($contact['phone'])) {
						$post['phone'] = self::sanitize_options($contact['phone'], "int");
					}

					if(isset($contact['phone_require'])) {
						$post['phone_require'] = self::sanitize_options($contact['phone_require'], "int");
					}

					if(isset($contact['phone_value'])) {
						$post['phone_value'] = self::sanitize_options($contact['phone_value']);
					}

					if(isset($contact['email'])) {
						$post['email'] = self::sanitize_options($contact['email'], "int");
					}

					if(isset($contact['email_require'])) {
						$post['email_require'] = self::sanitize_options($contact['email_require'], "int");
					}

					if(isset($contact['email_value'])) {
						$post['email_value'] = self::sanitize_options($contact['email_value']);
					}

					if(isset($contact['message'])) {
						$post['message'] = self::sanitize_options($contact['message'], "int");
					}

					if(isset($contact['message_require'])) {
						$post['message_require'] = self::sanitize_options($contact['message_require'], "int");
					}

					if(isset($contact['message_value'])) {
						$post['message_value'] = self::sanitize_options($contact['message_value']);
					}
					if(isset($contact['dropdown'])) {
						$post['dropdown'] = self::sanitize_options($contact['dropdown'], "int");
					}

					if(isset($contact['dropdown_require'])) {
						$post['dropdown_require'] = self::sanitize_options($contact['dropdown_require'], "int");
					}
					
					if(isset($contact['submit_button_background_color'])) {
						$post['submit_button_background_color'] = self::sanitize_options($contact['submit_button_background_color']);
					}

					if(isset($contact['submit_button_text_color'])) {
						$post['submit_button_text_color'] = self::sanitize_options($contact['submit_button_text_color']);
					}

					if(isset($contact['submit_button_text'])) {
						$post['submit_button_text'] = self::sanitize_options($contact['submit_button_text']);
					}

					if(isset($contact['tab_background_color'])) {
						$post['tab_background_color'] = self::sanitize_options($contact['tab_background_color']);
					}

					if(isset($contact['tab_text_color'])) {
						$post['tab_text_color'] = self::sanitize_options($contact['tab_text_color']);
					}
					if(isset($contact['headine_text_color'])) {
						$post['headine_text_color'] = self::sanitize_options($contact['headine_text_color']);
					}

					if(isset($contact['text_in_tab'])) {
						$post['text_in_tab'] = self::sanitize_options($contact['text_in_tab']);
					}

					if(isset($contact['send_leads'])) {
						$post['send_leads'] = self::sanitize_options($contact['send_leads']);
					}

					if(isset($contact['sent_to_mail'])) {
						$post['sent_to_mail'] = self::sanitize_options($contact['sent_to_mail']);
					}

					if(isset($contact['direction'])) {
						$post['direction'] = self::sanitize_options($contact['direction']);
					}

					if(isset($contact['direction'])) {
						$post['direction'] = self::sanitize_options($contact['direction']);
					}

					if(isset($contact['desktop'])) {
						$post['desktop'] = self::sanitize_options($contact['desktop'], "int");
					}

					if(isset($contact['mobile'])) {
						$post['mobile'] = self::sanitize_options($contact['mobile'], "int");
					}
					if(isset($contact['dropdown-placeholder'])) {
						$post['dropdown-placeholder'] = self::sanitize_options($contact['dropdown-placeholder']);
					}
					if(isset($contact['dropdown-option'])) {
						$post['dropdown-option'] = filter_var_array( $contact['dropdown-option'], FILTER_SANITIZE_STRING );
					}
					if(isset($contact['redirect'])) {
						$post['redirect'] = self::sanitize_options($contact['redirect'], "int");
					}
					if(isset($contact['redirect_link'])) {
						$post['redirect_link'] = self::sanitize_options($contact['redirect_link']);
					}
				}
				update_option('mystickyelements-contact-form', $post);

				/* Save/Update Social Channels tabs */
				$social_channels = array();
				if(isset($_POST['social-channels'])) {
					if(!empty($_POST['social-channels'])) {
						$social_channels = $_POST['social-channels'];
						foreach($social_channels as $key=>$val) {
							$social_channels[$key] = self::sanitize_options($val, "int");
						}
					}
				}
				update_option('mystickyelements-social-channels', $social_channels);

				$social_channels_tab = array();
				if(isset($_POST['social-channels-tab'])) {
					if(!empty($_POST['social-channels-tab'])) {
						foreach($_POST['social-channels-tab'] as $key=>$option) {
							if(isset($option['text'])) {
								$option['text'] = $option['text'];
							}
							if(isset($option['desktop'])) {
								$option['desktop'] = self::sanitize_options($option['desktop'], "int");
							}
							if(isset($option['mobile'])) {
								$option['mobile'] = self::sanitize_options($option['mobile'], "int");
							}
							if(isset($option['bg_color'])) {
								$option['bg_color'] = self::sanitize_options($option['bg_color']);
							}
							if(isset($option['hover_text'])) {
								$option['hover_text'] = self::sanitize_options($option['hover_text']);
							}
							$social_channels_tab[$key] = $option;
						}
					}
				}
				update_option( 'mystickyelements-social-channels-tabs', $social_channels_tab);

				/* Save/Update General Settings */
				$general_setting = array();
				if(isset($_POST['general-settings'])) {
					if(!empty($_POST['general-settings'])) {
						foreach($_POST['general-settings'] as $key=>$value) {
							$general_setting[$key] = self::sanitize_options($value);
						}
					}
				}
				update_option('mystickyelements-general-settings', $general_setting);



				/* Send Email Afte set email */
				if ( isset($_POST['contact-form']['send_leads']) && $_POST['contact-form']['send_leads'] == 'mail' && $_POST['contact-form']['sent_to_mail'] != '' && !get_option('mystickyelements-contact-mail-sent') ) {
					$send_mail = $_POST['contact-form']['sent_to_mail'];

					$subject = "Great job! You created your contact form successfully";
					$message = 'Thanks for using MyStickyElements! If you see this message in your spam folder, please click on "Report not spam" so you will get the next leads into your inbox.';


					$blog_name = get_bloginfo('name');
					$blog_email = get_bloginfo('admin_email');

					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					$headers .= 'From: ' . $blog_name . ' <' . $blog_email . '>' ."\r\n";
					$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

					if ( wp_mail( $send_mail, $subject, $message, $headers ) ) {
						update_option( 'mystickyelements-contact-mail-sent', true );
					}
				}
				$this->mystickyelements_clear_all_caches();
				echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__('Settings saved.','mystickyelements'). '</p></strong></div>';
			}
			$contact_field = get_option( 'mystickyelements-contact-field' );
			if ( empty( $contact_field ) ) {
				$contact_field = array( 'name', 'phone', 'email', 'message', 'dropdown' );
			}
			$contact_form = get_option( 'mystickyelements-contact-form');
			$social_channels = get_option( 'mystickyelements-social-channels');
			$social_channels_tabs = get_option( 'mystickyelements-social-channels-tabs');
			$general_settings = get_option( 'mystickyelements-general-settings');
			if ( !isset($general_settings['position_mobile']) ) {
				$general_settings['position_mobile'] = 'left';
			}
			$social_channels_lists = mystickyelements_social_channels();

			$upgarde_url = admin_url("admin.php?page=my-sticky-elements-upgrade");
			$is_pro_active = false;
            $is_shown = get_option("mysticky_element_update_message");
            ?>
            <?php if($is_shown == 1) {?>
                <div class="updates-form-form" >
                    <div class="popup-form-content">
                        <div id="add-update-title" class="add-update-title">
                            Would you like to get feature updates for My Sticky Elements in real-time?
                        </div>
                        <div class="folder-update-input">
                            <input id="sticky_element_update_email" autocomplete="off" value="<?php echo get_option( 'admin_email' ) ?>" placeholder="Email address">
                        </div>
                        <div class="updates-content-buttons">
                            <button href="javascript:;" class="button button-primary form-submit-btn yes">Yes, I want</button>
                            <button href="javascript:;" class="button button-secondary form-cancel-btn no">Skip</button>
                            <div style="clear: both"></div>
                        </div>
                        <input type="hidden" id="sticky_element_update_nonce" value="<?php echo wp_create_nonce("my_sticky_elements_update_nonce") ?>">
                    </div>
                </div>
                <script>
                    jQuery(document).ready(function($) {
                        $(document).on("click", ".updates-content-buttons button", function () {
                            var updateStatus = 0;
                            if ($(this).hasClass("yes")) {
                                updateStatus = 1;
                            }
                            $(".updates-content-buttons button").attr("disabled", true);
                            $.ajax({
                                url: ajaxurl,
                                data: "action=sticky_element_update_status&status=" + updateStatus + "&nonce=" + $("#sticky_element_update_nonce").val() + "&email=" + $("#sticky_element_update_email").val(),
                                type: 'post',
                                cache: false,
                                success: function () {
                                    window.location.reload();
                                }
                            })
                        });
                    });
                </script>
            <?php } else {
					if (isset($general_settings['font_family']) && $general_settings['font_family'] !="" ) :
				?>
                <link href="https://fonts.googleapis.com/css?family=<?php echo $general_settings['font_family']; ?>:400,500,600,700"
                      rel="stylesheet" type="text/css" class="sfba-google-font">
                <style>
                    .myStickyelements-preview-ul .mystickyelements-social-icon {
                        font-family: <?php echo $general_settings['font_family'];?>
                    }
                </style>
				<?php endif;?>
                <div class="wrap mystickyelement-wrap">
                    <h2>
						<?php _e('My Sticky Elements', 'mystickyelements'); ?>
						<div class="mystickyelement-contact-form-leads-btn">
							<a href="<?php echo admin_url("admin.php?page=my-sticky-elements-leads") ?>" class="create-rule">Contact Form Leads</a>
						</div>
					</h2>

                    <div class="mystickyelements-wrap">
                        <form class="mystickyelements-form" method="post" action="#">

                            <!-- Contact Form Tab Section -->
                            <div class="myStickyelements-container myStickyelements-contact-form-tab">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="myStickyelements-header-title">
                                                <h3><?php _e('Contact Form Tab', 'mystickyelements'); ?></h3>
                                                <label for="myStickyelements-contact-form-enabled"
                                                       class="myStickyelements-switch">
                                                    <input type="checkbox" id="myStickyelements-contact-form-enabled"
                                                           name="contact-form[enable]"
                                                           value="1" <?php checked(@$contact_form['enable'], '1'); ?> />
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <table id="mystickyelements-contact-form-fields"
                                                   class="myStickyelements-contact-form-field-hide">
                                                <?php foreach ($contact_field as $value) :

                                                    switch ($value) {

                                                        case 'name' :
                                                            ?>

                                                            <tr>
                                                                <td>
                                                                    <div class="move-icon">
                                                                        <input type="hidden" class="contact-fields"
                                                                               name="contact-field[]" value="name"/>
                                                                    </div>
                                                                    <label>
                                                                        <input type="checkbox" name="contact-form[name]"
                                                                               value="1" <?php checked(@$contact_form['name'], '1'); ?> />
                                                                        &nbsp; <?php _e('Name', 'mystickyelements'); ?>
                                                                    </label>
                                                                    <div class="mystickyelements-reqired-wrap">
                                                                        <div class="myStickyelements-icon-wrap">
                                                                            <input type="text"
                                                                                   name="contact-form[name_value]"
                                                                                   value="<?php echo $contact_form['name_value']; ?>"
                                                                                   placeholder="<?php _e('Enter Name', 'mystickyelements'); ?>"/>
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </div>
                                                                        <label>
                                                                            <input type="checkbox" class="required"
                                                                                   name="contact-form[name_require]"
                                                                                   value="1" <?php checked(@$contact_form['name_require'], '1'); ?> />
                                                                            &nbsp; <?php _e('Required', 'mystickyelements'); ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php break;
                                                        case 'phone' :
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="move-icon">
                                                                        <input type="hidden" class="contact-fields"
                                                                               name="contact-field[]" value="phone"/>
                                                                    </div>
                                                                    <label>
                                                                        <input type="checkbox"
                                                                               name="contact-form[phone]"
                                                                               value="1" <?php checked(@$contact_form['phone'], '1'); ?> />
                                                                        &nbsp; <?php _e('Phone', 'mystickyelements'); ?>
                                                                    </label>
                                                                    <div class="mystickyelements-reqired-wrap">
                                                                        <div class="myStickyelements-icon-wrap">
                                                                            <input type="text"
                                                                                   name="contact-form[phone_value]"
                                                                                   value="<?php echo $contact_form['phone_value']; ?>"
                                                                                   placeholder="<?php _e('Enter Phone Number', 'mystickyelements'); ?>"/>
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </div>
                                                                        <label>
                                                                            <input type="checkbox" class="required"
                                                                                   name="contact-form[phone_require]"
                                                                                   value="1" <?php checked(@$contact_form['phone_require'], '1'); ?> />
                                                                            &nbsp; <?php _e('Required', 'mystickyelements'); ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php break;
                                                        case 'email' :
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="move-icon">
                                                                        <input type="hidden" class="contact-fields"
                                                                               name="contact-field[]" value="email"/>
                                                                    </div>
                                                                    <label>
                                                                        <input type="checkbox"
                                                                               name="contact-form[email]"
                                                                               value="1" <?php checked(@$contact_form['email'], '1'); ?> />
                                                                        &nbsp; <?php _e('Email', 'mystickyelements'); ?>
                                                                    </label>
                                                                    <div class="mystickyelements-reqired-wrap">
                                                                        <div class="myStickyelements-icon-wrap">
                                                                            <input type="text"
                                                                                   name="contact-form[email_value]"
                                                                                   value="<?php echo $contact_form['email_value']; ?>"
                                                                                   placeholder="<?php _e('Enter Email', 'mystickyelements'); ?>"/>
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </div>
                                                                        <label>
                                                                            <input type="checkbox" class="required"
                                                                                   name="contact-form[email_require]"
                                                                                   value="1" <?php checked(@$contact_form['email_require'], '1'); ?> />
                                                                            &nbsp; <?php _e('Required', 'mystickyelements'); ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php break;
                                                        case 'message' :
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="move-icon">
                                                                        <input type="hidden" class="contact-fields"
                                                                               name="contact-field[]" value="message"/>
                                                                    </div>
                                                                    <label>
                                                                        <input type="checkbox"
                                                                               name="contact-form[message]"
                                                                               value="1" <?php checked(@$contact_form['message'], '1'); ?> />
                                                                        &nbsp; <?php _e('Message', 'mystickyelements'); ?>
                                                                    </label>
                                                                    <div class="mystickyelements-reqired-wrap">
                                                                        <div class="myStickyelements-icon-wrap">
                                                                            <textarea name="contact-form[message_value]"
                                                                                      rows="5" cols="50"
                                                                                      placeholder="<?php _e('Enter Message', 'mystickyelements'); ?>"><?php echo $contact_form['message_value']; ?></textarea>
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </div>
                                                                        <label>
                                                                            <input type="checkbox" class="required"
                                                                                   name="contact-form[message_require]"
                                                                                   value="1" <?php checked(@$contact_form['message_require'], '1'); ?> />
                                                                            &nbsp; <?php _e('Required', 'mystickyelements'); ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php break;
                                                        case 'dropdown' :
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="move-icon">
                                                                        <input type="hidden" class="contact-fields"
                                                                               name="contact-field[]" value="dropdown"/>
                                                                    </div>
                                                                    <div class="myStickyelements-clear myStickyelements-setting-wrap">
														<span class="myStickyelements-label">
															<label>
																<input type="checkbox" name="contact-form[dropdown]"
                                                                       value="1" <?php checked(@$contact_form['dropdown'], '1'); ?> <?php echo !$is_pro_active ? "disabled" : "" ?> /> &nbsp; <?php _e('Dropdown', 'mystickyelements'); ?>
															</label>
															<span class="upgrade-myStickyelements"><a
                                                                        href="<?php echo esc_url($upgarde_url); ?>"
                                                                        target="_blank"><i
                                                                            class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
														</span>
                                                                        <label class="myStickyelements-setting-label">
															<span class="contact-form-dropdown-popup">
																<i class="fas fa-cog"></i>&nbsp;<?php esc_html_e('Settings', 'mystickyelements'); ?>
															</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="mystickyelements-reqired-wrap">
                                                                        <select name="contact-form[dropdown_value]"
                                                                                id="" <?php echo !$is_pro_active ? "disabled" : "" ?> >
                                                                            <option value=""><?php echo "Select " . @$contact_form['dropdown-placeholder']; ?></option>
                                                                            <?php if (isset($contact_form['dropdown-option']) && !empty($contact_form['dropdown-option'])) :
                                                                                foreach ($contact_form['dropdown-option'] as $option) :
                                                                                    if ($option == '') {
                                                                                        continue;
                                                                                    }
                                                                                    echo "<option>" . esc_html($option) . "</option>";
                                                                                endforeach;
                                                                            endif;
                                                                            ?>
                                                                        </select>
                                                                        <label>
                                                                            <input type="checkbox" class="required"
                                                                                   name="contact-form[dropdown_require]"
                                                                                   value="1" <?php checked(@$contact_form['dropdown_require'], '1'); ?> <?php echo !$is_pro_active ? "disabled" : "" ?> />
                                                                            &nbsp; <?php _e('Required', 'mystickyelements'); ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            break;
                                                    } /* Finish Switch case */
                                                endforeach; /* Contact Fields  */ ?>
												<tr>
												<td>
													<div class="myStickyelements-clear myStickyelements-setting-wrap">
														<span class="myStickyelements-label">
															<label>
																<input type="checkbox" name="contact-form[consent_checkbox]" id="consent_checkbox" value="yes" <?php checked( @$contact_form['consent_checkbox'], 'yes' );?> disabled  /> &nbsp; <?php _e( 'Enable Consent Checkbox', 'mystickyelements' );?>
															</label>
															<span class="upgrade-myStickyelements">
																<a href="<?php echo esc_url($upgarde_url); ?>" target="_blank">
																	<i class="fas fa-lock"></i>
																	<?php _e('UPGRADE NOW', 'mystickyelements'); ?>
																</a>
															</span>
														</span>
													</div>
													<div class="mystickyelements-reqired-wrap">
														<div class="myStickyelements-icon-wrap">
															<?php $consent_text = ( isset($contact_form['consent_text'])) ? $contact_form['consent_text'] : 'I agree to the terms and conditions.'; ?>
															<input type="text" id="consent_text" name="contact-form[consent_text]" value="<?php echo htmlentities(stripslashes($consent_text));?>" placeholder="<?php _e('Enter contact form conset text','mystickyelements');?>" disabled />
															<i class="fas fa-pencil-alt"></i>
														</div>
														<label>
															<input type="checkbox" class="required" name="contact-form[consent_text_require]" value="1" <?php checked( @$contact_form['consent_text_require'], '1' );?> disabled /> &nbsp; <?php _e('Required', 'mystickyelements');?>
														</label>
													</div>
												</td>
											</tr>
                                            </table>
                                        </td>
                                        <td rowspan="9" class="myStickyelements-preview-tab">
                                            <div class="myStickyelements-header-title">
                                                <h3><?php _e('Preview', 'mystickyelements'); ?>
                                                    <p class="description">
                                                        <strong><?php esc_html_e('See the full functionality on your live site', 'mystickyelements'); ?></strong>
                                                    </p>
                                                </h3>

                                                <span class="myStickyelements-preview-window">
												<ul>
													<li class="preview-desktop preview-active"><i
                                                                class="fas fa-desktop"></i></li>
													<li class="preview-mobile"><i class="fas fa-mobile-alt"></i></li>
												</ul>
											</span>
                                            </div>
                                            <p class="description" id="myStickyelements_mobile_templete_desc"
                                               style="display: none;">
                                                <strong><?php esc_html_e('The default template is the only template that is currently available for the mobile bottom position', 'mystickyelements'); ?></strong>
                                            </p>
                                            <div class="myStickyelements-preview-screen">
                                                <div class="mystickyelements-fixed <?php echo (isset($contact_form['direction']) && $contact_form['direction'] == "RTL") ? "is-rtl" : "" ?> mystickyelements-position-<?php echo esc_attr($general_settings['position']) ?> <?php echo (isset($general_settings['position_on_screen']) && $general_settings['position_on_screen']!= '') ? 'mystickyelements-position-screen-' .$general_settings['position_on_screen'] : 'mystickyelements-position-screen-center';?> mystickyelements-position-mobile-<?php echo esc_attr($general_settings['position_mobile']) ?> <?php echo (isset($general_settings['widget-size']) && $general_settings['widget-size'] != '') ? 'mystickyelements-size-' . $general_settings['widget-size'] : 'mystickyelements-size-medium'; ?> <?php echo (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? 'mystickyelements-mobile-size-' .$general_settings['mobile-widget-size'] : 'mystickyelements-mobile-size-medium';?> <?php echo (isset($general_settings['entry-effect']) && $general_settings['entry-effect'] != '') ? 'mystickyelements-entry-effect-' . $general_settings['entry-effect'] : 'mystickyelements-entry-effect-slide-in'; ?> <?php echo (isset($general_settings['templates']) && $general_settings['templates'] != '') ? 'mystickyelements-templates-' . $general_settings['templates'] : 'mystickyelements-templates-default'; ?>">
                                                    <ul class="myStickyelements-preview-ul <?php echo (!isset($general_settings['minimize_tab'])) ? 'remove-minimize' : '' ?>">
                                                        <?php if (isset($general_settings['minimize_tab'])) : ?>
                                                            <li class="mystickyelements-minimize">
														<span class="mystickyelements-minimize minimize-position-<?php echo esc_attr($general_settings['position']) ?> minimize-position-mobile-<?php echo esc_attr($general_settings['position_mobile']) ?>"
                                                              <?php if (isset($general_settings['minimize_tab_background_color']) && $general_settings['minimize_tab_background_color'] != ''): ?>style="background: <?php echo esc_attr($general_settings['minimize_tab_background_color']); ?>" <?php endif;
                                                        ?>>
														<?php
                                                        if ($general_settings['position'] == 'left') :
                                                            echo "&larr;";
                                                        endif;
                                                        if ($general_settings['position'] == 'right'):
                                                            echo "&rarr;";
                                                        endif;
                                                        if ($general_settings['position'] == 'bottom'):
                                                            echo "&darr;";
                                                        endif;
                                                        ?>
														</span>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li id="myStickyelements-preview-contact" class="mystickyelements-contact-form element-desktop-on element-mobile-on <?php if (!isset($contact_form['enable'])) : ?> mystickyelements-contact-form-hide <?php endif; ?>" <?php if (!isset($contact_form['enable'])) : ?> style="display:none;" <?php endif; ?>>
															<?php 
															$contact_form_text_class = '';
															if ($contact_form['text_in_tab'] == '') {
																$contact_form_text_class = "mystickyelements-contact-notext";
															}?>
														<span class="mystickyelements-social-icon <?php echo $contact_form_text_class ?>"
                                                              style="background-color: <?php echo esc_attr($contact_form['tab_background_color']); ?>; color: <?php echo $contact_form['tab_text_color']; ?>;">
															<i class="far fa-envelope"></i><?php echo isset($contact_form['text_in_tab']) ? $contact_form['text_in_tab'] : "Contact Us"; ?>
														</span>
                                                        </li>
                                                        <?php
                                                        if (!empty($social_channels_tabs)) {
                                                            foreach ($social_channels_tabs as $key => $value) {
																if ($key == 'is_empty'){
																	continue;
																}
                                                                $social_channels_list = $social_channels_lists[$key];
                                                                if (empty($value)) {
                                                                    $value['bg_color'] = $social_channels_list['background_color'];
                                                                }
																$element_class = '';
																if (isset($value['desktop']) && $value['desktop'] == 1) {
																	$element_class .= ' element-desktop-on';
																}
																if (isset($value['mobile']) && $value['mobile'] == 1) {
																	$element_class .= ' element-mobile-on';
																}
                                                                if(!isset($value['icon_text'])) {
                                                                    $value['icon_text'] = "";
                                                                }

                                                                ?>
                                                                <li id="mystickyelements-social-<?php echo esc_attr($key);?>" class="mystickyelements-social-<?php echo esc_attr($key); ?> <?php echo esc_attr($element_class);?> mystickyelements-social-preview ">
                                                                    <?php
                                                                    /*diamond template css*/
                                                                    if (isset($value['bg_color']) && $value['bg_color'] != '') {
                                                                        ?>
                                                                        <style>
                                                                            .myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>, .myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?> {
                                                                                background-color: <?php echo $value['bg_color']; ?> !important;
                                                                            }

                                                                            <?php
                                                                            if( isset($general_settings['templates']) && $general_settings['templates'] == 'diamond' ) {
                                                                            ?>
                                                                            .mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                background: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            <?php
                                                                            }
                                                                            if( isset($general_settings['templates']) && $general_settings['templates'] == 'arrow' ) {
                                                                            ?>
                                                                            .myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                border-left-color: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                border-right-color: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-bottom.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                border-bottom-color: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                border-left-color: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                border-right-color: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            <?php if( $key == 'insagram' ) { ?>
                                                                            .myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                background: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                background: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            <?php } ?>
                                                                            <?php
                                                                            }
                                                                            if( isset($general_settings['templates']) && $general_settings['templates'] == 'triangle' ) {
                                                                            ?>
                                                                            .myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                background: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            .myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
                                                                                background: <?php echo $value['bg_color']; ?>;
                                                                            }

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </style>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <span class="mystickyelements-social-icon social-<?php echo esc_attr($key); ?>"
                                                                          style="background: <?php echo esc_attr($value['bg_color']); ?>">

																	<?php if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['custom_icon'] != '' && $value['fontawesome_icon'] == '' ):?>
                                                                        <img src="<?php echo esc_url($value['custom_icon']); ?>"
                                                                             width="40" height="40"/>
                                                                    <?php else: 
																			if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['fontawesome_icon'] != '' ) {
																				$social_channels_list['class'] = $value['fontawesome_icon'];
																			}
																		if ( isset($social_channels_list['custom_svg_icon']) && $social_channels_list['custom_svg_icon'] != '' ) :
																			echo $social_channels_list['custom_svg_icon'];	
																		else:
																	?>
                                                                        <i class="<?php echo esc_attr($social_channels_list['class']); ?>" <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>></i>
                                                                    <?php endif;
																		endif;
																	$icon_text_size = "display: none;";
																	$value['icon_text'] = ( isset($value['icon_text']) && $value['icon_text'] != '' ) ? $value['icon_text'] : '';
																	if ( isset($value['icon_text']) && $value['icon_text'] != '' && isset($general_settings['templates']) && $general_settings['templates'] == 'default' ) {
																		$icon_text_size .= "display: block;";
																		if ( isset($value['icon_text_size']) && $value['icon_text_size'] != '') {
																			$icon_text_size .= "font-size: " . $value['icon_text_size'] . "px;";
																		}
																	}
																	echo "<span class='mystickyelements-icon-below-text' style='".$icon_text_size."'>" . esc_html($value['icon_text']) . "</span>";
																	if ( $key == 'line') {
																		echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil1{ fill:" .$value['icon_color']. "}</style>";
																	}
																	if ( $key == 'qzone') {
																		echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil2{ fill:" . $value['icon_color'] . "}</style>";
																	}
																	?>
																</span>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p id="mystickyelements-preview-description" class="description"
                                               style="display:none;">
                                                <strong><?php esc_html_e('There are more social icons on your live site', 'mystickyelements'); ?></strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td>
                                            <div class="mystickyelements-add-custom-fields">
                                                <a href="#"
                                                   class="mystickyelements-new-custom-btn"> <?php esc_html_e('+ NEW CUSTOM FIELD', 'mystickyelements'); ?></a>
                                                <span class="upgrade-myStickyelements"><a
                                                            href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
                                                <div class="mystickyelements-custom-fields-tooltip">
                                                    <a href="javascript:void(0);"
                                                       class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i
                                                                class="fas fa-info"></i></a>
                                                    <p><?php esc_html_e("Add custom fields to your contact form including text, text area, dropdowns, file upload, website, date, and number fields", 'mystickyelements'); ?></p>
                                                </div>
                                            </div>
											<div class="custom-field-popup-open" style="display: none;">
												<h3><?php esc_html_e("Choose which custom field you'd like to add"); ?></h3>
												<div class="contact-form-field-select-wrap">
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('Text', 'mystickyelements'); ?></span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('Text Area', 'mystickyelements'); ?> </span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span> <?php _e('Number', 'mystickyelements'); ?> </span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('Date', 'mystickyelements'); ?></span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('Website', 'mystickyelements'); ?></span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('Dropdown', 'mystickyelements'); ?></span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('File upload', 'mystickyelements'); ?></span>
													</label>
													<label class="contact-form-field-select">
														<input type="radio" name="radio_btn" />
														<span><?php _e('IP Log', 'mystickyelements'); ?></span>
													</label>
													<div class="upgrade-myStickyelements-link">
														<a href="<?php echo esc_url($upgarde_url); ?>" target="_blank">
															<i class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?>
														</a>
													</div>
												</div>
												<span class="contact-form-dropdfown-close"><i class="fas fa-times"></i></span>
											</div>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td>
                                            <h4><?php _e('Submit Button', 'mystickyelements'); ?></h4>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Background Color:', 'mystickyelements'); ?></label>
                                                <input type="text" id="submit_button_background_color"
                                                       name="contact-form[submit_button_background_color]"
                                                       class="mystickyelement-color"
                                                       value="<?php echo esc_attr($contact_form['submit_button_background_color']); ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Text Color:', 'mystickyelements'); ?></label>
                                                <input type="text" id="submit_button_text_color"
                                                       name="contact-form[submit_button_text_color]"
                                                       class="mystickyelement-color"
                                                       value="<?php echo esc_attr($contact_form['submit_button_text_color']); ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Text on the submit button', 'mystickyelements'); ?></label>
                                                <input type="text" id="contact-form-submit-button"
                                                       name="contact-form[submit_button_text]"
                                                       value="<?php echo $contact_form['submit_button_text']; ?>"
                                                       placeholder="<?php _e('Enter text here...', 'mystickyelements'); ?>"/>
                                            </div>
                                            <div class="myStickyelements-redirect-link-wrap myStickyelements-setting-wrap">
                                                <label>
                                                    <input type="checkbox" name="contact-form[redirect]"
                                                           value="1" <?php checked(@$contact_form['redirect'], '1'); ?>
                                                           disabled/>
                                                    &nbsp; <?php _e('Redirect visitors after submission', 'mystickyelements'); ?>
                                                </label>
                                                <div class="redirect-link-input">
                                                    <input type="text" name="contact-form[redirect_link]"
                                                           value="<?php echo @$contact_form['redirect_link']; ?>"
                                                           class="myStickyelements-redirect-link"
                                                           placeholder="<?php _e('Enter redirect link', 'mystickyelements'); ?>"
                                                           disabled/>
                                                    <span class="upgrade-myStickyelements"><a
                                                                href="<?php echo esc_url($upgarde_url); ?>"
                                                                target="_blank"><i
                                                                    class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
                                                </div>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Thank you message', 'mystickyelements'); ?></label>
                                                <?php $thank_you_message = 'Your message was sent successfully'; ?>

                                                <div class="myStickyelements-thankyou-input">
                                                    <input type="text" name="contact-form[thank_you_message]"
                                                           value="<?php echo $thank_you_message; ?>"
                                                           placeholder="<?php _e('Enter thank you message here...', 'mystickyelements'); ?>"
                                                           disabled/>
                                                    <span class="upgrade-myStickyelements"><a
                                                                href="<?php echo esc_url($upgarde_url); ?>"
                                                                target="_blank"><i
                                                                    class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td><h4 class=""><?php _e('Contact Tab Settings', 'mystickyelements'); ?></h4>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Devices', 'mystickyelements'); ?></label>
                                                <div class="myStickyelements-setting-right">
                                                    <label>
                                                        <input type="checkbox" name="contact-form[desktop]"
                                                               value="1"<?php checked(@$contact_form['desktop'], '1'); ?> />
                                                        &nbsp;<?php _e('Desktop', 'mystickyelements'); ?>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" name="contact-form[mobile]"
                                                               value="1" <?php checked(@$contact_form['mobile'], '1'); ?> />
                                                        &nbsp;<?php _e('Mobile', 'mystickyelements'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Direction', 'mystickyelements'); ?></label>
                                                <div class="myStickyelements-inputs myStickyelements-setting-right myStickyelements-direction-rtl">
                                                    <label>
                                                        <input type="radio" name="contact-form[direction]"
                                                               value="LTR" <?php checked(@$contact_form['direction'], 'LTR'); ?> />
                                                        &nbsp;<?php _e('LTR', 'mystickyelements'); ?>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="contact-form[direction]"
                                                               value="RTL" <?php checked(@$contact_form['direction'], 'RTL'); ?> />
                                                        &nbsp;<?php _e('RTL', 'mystickyelements'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Background Color:', 'mystickyelements'); ?></label>
                                                <input type="text" id="tab_background_color"
                                                       name="contact-form[tab_background_color]"
                                                       class="mystickyelement-color"
                                                       value="<?php echo $contact_form['tab_background_color']; ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Text Color:', 'mystickyelements'); ?></label>
                                                <input type="text" id="tab_text_color"
                                                       name="contact-form[tab_text_color]" class="mystickyelement-color"
                                                       value="<?php echo $contact_form['tab_text_color']; ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Form Headline Color:', 'mystickyelements'); ?></label>
                                                <input type="text" id="headine_text_color"
                                                       name="contact-form[headine_text_color]"
                                                       class="mystickyelement-color"
                                                       value="<?php echo $contact_form['headine_text_color']; ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Text in tab', 'mystickyelements'); ?></label>
                                                <input type="text" name="contact-form[text_in_tab]"
                                                       value="<?php echo $contact_form['text_in_tab']; ?>"
                                                       placeholder="<?php _e('Enter text here...', 'mystickyelements'); ?>"/>
                                            </div>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label><?php _e('Send leads to', 'mystickyelements'); ?></label>
                                                <div class="myStickyelements-setting-right">
                                                    <select name="contact-form[send_leads]"
                                                            id="contact-form-send-leads">
                                                        <option value="database"
                                                                selected><?php _e('Local Database', 'mystickyelements'); ?></option>
                                                        <option value="mail"
                                                                disabled><?php _e('Email (Upgrade now)', 'mystickyelements'); ?></option>
                                                    </select>
                                                </div>
                                                <div id="contact-form-send-mail"
                                                     class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list"
                                                     style="display:none">
                                                    <label><?php _e('Email', 'mystickyelements'); ?></label>
                                                    <input type="text" name="contact-form[sent_to_mail]"
                                                           value="<?php echo $contact_form['sent_to_mail']; ?>"
                                                           placeholder="<?php _e('Enter your email', 'mystickyelements'); ?>"/>
                                                    <p class="description"><?php esc_html_e('Check your Spam folder.', 'mystickyelements'); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="myStickyelements-contact-form-field-hide">
                                        <td>
                                            <div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
                                                <label> <?php _e('Form CSS', 'mystickyelements'); ?></label>
                                                <span class="upgrade-myStickyelements"><a
                                                            href="<?php echo $upgarde_url ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
                                                <div class="redirect-link-input myStickyelements-setting-right">
                                                    <textarea name="contact-form[form_css]" rows="5" cols="50"
                                                              placeholder="<?php _e('Enter your form css', 'mystickyelements'); ?>"
                                                              disabled><?php echo @$contact_form['form_css']; ?></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Social Channels Tabs Section -->
                            <div class="myStickyelements-container myStickyelements-social-channels-tabs">
                                <div class="myStickyelements-header-title">
                                    <h3><?php _e('Social Channels Tabs', 'mystickyelements'); ?></h3>
                                    <label for="myStickyelements-social-channels-enabled"
                                           class="myStickyelements-switch">
                                        <input type="checkbox" id="myStickyelements-social-channels-enabled"
                                               name="social-channels[enable]"
                                               value="1" <?php checked(@$social_channels['enable'], '1'); ?> />
                                        <span class="slider round"></span>
                                    </label>
									<div class="myStickyelements-social-search">
										<div class="myStickyelements-social-search-wrap">
											<i class="fas fa-search"></i>
											<input type="text" placeholder="Search social channels" />
										</div>
									</div>
                                </div>
                                <ul class="myStickyelements-social-channels-lists">
                                    <?php foreach ($social_channels_lists as $key => $value): if (isset($value['is_locked']) && $value['is_locked'] == 1) {
                                        continue;
                                    } ?>

                                    <li data-search="<?php echo str_replace("_", " ", $key); ?>" <?php if (isset($value['is_locked']) && $value['is_locked'] == 1): ?> class="upgrade-myStickyelements" <?php endif; ?>>
                                        <label>
                                            <span class="social-channels-list social-<?php echo esc_attr($key); ?>" style="background-color: <?php echo $value['background_color'] ?>">
												<i class="<?php echo esc_attr($value['class']); ?>"></i>
											</span>
                                            <input type="checkbox" data-social-channel="<?php echo esc_attr($key); ?>" class="social-channel" name="social-channels[<?php echo esc_attr($key); ?>]" value="1" <?php checked(@$social_channels[$key], '1'); ?> <?php if (isset($value['is_locked']) && $value['is_locked'] == 1) { echo "disabled"; } ?>/>
                                        </label>
										<span class="social-tooltip-popup">
											<?php echo ucwords(str_replace("_", " ", $key)); ?>
										</span>
									</li><?php endforeach; ?>
                                    <li data-search="" class="upgrade-myStickyelements">
                                        <ul>
                                            <?php foreach ($social_channels_lists as $key => $value): if (!isset($value['is_locked'])) {
                                                continue;
                                            } ?>
											<li data-search="<?php echo str_replace("_", " ", $key); ?>">
												<label>
													<span class="social-channels-list social-<?php echo esc_attr($key); ?>"
														  style="background-color: <?php echo $value['background_color'] ?>"><i
																class="<?php echo esc_attr($value['class']); ?>"></i></span>
													<input type="checkbox"
														   data-social-channel="<?php echo esc_attr($key); ?>"
														   class="social-channel"
														   name="social-channels[<?php echo esc_attr($key); ?>]"
														   value="1" <?php checked(@$social_channels[$key], '1'); ?>   <?php if (isset($value['is_locked']) && $value['is_locked'] == 1) {
														echo "disabled";
													} ?>/>
												</label>
											</li>
											<?php endforeach; ?>
											<li data-search="" class="upgrade-myStickyelements-link">
												<a href="<?php echo esc_url($upgarde_url); ?>" target="_blank">
													<i class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?>
												</a>
											</li>
                                        </ul>
                                    </li>
                                </ul>

                                <div class="social-channel-popover" style="display:none;">
                                    <a href="<?php echo $upgarde_url ?>" target="_blank">
                                        <?php _e('Get unlimited channels in the Pro plan', 'mystickyelements'); ?>
                                        <strong><?php _e('Upgrade Now', 'mystickyelements'); ?></strong>
                                    </a>
                                </div>

                                <div class="myStickyelements-social-channels-info">
                                    <div class="social-channels-tab">
                                        <?php
                                        if (!empty($social_channels_tabs)) {
                                            global $social_channel_count;
                                            $social_channel_count = 1;
                                            foreach ($social_channels_tabs as $key => $value) {
                                                $this->mystickyelement_social_tab_add($key);
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>

                            <!-- General Settings Section -->
                            <div class="myStickyelements-container myStickyelements-general-settings">
                                <div class="myStickyelements-header-title">
                                    <h3><?php _e('General Settings', 'mystickyelements'); ?></h3>
                                </div>

                                <div class="myStickyelements-content-section">
                                    <table>
                                        <tr>
                                            <td>
                                                <span class="myStickyelements-label"><?php _e('Templates', 'mystickyelements'); ?></span>
                                                <div class="myStickyelements-inputs myStickyelements-label">
                                                    <?php $general_settings['templates'] = (isset($general_settings['templates']) && $general_settings['templates'] != '') ? $general_settings['templates'] : 'default'; ?>
                                                    <select id="myStickyelements-inputs-templete"
                                                            name="general-settings[templates]">
                                                        <option value="default" <?php selected(@$general_settings['templates'], 'default'); ?>><?php _e('Default', 'mystickyelements'); ?></option>
                                                        <option value="roundad" <?php selected(@$general_settings['templates'], 'roundad'); ?>><?php _e('Rounded', 'mystickyelements'); ?></option>
                                                        <option value="leaf_right" <?php selected(@$general_settings['templates'], 'leaf_right'); ?>><?php _e('Leaf right', 'mystickyelements'); ?></option>
                                                        <option value="round" <?php selected(@$general_settings['templates'], 'round'); ?>><?php _e('Round', 'mystickyelements'); ?></option>
                                                        <option value="diamond" <?php selected(@$general_settings['templates'], 'diamond'); ?>><?php _e('Diamond', 'mystickyelements'); ?></option>
                                                        <option value="leaf_left" <?php selected(@$general_settings['templates'], 'leaf_left'); ?>><?php _e('Leaf left', 'mystickyelements'); ?></option>
                                                        <option value="arrow" <?php selected(@$general_settings['templates'], 'arrow'); ?>><?php _e('Arrow', 'mystickyelements'); ?></option>
                                                        <option value="triangle" <?php selected(@$general_settings['templates'], 'triangle'); ?>><?php _e('Triangle', 'mystickyelements'); ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td rowspan="7">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="myStickyelements-label"><?php _e('Position on desktop', 'mystickyelements'); ?></span>
                                                <div class="myStickyelements-inputs">
                                                    <ul>
                                                        <li>
                                                            <label>
                                                                <input type="radio" name="general-settings[position]"
                                                                       value="left" <?php checked(@$general_settings['position'], 'left'); ?> />
                                                                <?php _e('Left', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                        <li class="myStickyelements-pos-rtl">
                                                            <label>
                                                                <input type="radio" name="general-settings[position]"
                                                                       value="right" <?php checked(@$general_settings['position'], 'right'); ?> />
                                                                <?php _e('Right', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label>
                                                                <input type="radio" name="general-settings[position]"
                                                                       value="bottom" <?php checked(@$general_settings['position'], 'bottom'); ?> />
                                                                <?php _e('Bottom', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td rowspan="7"></td>
                                        </tr>
										<tr class="myStickyelements-position-on-screen-wrap" style="<?php echo (isset($general_settings['position']) && $general_settings['position'] != 'bottom') ? 'display: none;' : ''; ?>">
											<td>
												<span class="myStickyelements-label" ><?php _e( 'Position on screen', 'mystickyelements' );?></span>
												<div class="myStickyelements-inputs myStickyelements-label">
													<?php $general_settings['position_on_screen'] = (isset($general_settings['position_on_screen']) && $general_settings['position_on_screen']!= '') ? $general_settings['position_on_screen'] : 'center'; ?>
													<select id="myStickyelements-inputs-position-on-screen" name="general-settings[position_on_screen]" >
														<option value="center" <?php selected( @$general_settings['position_on_screen'], 'center' ); ?>><?php _e( 'Center', 'mystickyelements' );?></option>
														<option value="left" <?php selected( @$general_settings['position_on_screen'], 'left' ); ?>><?php _e( 'Left', 'mystickyelements' );?></option>
														<option value="right" <?php selected( @$general_settings['position_on_screen'], 'right' ); ?>><?php _e( 'Right', 'mystickyelements' );?></option>
													</select>
												</div>
											</td>
										</tr>
                                        <tr>
                                            <td>
                                                <span class="myStickyelements-label"><?php _e('Position on mobile', 'mystickyelements'); ?></span>
                                                <div class="myStickyelements-inputs">
                                                    <ul>
                                                        <li>
                                                            <label>
                                                                <input type="radio"
                                                                       name="general-settings[position_mobile]"
                                                                       value="left" <?php checked(@$general_settings['position_mobile'], 'left'); ?> />
                                                                <?php _e('Left', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                        <li class="myStickyelements-pos-rtl">
                                                            <label>
                                                                <input type="radio"
                                                                       name="general-settings[position_mobile]"
                                                                       value="right" <?php checked(@$general_settings['position_mobile'], 'right'); ?> />
                                                                <?php _e('Right', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
														<li>
															<label>
																<input type="radio" name="general-settings[position_mobile]" value="top" <?php checked( @$general_settings['position_mobile'], 'top' );?> />
																<?php _e( 'Top', 'mystickyelements' );?>
															</label>
														</li>
                                                        <li>
                                                            <label>
                                                                <input type="radio"
                                                                       name="general-settings[position_mobile]"
                                                                       value="bottom" <?php checked(@$general_settings['position_mobile'], 'bottom'); ?> />
                                                                <?php _e('Bottom', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="myStickyelements-label"><?php _e('Open tabs when', 'mystickyelements'); ?></span>
                                                <div class="myStickyelements-inputs">
                                                    <ul>
                                                        <li>
                                                            <label>
                                                                <input type="radio"
                                                                       name="general-settings[open_tabs_when]"
                                                                       value="hover" <?php checked(@$general_settings['open_tabs_when'], 'hover'); ?> />
                                                                <?php _e('Hover', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label>
                                                                <input type="radio"
                                                                       name="general-settings[open_tabs_when]"
                                                                       value="click" <?php checked(@$general_settings['open_tabs_when'], 'click'); ?> />
                                                                <?php _e('Click', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<label for="custom_position"><?php _e('Custom Position(Y)', 'mystickyelements'); ?></label>
												<span class="upgrade-myStickyelements"><a
                                                            href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
											</span>

                                                <div class="myStickyelements-inputs">
                                                    <div class="px-wrap">
                                                        <input type="text" id="custom_position"
                                                               name="general-settings[custom_position]"
                                                               value="<?php echo @$general_settings['custom_position']; ?>"
                                                               placeholder="0" disabled/>
                                                        <span class="input-px">PX</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<label>													
													<?php esc_html_e('Minimize tab', 'mystickyelements'); ?>
												</label>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label myStickyelements-minimize-tab">
                                                    <label for="myStickyelements-minimize-tab"
                                                           class="myStickyelements-switch">
                                                        <input type="checkbox" id="myStickyelements-minimize-tab"
                                                               name="general-settings[minimize_tab]"<?php checked(@$general_settings['minimize_tab'], '1'); ?>
                                                               value="1"/>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    &nbsp;
                                                    <input type="text" id="minimize_tab_background_color"
                                                           name="general-settings[minimize_tab_background_color]"
                                                           class="mystickyelement-color"
                                                           value="<?php echo esc_attr($general_settings['minimize_tab_background_color']); ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="myStickyelements-minimized">
                                            <td>
											<span class="myStickyelements-label">
												<label>
													<?php esc_html_e('Minimized bar on load', 'mystickyelements'); ?>
												</label>
											</span>
                                                <div class="myStickyelements-inputs">
                                                    <ul>
                                                        <li>
                                                            <label>
                                                                <input type="checkbox"
                                                                       name="general-settings[minimize_desktop]"
                                                                       value="desktop" <?php checked(@$general_settings['minimize_desktop'], 'desktop'); ?> />
                                                                <?php _e('Desktop', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label>
                                                                <input type="checkbox"
                                                                       name="general-settings[minimize_mobile]"
                                                                       value="mobile" <?php checked(@$general_settings['minimize_mobile'], 'mobile'); ?> />
                                                                <?php _e('Mobile', 'mystickyelements'); ?>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<label for="custom_position"><?php _e('Google Analytics Events', 'mystickyelements'); ?></label>
												<span class="upgrade-myStickyelements"><a
                                                            href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label">

                                                    <label for="myStickyelements-google-alanytics-enabled"
                                                           class="myStickyelements-switch">
                                                        <input type="checkbox"
                                                               id="myStickyelements-google-alanytics-enabled"
                                                               name="general-settings[google_analytics]"
                                                               value="1" <?php checked(@$general_settings['google_analytics'], '1'); ?>
                                                               disabled/>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<?php _e('Font Family', 'mystickyelements'); ?></label>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label">
                                                    <select name="general-settings[font_family]" class="form-fonts">
                                                        <option value=""><?php _e('Select font family', 'mystickyelements'); ?></option>
                                                        <?php $group = '';
                                                        foreach (mystickyelements_fonts() as $key => $value):
                                                            if ($value != $group) {
                                                                echo '<optgroup label="' . $value . '">';
                                                                $group = $value;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $key; ?>" <?php selected(@$general_settings['font_family'], $key); ?>><?php echo $key; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<?php _e('Desktop Widget size', 'mystickyelements'); ?>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label">
                                                    <?php $general_settings['widget-size'] = (isset($general_settings['widget-size']) && $general_settings['widget-size'] != '') ? $general_settings['widget-size'] : 'medium'; ?>
                                                    <select id="myStickyelements-widget-size" name="general-settings[widget-size]">
                                                        <option value="small" <?php selected(@$general_settings['widget-size'], 'small'); ?>><?php _e('Small', 'mystickyelements'); ?></option>
                                                        <option value="medium" <?php selected(@$general_settings['widget-size'], 'medium'); ?>><?php _e('Medium', 'mystickyelements'); ?></option>
                                                        <option value="large" <?php selected(@$general_settings['widget-size'], 'large'); ?>><?php _e('Large', 'mystickyelements'); ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
										<tr>
										<td>
											<span class="myStickyelements-label" >
												<?php _e( 'Mobile Widget size', 'mystickyelements' );?>
											</span>
											<div class="myStickyelements-inputs myStickyelements-label">
												<?php $general_settings['mobile-widget-size'] = (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? $general_settings['mobile-widget-size'] : 'medium'; ?>
												<select id="myStickyelements-widget-mobile-size" name="general-settings[mobile-widget-size]" >
													<option value="small" <?php selected( @$general_settings['mobile-widget-size'], 'small' ); ?>><?php _e( 'Small', 'mystickyelements' );?></option>
													<option value="medium" <?php selected( @$general_settings['mobile-widget-size'], 'medium' ); ?>><?php _e( 'Medium', 'mystickyelements' );?></option>
													<option value="large" <?php selected( @$general_settings['mobile-widget-size'], 'large' ); ?>><?php _e( 'Large', 'mystickyelements' );?></option>
												</select>
											</div>
										</td>
									</tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<?php _e('Entry effect', 'mystickyelements'); ?></label>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label">
                                                    <?php $general_settings['entry-effect'] = (isset($general_settings['entry-effect']) && $general_settings['entry-effect'] != '') ? $general_settings['entry-effect'] : 'slide-in'; ?>
                                                    <select id="myStickyelements-entry-effect"
                                                            name="general-settings[entry-effect]">
                                                        <option value="none" <?php selected(@$general_settings['entry-effect'], 'none'); ?>><?php _e('None', 'mystickyelements'); ?></option>
                                                        <option value="slide-in" <?php selected(@$general_settings['entry-effect'], 'slide-in'); ?>><?php _e('Slide in', 'mystickyelements'); ?></option>
                                                        <option value="fade" <?php selected(@$general_settings['entry-effect'], 'fade'); ?>><?php _e('Fade', 'mystickyelements'); ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="show-on-apper">
                                            <td colspan="2">
                                                <div class="myStickyelements-show-on-wrap">
													<span class="myStickyelements-label">
														<label><?php _e('Show on Pages', 'mystickyelements'); ?></label>
														<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
															<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
															<p><?php esc_html_e("Show or don't show the widget on specific pages. You can use rules like contains, exact match, starts with, and ends with", 'mystickyelements'); ?></p>
														</div>
													</span>
                                                    <div class="myStickyelements-show-on-right">
                                                        <div class="myStickyelements-page-options myStickyelements-inputs"
                                                             id="myStickyelements-page-options">
                                                            <?php $page_option = (isset($general_settings['page_settings'])) ? $general_settings['page_settings'] : array();
                                                            $url_options = array(
                                                                'page_contains' => 'pages that contain',
                                                                'page_has_url' => 'a specific page',
                                                                'page_start_with' => 'pages starting with',
                                                                'page_end_with' => 'pages ending with',
                                                            );
                                                            
                                                            if (!empty($page_option) && is_array($page_option)) {
                                                                $count = 0;
                                                                foreach ($page_option as $k => $option) {
                                                                    $count++;
                                                                    ?>
                                                                    <div class="myStickyelements-page-option <?php echo $k == count($page_option) ? "last" : ""; ?>">
                                                                        <div class="url-content">
                                                                            <div class="myStickyelements-url-select">
                                                                                <select name="general-settings[page_settings][<?php echo $count; ?>][shown_on]"
                                                                                        id="url_shown_on_<?php echo $count ?>_option">
                                                                                    <option value="show_on" <?php echo $option['shown_on'] == "show_on" ? "selected" : "" ?> >
                                                                                        Show on
                                                                                    </option>
                                                                                    <option value="not_show_on" <?php echo $option['shown_on'] == "not_show_on" ? "selected" : "" ?>>
                                                                                        Don't show on
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="myStickyelements-url-option">
                                                                                <select class="myStickyelements-url-options"
                                                                                        name="general-settings[page_settings][<?php echo $count; ?>][option]"
                                                                                        id="url_rules_<?php echo $count ?>_option">
                                                                                    <option disabled value="">Select
                                                                                        Rule
                                                                                    </option>
                                                                                    <?php foreach ($url_options as $key => $value) {
                                                                                        $selected = $option['option'] == $key ? " selected='selected' " : "";
                                                                                        echo '<option ' . $selected . ' value="' . $key . '">' . $value . '</option>';
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="myStickyelements-url-box">
                                                                                <span class='myStickyelements-url'><?php echo site_url("/"); ?></span>
                                                                            </div>
                                                                            <div class="myStickyelements-url-values">
                                                                                <input type="text"
                                                                                       value="<?php echo $option['value'] ?>"
                                                                                       name="general-settings[page_settings][<?php echo $count; ?>][value]"
                                                                                       id="url_rules_<?php echo $count; ?>_value"/>
                                                                            </div>
                                                                            <div class="myStickyelements-url-buttons">
                                                                                <a class="myStickyelements-remove-rule"
                                                                                   href="javascript:;">x</a>
                                                                            </div>
                                                                            <div class="clear"></div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>

                                                        </div>
                                                        <a href="javascript:void(0);" class="create-rule"
                                                           id="create-rule">Add Rule</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
										<tr>
											<td>
												<span class="myStickyelements-label" >
													<label for="custom_position"><?php _e( "Country targeting", 'mystickyelements' );?></label>
													<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
														<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
														<p><?php esc_html_e("Target your widget to specific countries. You can create different widgets for different countries", 'mystickyelements'); ?></p>
													</div>
												</span>
												<div class="myStickyelements-inputs myStickyelements-label myStickyelements-country-inputs">
													<select name="general-settings[countries_list][]" placeholder="Select Country" class="myStickyelements-country-list">
															<option value=""><?php _e("All countries", 'mystickyelements'); ?></option>
													</select>
													<span class="upgrade-myStickyelements">
														<a href="<?php echo esc_url($upgarde_url); ?>" target="_blank">
															<i class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?>
														</a>
													</span>
												</div>
											</td>
										</tr>
                                        <tr>
                                            <td>
											<span class="myStickyelements-label">
												<label for="custom_position"><?php _e("Remove 'Get Widget' Credit", 'mystickyelements'); ?></label>
												<span class="upgrade-myStickyelements"><a
                                                            href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
											</span>
                                                <div class="myStickyelements-inputs myStickyelements-label">
                                                    <label for="myStickyelements-remove-widget-enabled"
                                                           class="myStickyelements-switch">
                                                        <input type="checkbox"
                                                               id="myStickyelements-remove-widget-enabled"
                                                               name="contact-form[remove_widget]" value="1" disabled/>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="tab-css-apper">
                                            <td>
											<span class="myStickyelements-label"><label
                                                        for="general-settings-tabs-css"><?php _e('Tabs CSS', 'mystickyelements'); ?></label>
											<span class="upgrade-myStickyelements"><a href="<?php echo $upgarde_url ?>"
                                                                                      target="_blank"><i
                                                            class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
											</span>
                                                <textarea name="general-settings[tabs_css]" rows="5" cols="50"
                                                          id="general-settings-tabs-css" class="large-text code"
                                                          disabled><?php echo @$general_settings['tabs_css']; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" id="myStickyelements_site_url"
                                           value="<?php echo site_url("/") ?>">
                                    <div class="myStickyelements-page-options-html" style="display: none">
                                        <div class="myStickyelements-page-option">
                                            <div class="url-content">
                                                <div class="myStickyelements-url-select">
                                                    <select name="general-settings[page_settings][__count__][shown_on]"
                                                            id="url_shown_on___count___option" <?php echo !$is_pro_active ? "disabled" : "" ?>>
                                                        <option value="show_on">Show on</option>
                                                        <option value="not_show_on">Don't show on</option>
                                                    </select>
                                                </div>
                                                <div class="myStickyelements-url-option">
                                                    <select class="myStickyelements-url-options"
                                                            name="general-settings[page_settings][__count__][option]"
                                                            id="url_rules___count___option" <?php echo !$is_pro_active ? "disabled" : "" ?>>
                                                        <option selected="selected" disabled value="">Select Rule
                                                        </option>
                                                        <?php foreach ($url_options as $key => $value) {
                                                            echo '<option value="' . $key . '">' . $value . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="myStickyelements-url-box">
                                                    <span class='myStickyelements-url'><?php echo site_url("/"); ?></span>
                                                </div>
                                                <div class="myStickyelements-url-values">
                                                    <input type="text" value=""
                                                           name="general-settings[page_settings][__count__][value]"
                                                           id="url_rules___count___value" <?php echo !$is_pro_active ? "disabled" : "" ?> />
                                                </div>
                                                <div class="myStickyelements-url-buttons">
                                                    <a class="myStickyelements-remove-rule"
                                                       href="javascript:void(0);">x</a>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <?php if (!$is_pro_active) { ?>
                                                <span class="upgrade-myStickyelements"><a
                                                            href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i
                                                                class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements'); ?></a></span>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-form-dropdown-open" style="display: none;">
                                <input type="text" name="contact-form[dropdown-placeholder]"
                                       class="contact-form-dropdown-select"
                                       value="<?php echo esc_attr(@$contact_form['dropdown-placeholder']); ?>"
                                       placeholder="<?php esc_html_e('Select...', 'mystickyelement'); ?>"/>
                                <div class="contact-form-dropdown-option">
                                    <div class="option-value-field">
                                        <span class="move-icon"></span>
                                        <input type="text" name="contact-form[dropdown-option][]" value=""/> <span
                                                class="add-dropdown-option"><?php esc_html_e('Add', 'mystickyelement'); ?></span>
                                    </div>
                                    <?php if (isset($contact_form['dropdown-option']) && !empty($contact_form['dropdown-option'])) :
                                        foreach ($contact_form['dropdown-option'] as $option) :
                                            if ($option == '') {
                                                continue;
                                            }
                                            ?>
                                            <div class="option-value-field">
                                                <span class="move-icon"></span>
                                                <input type="text" name="contact-form[dropdown-option][]"
                                                       value="<?php echo esc_attr($option); ?>"/> <span
                                                        class="delete-dropdown-option"><i
                                                            class="fas fa-times"></i></span>
                                            </div>
                                        <?php
                                        endforeach;
                                    endif; ?>

                                </div>
                                <input type="submit" name="submit" class="button button-primary"
                                       value="<?php _e('Save', 'mystickyelements'); ?>">

                                <span class="contact-form-dropdfown-close"><i class="fas fa-times"></i></span>
                            </div>

                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button button-primary"
                                       value="<?php _e('Save Changes', 'mystickyelements'); ?>">
                            </p>
							<input type="hidden" id="mystickyelement_save_confirm_status" name="mystickyelement_save_confirm_status" value="">
                            <?php wp_nonce_field('mystickyelement-submit', 'mystickyelement-submit'); ?>
                        </form>
                    </div>
                </div>
                <?php
				$table_name = $wpdb->prefix . "mystickyelement_contact_lists";
				$result = $wpdb->get_results ( "SELECT count(*) as count FROM ".$table_name ." ORDER BY ID DESC" );

				if ( $result[0]->count != 0 && !get_option( 'myStickyelements_show_leads' )) { ?>
					<div id="myStickyelements-new-lead-confirm" style="display:none;" title="<?php esc_attr_e( 'Congratulations 🎉', 'mystickyelement-submit-delete' ); ?>">
						<p><?php _e('You just got your first My Sticky Elements lead. Click on the Show Me button to display your contact form leads' ); ?></p>
					</div>
					<script>
						( function( $ ) {
							"use strict";
							$(document).ready(function(){
								jQuery( "#myStickyelements-new-lead-confirm" ).dialog({
									resizable: false,
									modal: true,
									draggable: false,
									height: 'auto',
									width: 400,
									buttons: {
										"Show Me": {
												click: function () {
													window.location = "<?php echo admin_url('admin.php?page=my-sticky-elements-leads')?>";
													//$(this).dialog('close');
												},
												text: 'Show Me',
												class: 'purple-btn'
											},
											"Not Now": {
												click: function () {
													confetti.remove();
													$(this).dialog('close');
												},
												text: 'Not Now',
												class: 'gray-btn'
											},
									}
								});
								confetti.start();
								$('#myStickyelements-new-lead-confirm').bind('dialogclose', function(event) {
									confetti.remove();
								});
							});
						})( jQuery );
					</script>
					<?php
					update_option( 'myStickyelements_show_leads', 1 );
				}
				?>
				<div id="mystickyelement-save-confirm" style="display:none;" title="<?php esc_attr_e( 'Icons\' text isn\'t supported in this template', 'mystickyelement-submit-delete' ); ?>">
					<p><?php _e("The selected template doesn't support icons'text, please change to the Default templates. Would you like to publish it anyway?", 'mystickyelement' ); ?></p>
				</div>
				<?php
				$mystickyelements_popup_status = get_option( 'mystickyelements_intro_popup' );
				if( $mystickyelements_popup_status == 'show' ) {
					require_once MYSTICKYELEMENTS_PATH . 'mystickyelements-popup.php';
				}
                require_once MYSTICKYELEMENTS_PATH . 'help.php';
            }
		}

		public function mystickyelement_social_tab_add( $key) {
			global $social_channel_count;
			$social_channel = (isset($_POST['social_channel'])) ? $_POST['social_channel'] : $key ;
			if ( $key == "is_empty" ){
				return;
			}
			if ( $social_channel != '') {
				$social_channels_lists = mystickyelements_social_channels();
				$social_channels_list = $social_channels_lists[$social_channel];
				$social_channels_tabs = get_option( 'mystickyelements-social-channels-tabs', true );
				$social_channel_value = ( isset($social_channels_tabs[$key])) ? $social_channels_tabs[$key] : array();
                $social_channels_list['icon_text'] = isset($social_channels_list['icon_text'])?$social_channels_list['icon_text']:"";
                $social_channels_list['icon_text_size'] = isset($social_channels_list['icon_text_size'])?$social_channels_list['icon_text_size']:"";
                $social_channels_list['background_color'] = isset($social_channels_list['background_color'])?$social_channels_list['background_color']:"";
                $social_channels_list['hover_text'] = isset($social_channels_list['hover_text'])?$social_channels_list['hover_text']:"";


				if ( empty($social_channel_value)) {
					$social_channel_value['text'] = '';
					$social_channel_value['icon_text'] = $social_channels_list['icon_text'];
					$social_channel_value['icon_text_size'] = $social_channels_list['icon_text_size'];
					$social_channel_value['bg_color'] = $social_channels_list['background_color'];
					$social_channel_value['hover_text'] = $social_channels_list['hover_text'];
					$social_channel_value['desktop'] = 1;
					$social_channel_value['mobile'] = 1;
				}

                if ( !isset($social_channel_value['icon_text'])) {
                    $social_channel_value['icon_text'] = '';
                }
                if ( !isset($social_channel_value['icon_text_size'])) {
                    $social_channel_value['icon_text_size'] = '';
                }
                if ( !isset($social_channel_value['icon_color'])) {
                    $social_channel_value['icon_color'] = '';
                }
                if ( !isset($social_channel_value['pre_set_message'])) {
                    $social_channel_value['pre_set_message'] = '';
                }
				
				if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && isset($social_channel_value['fontawesome_icon']) && $social_channel_value['fontawesome_icon'] != '' ) {
					$social_channels_list['class'] = $social_channel_value['fontawesome_icon'];
				} else {
					$social_channel_value['fontawesome_icon'] = '';
				}
				if ( !isset($social_channels_list['custom_icon']) && !isset($social_channel_value['custom_icon']) ) {
					$social_channel_value['custom_icon'] = '';
				}
				
				if ( $key == 'line') {
					echo "<style>.social-channels-item .social-channel-input-box .social-". $key ." svg .fil1{ fill:" .$social_channel_value['icon_color']. "}</style>";
				}
				if ( $key == 'qzone') {
					echo "<style>.social-channels-item .social-channel-input-box .social-". $key ." svg .fil2{ fill:" . $social_channel_value['icon_color'] . "}</style>";
				}
				
				$social_channel_value['text'] = str_replace('\"', '"', $social_channel_value['text']);
				?>
				<div class="social-channels-item" data-slug="<?php echo esc_attr($social_channel); ?>">
					<div class="move-icon"></div>
					<span id="<?php echo esc_attr("social-".$social_channel . "-number"); ?>" class="social-channel-number"><?php echo sprintf("%02d", $social_channel_count++);?></span>
					<div class="social-channel-input-box">
						<label>
							<span class="social-channels-list social-<?php echo esc_attr($social_channel);?>" style="background-color: <?php echo esc_attr($social_channel_value['bg_color'])?>; color: <?php echo esc_attr($social_channel_value['icon_color'])?>; position:relative;">
								<?php if (isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && isset($social_channel_value['custom_icon']) && $social_channel_value['custom_icon'] != '' && isset($social_channel_value['fontawesome_icon']) && $social_channel_value['fontawesome_icon'] == ''): ?>
									<img src="<?php echo esc_url($social_channel_value['custom_icon']); ?>" width="25" height="25"/>
								<?php 
								else:
									if ( isset($social_channels_list['custom_svg_icon']) && $social_channels_list['custom_svg_icon'] != '' ) :
										echo $social_channels_list['custom_svg_icon'];
									else:?>
									<i class="<?php echo esc_attr($social_channels_list['class'])?>"></i>
									<?php endif;
								endif;
								?>
								<span class="social-tooltip-popup">
									<?php echo ucwords(str_replace("_", " ", $social_channel)); ?>
								</span>
							</span>
						</label>
						<input type="text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][text]" value="<?php echo esc_attr(stripslashes($social_channel_value['text']));?>" placeholder="<?php echo esc_attr($social_channels_list['placeholder'])?>"/>
					</div>
					<div class="myStickyelements-channel-view">
						<?php if ( isset($social_channels_list['tooltip']) && $social_channels_list['tooltip'] != "" ) : ?>
							<label class="social-tooltip" >
								<span>
									<i class="fas fa-info"></i>
									<span class="social-tooltip-popup">
										<?php echo $social_channels_list['tooltip']; ?>
									</span>
								</span>
							</label>
						<?php endif; ?>
						<label class="social-channel-view">
							<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][desktop]" data-social-channel-view="<?php echo esc_attr($social_channel);?>" value= "1" class="social-channel-view-desktop" id="social_channel_<?php echo esc_attr($social_channel);?>_desktop" <?php checked( @$social_channel_value['desktop'], '1' );?> /> &nbsp;<?php _e( 'Desktop', 'mystickyelements' );?>
						</label>
						<label class="social-channel-view">
							<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][mobile]" data-social-channel-view="<?php echo esc_attr($social_channel);?>" value="1" class="social-channel-view-mobile" id="social_channel_<?php echo esc_attr($social_channel);?>_mobile" <?php checked( @$social_channel_value['mobile'], '1' );?> /> &nbsp;<?php _e( 'Mobile', 'mystickyelements' );?>
						</label>
						<label class="social-setting" data-slug="<?php echo $social_channel; ?>"><i class="fas fa-cog"></i>&nbsp; <?php _e( 'Settings', 'mystickyelements' );?></label>
					</div>
					<div class="social-channel-setting" style="display:none;">
						<table>
							<?php if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1) :?>
							<tr>
								<td colspan="2" style="text-align:left;">
									<div class="myStickyelements-custom-image-icon">
										<div class="myStickyelements-custom-image">
											<input type="button" data-slug="<?php echo esc_attr($social_channel);?>" name="social-channels-icon"  class="button-secondary social-custom-icon-upload-button" value="<?php esc_attr_e( 'Upload Custom Icon', 'mystickyelements'); ?>" />

											<div id="social-channel-<?php echo esc_attr($social_channel);?>-icon" class="social-channel-icon" style="display:none; ">
												<img src="<?php echo esc_url($social_channel_value['custom_icon'])?>" id="social-channel-<?php echo esc_attr($social_channel);?>-custom-icon-img"  width="38" height="38"/>
												<span class="social-channel-icon-close" data-slug="<?php echo esc_attr($social_channel);?>">x</span>
											</div>

											<input type="hidden" id="social-channel-<?php echo esc_attr($social_channel);?>-custom-icon" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][custom_icon]" value="<?php echo esc_url($social_channel_value['custom_icon'])?>" />
										</div>
										<div class="myStickyelements-custom-icon">
											<span>Or</span>
											
											<?php $fontawesome_icons = mystickyelements_fontawesome_icons();?>
											<select id="mystickyelements-<?php echo esc_attr($social_channel);?>-custom-icon" data-slug="<?php echo esc_attr($social_channel);?>" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][fontawesome_icon]" class="social-channel-fontawesome-icon">
												<option value=""><?php esc_html_e( 'Select FontAwesome Icon', 'mystickyelements');?></option>
												<?php foreach( $fontawesome_icons as $icons):
													$icon_html = '<i class="' . $icons . '"></i>';
												?>
													<option value="<?php echo $icons?>" <?php selected( $social_channel_value['fontawesome_icon'] , $icons)?>><?php echo $icons;?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<?php endif;?>
							<tr>
								<td>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Background Color', 'mystickyelements' );?></label>
										<input type="text" data-slug="<?php echo esc_attr($social_channel); ?>" id="social-<?php echo esc_attr($social_channel);?>-bg_color" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][bg_color]" class="mystickyelement-color" value="<?php echo esc_attr($social_channel_value['bg_color']);?>" />
									</div>
									<?php if ( isset($social_channels_list['icon_color']) && $social_channels_list['icon_color'] == 1) :?>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Icon Color', 'mystickyelements' );?></label>
										<input type="text" data-soical-icon="<?php echo esc_attr($social_channel); ?>" id="social-<?php echo esc_attr($social_channel);?>-icon_color" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_color]" class="mystickyelement-color" value="<?php echo esc_attr($social_channel_value['icon_color']);?>" />
									</div>
									<?php endif;?>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Icon Text', 'mystickyelements' );?></label>
										<input type="text" class="myStickyelements-icon-text-input" id="social-<?php echo esc_attr($social_channel);?>-icon_text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_text]" value="<?php echo esc_attr($social_channel_value['icon_text']);?>" data-icontext="<?php echo esc_attr($social_channel);?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
									</div>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Icon Text Size', 'mystickyelements' );?></label>
										<div class="px-wrap">
											<input type="number" class="myStickyelements-icon-text-size" id="social-<?php echo esc_attr($social_channel);?>-icon_text_size" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_text_size]" value="<?php echo esc_attr($social_channel_value['icon_text_size']);?>" min="0" data-icontextsize="<?php echo esc_attr($social_channel);?>" placeholder="<?php _e('Enter font size here...','mystickyelements');?>" />
											<span class="input-px">PX</span>
										</div>
									</div>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'On Hover Text', 'mystickyelements' );?></label>
										<input type="text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][hover_text]" value="<?php echo esc_attr($social_channel_value['hover_text']);?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
									</div>
									<?php if ( isset($social_channels_list['is_pre_set_message']) && $social_channels_list['is_pre_set_message'] == 1) :?>
										<div class="myStickyelements-setting-wrap-list">
											<label><?php _e( 'Pre Set Message', 'mystickyelements' );?></label>
											<input type="text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][pre_set_message]" value="<?php echo esc_attr($social_channel_value['pre_set_message']);?>" placeholder="<?php _e('Enter message here...','mystickyelements');?>" />
										</div>
									<?php endif;?>
									<?php if ( !isset($social_channels_list['custom_html']) && isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1) :?>
									<div class="myStickyelements-setting-wrap-list">
										<div id="checkboxes">
											<label>
												<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][open_newtab]" value="1" <?php if ( isset($social_channel_value['open_newtab']) && $social_channel_value['open_newtab'] == 1 ) { echo 'checked="checked"'; } ?>  />&nbsp;<?php _e( 'Open in a new tab', 'mystickyelements' );?>
											</label>
										</div>
									</div>
									<?php endif;?>
								</td>
							</tr>
						</table>
					</div>
					<span class="social-channel-close" data-slug="<?php echo $social_channel; ?>">X</span>
				</div>
				<?php

			}
			if ( isset($_POST['is_ajax']) && $_POST['is_ajax'] == true ) {
				wp_die();
			}
		}

		/*
		 * My Sticky Elements Contact Leads
		 *
		 */
		public function mystickyelements_admin_leads_page(){
			global $wpdb;

			$table_name = $wpdb->prefix . "mystickyelement_contact_lists";
			
			if ( isset($_POST['stickyelement-contatc-submit']) && !wp_verify_nonce( $_POST['stickyelement-contatc-submit'], 'stickyelement-contatc-submit' ) ) {

				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickyelements'). '</p></strong></div>';

			} else if ( isset($_POST['stickyelement-contatc-submit']) && wp_verify_nonce( $_POST['stickyelement-contatc-submit'], 'stickyelement-contatc-submit' )  ) {
				if ( isset($_POST['delete_message']) && !empty($_POST['delete_message'])) {
				
					$count = count($_POST['delete_message']);					
					foreach ( $_POST['delete_message'] as $key=>$ID) {	
						$delete = $wpdb->query("DELETE FROM $table_name WHERE ID = " . $ID);
					}
					echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__( $count . ' message deleted.','mystickyelements'). '</p></strong></div>';
				
				}
			}
			
			
			?>
			<div class="wrap mystickyelement-contact-wrap">
				<h2><?php _e( 'Contact Form Leads', 'mystickyelements' ); ?></h2>
				<p class="description">
					<strong><?php esc_html_e("Contact's data is saved locally do make backup or export before uninstalling plugin", 'mystickyelements');?></strong>
				</p>
				<div>
					<table id="mystickyelement_contact_tab">
						<tr>
							<td><strong><?php esc_html_e('Download & Export All Subscriber to CSV file:','mystickyelements' );?> </strong></td>
							<td><a href="<?php echo plugins_url('mystickyelements-contact-leads.php?download_file=mystickyelements_contact_leads.csv',__FILE__); ?>" class="wpappp_buton" id="wpappp_export_to_csv" value="Export to CSV" href="#"><?php esc_html_e('Download & Export to CSV', 'mystickyelements' );?></a></td>
							<td><strong><?php esc_html_e('Delete All Subscibers from Database:','mystickyelements');?> </strong></td>
							<td><input type="button" class="wpappp_buton" id="mystickyelement_delete_all_leads" value="<?php esc_attr_e('Delete All Data', 'mystickyelements' );?>" /></td>
						</tr>						
					</table>
					<input type="hidden" id="delete_nonce" name="delete_nonce" value="<?php echo wp_create_nonce("mysticky_elements_delete_nonce") ?>" />
				</div>

				<div>
					<form action="" method="post">
						<div class="tablenav top">
							<div class="alignleft actions bulkactions">
								<select name="action" id="bulk-action-selector-top">
								<option value="">Bulk Actions</option>
								<option value="delete_message">Delete</option>								
								</select>
								<input type="submit" id="doaction" class="button action" value="Apply">
								<?php wp_nonce_field( 'stickyelement-contatc-submit', 'stickyelement-contatc-submit' );  ?>
							</div>
						</div>
						<table border="1" class="responstable">
							<tr>
								<th style="width:1%"><?php esc_html_e( 'Bulk', 'mystickyelements' );?></th>
								<th>ID</th>
								<th><?php esc_html_e( 'Name', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Phone', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Email', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Option', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Message', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Date', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'URL', 'mystickyelements');?></th>
								<th style="width:11%"><?php esc_html_e( 'Delete', 'mystickyelements');?></th>
							</tr>
						<?php
						$result = $wpdb->get_results ( "SELECT * FROM ".$table_name ." ORDER BY ID DESC" );
						if($result){?>

								<?php foreach ( $result as $res )   { ?>
								<tr>
									<td><input id="cb-select-80" type="checkbox" name="delete_message[]" value="<?php echo esc_attr($res->ID);?>"></td>
									<td><?php echo $res->ID;?></td>
									<td><?php echo $res->contact_name ;?></td>
									<td><?php echo $res->contact_phone;?></td>
									<td><?php echo $res->contact_email;?></td>
									<td><?php echo $res->contact_option;?></td>
									<td><?php echo wpautop($res->contact_message);?></td>
									<td><?php echo ( isset($res->message_date) ) ? $res->message_date : '-' ;?></td>
									<td>
										<?php if ( $res->page_link) :?>
										<a href="<?php echo esc_url($res->page_link);?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
										<?php endif;?>
									</td>									
									<td>
										<input type="button" data-delete="<?php echo $res->ID;?>" class="mystickyelement-delete-entry" value="<?php esc_attr_e('Delete Record', 'mystickyelements');?>" />
									</td>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan="6" align="center">
									<p class="mystickyelement-no-contact"> <?php esc_html_e('No Contact Form Leads Found!','mystickyelements');?>
									</p>
								</td>
							</tr>
						<?php }	?>

						</table>
					</form>
				</div>
			</div>
			<?php
		}

		/*
		 * My Sticky Elements Create New Widget
		 *
		 */
		public function mystickyelements_admin_new_widget_page(){
			$upgarde_url = admin_url("admin.php?page=my-sticky-elements-upgrade");
			?>
			<div class="mystickyelement-new-widget-wrap">
				<h2><?php _e( 'Create another widget', 'mystickyelements' ); ?></h2>
				<div class="mystickyelement-new-widget-row">
					<div class="mystickyelement-new-widget-left">
						<img src="<?php echo MYSTICKYELEMENTS_URL; ?>images/new_widget_img.png" width="322" height="258" />
					</div>
					<div class="mystickyelement-new-widget-right">
						<p><strong>Create a new My Sticky Elements widget and show it on specific pages and categories of your website based on page targeting rules.</strong></p>
						<p><strong>What can you use it for?</strong></p>
						<ul>
							<li>If you have a multi-language website or WPML plugin installed, you can show different form and buttons based on URL (e.g. visitors to yourdomain.com/fr/* pages will get a form in French, and an option to send a WhatsApp message to a French number)</li>
							<li>You can show separate widgets for different products on your website (e.g. you can show the Facebook Messenger channel for products in the yourdomain.com/high-end/* category)</li>
							<li>Display different channels and form for your landing pages</li>
							<li>Show one widget on your support and contact pages, and a different widget on your sales pages.</li>
						</ul>
						<div class="upgrade-myStickyelements">
							<a href="<?php echo esc_url($upgarde_url); ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('UPGRADE NOW', 'mystickyelements' );?></a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function mystickyelement_delete_db_record(){
			global $wpdb;

			if ( isset($_POST['ID']) && $_POST['ID'] != '' && wp_verify_nonce($_POST['delete_nonce'], "mysticky_elements_delete_nonce") ) {
				$ID = sanitize_text_field($_POST['ID']);
				$table = $wpdb->prefix . 'mystickyelement_contact_lists';
				$ID = self::sanitize_options($ID, "sql");
				$delete = $wpdb->query("DELETE FROM $table WHERE ID = " . $ID);
			}

			if ( isset($_POST['all_leads']) && $_POST['all_leads'] == 1 && wp_verify_nonce($_POST['delete_nonce'], "mysticky_elements_delete_nonce")) {
				$table = $wpdb->prefix . 'mystickyelement_contact_lists';
				$delete = $wpdb->query("TRUNCATE TABLE $table");
			}
			wp_die();
		}
		
		public function myStickyelements_intro_popup_action() {
			if( !empty( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'myStickyelements_update_popup_status' ) ) {
				update_option( "mystickyelements_intro_popup", "hide" );
			}
			echo esc_attr("1");
			die;
		}
		
		public function mystickyelements_admin_send_message_to_owner() {
			$response = array();
			$response['status'] = 0;
			$response['error'] = 0;
			$response['errors'] = array();
			$response['message'] = "";
			$errorArray = [];
			$errorMessage = __("%s is required", "mystickyelements");
			$postData = $_POST;
			if(!isset($postData['textarea_text']) || trim($postData['textarea_text']) == "") {
				$error = array(
					"key"   => "textarea_text",
					"message" => __("Please enter your message","wcp")
				);
				$errorArray[] = $error;
			}
			if(!isset($postData['user_email']) || trim($postData['user_email']) == "") {
				$error = array(
					"key"   => "user_email",
					"message" => sprintf($errorMessage,__("Email","wcp"))
				);
				$errorArray[] = $error;
			} else if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)) {
				$error = array(
					'key' => "user_email",
					"message" => "Email is not valid"
				);
				$errorArray[] = $error;
			}
			if(empty($errorArray)) {
				if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
					$error = array(
						'key' => "nonce",
						"message" => "Your request is not valid"
					);
					$errorArray[] = $error;
				} else if(!wp_verify_nonce($_REQUEST['nonce'], "mystickyelements_send_message_to_owner")) {
					$error = array(
						'key' => "nonce",
						"message" => "Your request is not valid"
					);
					$errorArray[] = $error;
				}
			}
			if(empty($errorArray)) {
				global $current_user;
				$text_message = $postData['textarea_text'];
				$email = $postData['user_email'];
				$domain = site_url();
				$user_name = $current_user->first_name." ".$current_user->last_name;
				$subject = "My Sticky Elements request: ".$domain;
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$headers .= 'From: '.$user_name.' <'.$email.'>'.PHP_EOL ;
				$headers .= 'Reply-To: '.$user_name.' <'.$email.'>'.PHP_EOL ;
				$headers .= 'X-Mailer: PHP/' . phpversion();
				ob_start();
				?>
				<table border="0" cellspacing="0" cellpadding="5">
					<tr>
						<th>Domain</th>
						<td><?php echo $domain ?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $email ?></td>
					</tr>
					<tr>
						<th>Message</th>
						<td><?php echo nl2br($text_message) ?></td>
					</tr>
				</table>
				<?php
				$message = ob_get_clean();
				$to = "gal@premio.io";
				$status = wp_mail($to, $subject, $message, $headers);
				if($status) {
					$response['status'] = 1;
				} else {
					$response['status'] = 0;
					$response['message'] = "Not able to send mail";
				}
			} else {
				$response['error'] = 1;
				$response['errors'] = $errorArray;
			}
			echo json_encode($response);
			wp_die();
		}
		
		public function mystickyelements_deactivate() {
			global $pagenow;

			if ( 'plugins.php' !== $pagenow ) {
				return;
			}			

			include MYSTICKYELEMENTS_PATH . 'mystickyelements-deactivate-form.php';
		}
		
		public function mystickyelements_plugin_deactivate() {
			global $current_user;
			$postData = $_POST;
			$errorCounter = 0;
			$response = array();
			$response['status'] = 0;
			$response['message'] = "";
			$response['valid'] = 1;
			if(!isset($postData['reason']) || empty($postData['reason'])) {
				$errorCounter++;
				$response['message'] = "Please provide reason";
			} else if(!isset($postData['reason']) || empty($postData['reason'])) {
                $errorCounter++;
                $response['message'] = "Please provide reason";
            } else {
				$nonce = $postData['nonce'];
				if(!wp_verify_nonce($nonce, 'mystickyelements_deactivate_nonce')) {
					$response['message'] = __("Your request is not valid", "mystickyelements");
					$errorCounter++;
					$response['valid'] = 0;
				}
			}
			if($errorCounter == 0) {
				global $current_user;
				$plugin_info = get_plugin_data( MYSTICKYELEMENTS_PATH. 'mystickyelements.php');
				$postData = $_POST;
				$email = "none@none.none";

                if (isset($postData['email_id']) && !empty($postData['email_id']) && filter_var($postData['email_id'], FILTER_VALIDATE_EMAIL)) {
                    $email = $postData['email_id'];
                }
				$domain = site_url();
				$user_name = $current_user->first_name . " " . $current_user->last_name;
				$subject = "My Sticky Elements was removed from {$domain}";
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$headers .= 'From: ' . $user_name . ' <' . $email . '>' . PHP_EOL;
				$headers .= 'Reply-To: ' . $user_name . ' <' . $email . '>' . PHP_EOL;
				$headers .= 'X-Mailer: PHP/' . phpversion();
				ob_start();
				?>
				<table border="0" cellspacing="0" cellpadding="5">
					<tr>
						<th>Plugin</th>
						<td>My Sticky Elements</td>
					</tr>
					<tr>
						<th>Plugin Version</th>
						<td><?php echo $plugin_info['Version']; ?></td>
					</tr>
					<tr>
						<th>Domain</th>
						<td><?php echo $domain ?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $email ?></td>
					</tr>
					<tr>
						<th>Reason</th>
						<td><?php echo nl2br($postData['reason']) ?></td>
					</tr>
					<tr>
						<th>WordPress Version</th>
						<td><?php echo get_bloginfo('version') ?></td>
					</tr>
					<tr>
						<th>PHP Version</th>
						<td><?php echo PHP_VERSION ?></td>
					</tr>
				</table>
				<?php
				$content = ob_get_clean();
				$to = "gal@premio.io";
				wp_mail($to, $subject, $content, $headers);
				$response['status'] = 1;
			}
			echo json_encode($response);
			wp_die();
		}
		
		/*
		 * clear cache when any option is updated 
		 *
		 */	
		public function mystickyelements_clear_all_caches(){
			
			try {
				global $wp_fastest_cache;
				// if W3 Total Cache is being used, clear the cache
				if (function_exists('w3tc_flush_all')) {
					w3tc_flush_all();                
				} 
				/* if WP Super Cache is being used, clear the cache */
				if (function_exists('wp_cache_clean_cache')) {
					global $file_prefix, $supercachedir;
					if (empty($supercachedir) && function_exists('get_supercache_dir')) {
						$supercachedir = get_supercache_dir();
					}
					wp_cache_clean_cache($file_prefix);
				} 
				
				if (class_exists('WpeCommon')) {
					//be extra careful, just in case 3rd party changes things on us
					if (method_exists('WpeCommon', 'purge_memcached')) {
						//WpeCommon::purge_memcached();
					}
					if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
						//WpeCommon::clear_maxcdn_cache();
					}
					if (method_exists('WpeCommon', 'purge_varnish_cache')) {
						//WpeCommon::purge_varnish_cache();
					}
				}
				
				if (method_exists('WpFastestCache', 'deleteCache') && !empty($wp_fastest_cache)) {
					$wp_fastest_cache->deleteCache();
				} 
				if (function_exists('rocket_clean_domain')) {
					rocket_clean_domain();
					// Preload cache.
					if (function_exists('run_rocket_sitemap_preload')) {
						run_rocket_sitemap_preload();
					}
				} 
				
				if (class_exists("autoptimizeCache") && method_exists("autoptimizeCache", "clearall")) {
					autoptimizeCache::clearall();
				}
				
				if (class_exists("LiteSpeed_Cache_API") && method_exists("autoptimizeCache", "purge_all")) {
					LiteSpeed_Cache_API::purge_all();
				}
				
				if ( class_exists( '\Hummingbird\Core\Utils' ) ) {
	
					$modules   = \Hummingbird\Core\Utils::get_active_cache_modules();					
					foreach ( $modules as $module => $name ) {
						$mod = \Hummingbird\Core\Utils::get_module( $module );

						if ( $mod->is_active() ) {
							if ( 'minify' === $module ) {
								$mod->clear_files();
							} else {
								$mod->clear_cache();
							}
						}
					}	
				}

			} catch (Exception $e) {
				return 1;
			}
		}
	}
}


if( is_admin() ) {
    $my_settings_page = new MyStickyElementsPage_pro();
    include_once "class-review-box.php";
}