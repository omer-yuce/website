<?php
    namespace MasterAddons\Inc\Classes;
    use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 1/7/20
	 */

	class JLTMA_Ajax_Queries{

        private static $instance = null;

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }


		public function __construct() {
			add_action( 'wp_ajax_ma_el_restrict_content', array( $this, 'ma_el_restrict_content' ) );
            add_action( 'wp_ajax_nopriv_ma_el_restrict_content', array( $this, 'ma_el_restrict_content' ) );

            add_action('wp_ajax_jltma_domain_checker', array( $this,'jltma_domain_checker' ));
            add_action('wp_ajax_nopriv_jltma_domain_checker', array( $this,'jltma_domain_checker' ));
        }


		function ma_el_restrict_content() {

            parse_str( $_POST['fields'], $output );

            if ( !empty($_POST['fields'] )) {

                // Math Captcha
                if($_POST['restrict_type'] == 'math_captcha'){
                    if( $output['ma_el_rc_answer'] !== $output['ma_el_rc_answer_hd']){
                        die( json_encode( array(
                            "result" => "validate",
                            "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['error_message'] )
                        ) ) );
                    }
                }

                // Password Protecion
                if($_POST['restrict_type'] == 'password'){
                    if( $_POST['content_pass'] !== $output['ma_el_restrict_content_pass']){
                        die( json_encode( array(
                            "result" => "validate",
                            "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['error_message'] )
                        ) ) );
                    }
                }


                // Age Restrict
                if($_POST['restrict_type'] == 'age_restrict'){

                    $min_age = $_POST['restrict_age']['min_age'];

                    // Enter Age
                    if($_POST['restrict_age']['age_type'] == "enter_age"){

                        if( ( $output['ma_el_ra_year'] =="" ) || ($output['ma_el_ra_year'] < $min_age ) ) { 
                            die( json_encode( array(
                                "result" => "validate" ,
                                "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['error_message'] )
                            ) ) );
                        }
                    }

                    if($_POST['restrict_age']['age_type'] == "age_checkbox"){
                        // Checkbox Age Restriction
                        if( $output['ma_el_rc_check'] !="on"){
                            die( json_encode( array(
                                "result" => "validate",
                                "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['error_message'] )
                            ) ) );
                        }
                    }

                    if($_POST['restrict_age']['age_type'] == "input_age"){

                        if( $output['ma_el_ra_day'] =="" || $output['ma_el_ra_month'] =="" || $output['ma_el_ra_year'] =="" ) {
                            die( json_encode( array(
                                "result" => "validate",
                                "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['restrict_age']['empty_bday'] )
                            ) ) );
                        } else if( !checkdate( $output['ma_el_ra_month'], $output['ma_el_ra_day'], $output['ma_el_ra_year'] ) ) {
                            die( json_encode( array(
                                "result" => "validate",
                                "output" => sprintf( __( '%1$s', MELA_TD ), $_POST['restrict_age']['non_exist_bday'] )
                            ) ) );
                        } else{
                            $birthday = sprintf( "%04d-%02d-%02d", $output['ma_el_ra_year'], $output['ma_el_ra_month'], $output['ma_el_ra_day'] );
                            $today = new \DateTime();
                            $min = $today->modify( "-{$min_age} year" )->format( "Y-m-d" );

                            // Check if after the minimum age date
                            if( $birthday > $min ) {
                                die( json_encode( array(
                                    "result" => "validate",
                                    "output" => sprintf( __( '%1$s , minimum age %2$s', MELA_TD ), $min_age, $_POST['error_message'] )
                                ) ) );
                            }
                        }


                    }

                }

                die( json_encode( array(
                    "result" => "success",
                    "output" => ""
                ) ) );

            }// end if fields


		}



    public function jltma_domain_checker(){

            require_once MELA_PLUGIN_PATH . '/inc/classes/class-jltma-domain-checker.php';

            $succes_msg = $_POST['succes_msg'];
            $error_msg = $_POST['error_msg'];
            $not_found = $_POST['not_found'];
            $not_entered_domain = $_POST['not_entered_domain'];


            // check security field
            if( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ma-el-domain-checker' ) ) {
                wp_send_json_error(  __( 'Security Error.', MELA_TD ) );
            }


            if( ! isset( $_POST['domain'] ) ||  $_POST['domain'] == "" ){
                wp_send_json_error( $not_entered_domain );
            }

            $domain = str_replace( array( 'www.', 'http://' ), NULL, $_POST['domain'] );
            $split  = explode('.', $domain);
            if( count( $split ) == 1 ) {
                $domain = $domain . ".com";
            }
            $domain = preg_replace("/[^-a-zA-Z0-9.]+/", "", $domain);

            if( strlen( $domain ) > 0 ){


                // Class responsible for checking if a domain is registered
                $domain_check = new Master_Addons_Domain_Checker();
                $available    = $domain_check->is_available( $domain );

                switch ( $available ) {
                    case '1':
                        wp_send_json_success( sprintf( $succes_msg, '<strong>' .  $domain . '</strong> ' ) );
                        break;

                    case '0':
                        wp_send_json_error( sprintf( $error_msg, '<strong>' .  $domain . '</strong>' ) );
                        break;

                    default:
                        wp_send_json_error( $not_found );
                }

            }

            wp_send_json_error( __( 'Please enter a valid Domain name.', MELA_TD ) );
        }



    }

    // JLTMA_Ajax_Queries::get_instance();