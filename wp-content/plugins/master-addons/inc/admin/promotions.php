<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/4/19
	 */

	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!



	if( !class_exists('Master_Addons_Promotions') ) {
		class Master_Addons_Promotions {

			private static $instance = null;

			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;
				}

				return self::$instance;
			}


			public function __construct() {

				/* Admin notice for asking ratings and Other Notices */
				global $pagenow;
				if ( ( 'admin.php' === $pagenow ) && ( 'master-addons-settings' === $_GET['page'] ) ) {
					add_action( 'admin_notices', array( $this, 'ma_el_promotional_offer' ) );
					add_action( 'admin_notices' , array( $this, 'ma_el_review_notice_message' ) );
                }

				add_action( 'wp_ajax_ma_el_promotional_offer_notice', array( $this, 'ma_el_dismiss_offer_notice' ));
				add_action( 'wp_ajax_ma_el_review_notice', array( $this, 'ma_el_review_notice' ) );

			}



			public function ma_el_promotional_offer(){
				// Show only to Admins
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				// 2019-12-30 23:59:00
				 if ( time() > 1577728755 ) {
				 	return;
				 }

				// check if it has already been dismissed
				 $hide_notice = get_option( 'ma_el_promotional_offer_notice', 'no' );

				 if ( 'hide' == $hide_notice ) {
				 	return;
				 }

//				$offer_msg  = __( '<h2><span class="dashicons dashicons-awards"></span> Jewel Theme\'s 6th Birthday Offer</h2>', MELA_TD );
//				$offer_msg = __( '<p><strong class="highlight-text" style="font-size: 18px">Get Master Addons Pro for Lifetime only $29 !!!</strong><br>Save money on Black Friday and Cyber Monday Deals for lifetime only $29. Use Coupon Code: <strong>BFLIFETIME2019</strong><br> Offer ending soon!</p>', MELA_TD );

				$offer_msg = __( '<p>
                            <strong class="highlight-text" style="font-size: 18px">
                                <span class="dashicons dashicons-awards"></span>
                                    Jewel Theme\'s 5th Birthday Offer
                                <span class="dashicons dashicons-awards"></span>
                            </strong>    
                            <br>Flat 50% Discount on all Plans for <strong>Jewel Theme\'s</strong> 5th Anniversary. Use Coupon 
                            Code:
                             <strong>HBFIVE50</strong><br> Limited Time Offer!</p>', MELA_TD );

				?>
                <div id="master-addons-promotional-offer-notice" class="master-addons-promotional-offer-notice
                is-dismissible">
                    <table>
                        <tbody>
                        <tr>
                            <td class="image-container">
                                <img src="https://ps.w.org/master-addons/assets/icon-256x256.png" alt="">
                            </td>
                            <td class="message-container">
								<?php echo $offer_msg; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <span class="dashicons dashicons-megaphone"></span>
                    <a href="http://bit.ly/3571fUr" class="button button-primary promo-btn" target="_blank"><?php _e(
                            'Get 50% Discount', MELA_TD ); ?></a>
                    <a href="#" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </a>
                </div><!-- #master-addons-promotional-offer-notice -->

                <style>
                    #master-addons-promotional-offer-notice {
                        padding: 15px 15px 15px 0;
                        background-color: #fff;
                        border-radius: 3px;
                        margin: 20px 20px 20px 0;
                        border-left: 4px solid transparent;
                        position: relative;
                    }

                    /*#master-addons-promotional-offer-notice .notice-dismiss:before{*/
                        /*display: none;*/
                    /*}*/
                    .wrap > #master-addons-promotional-offer-notice {
                        opacity: 1;
                    }

                    #master-addons-promotional-offer-notice table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #master-addons-promotional-offer-notice table td {
                        padding: 0;
                    }

                    #master-addons-promotional-offer-notice table td.image-container {
                        /*background-color: #ebf0f4;*/
                        vertical-align: middle;
                        width: 95px;
                    }

                    #master-addons-promotional-offer-notice img {
                        max-width: 100%;
                        max-height: 100px;
                        vertical-align: middle;
                    }

                    #master-addons-promotional-offer-notice table td.message-container {
                        padding: 0 10px;
                    }

                    #master-addons-promotional-offer-notice h2{
                        color: rgba(250, 250, 250, 0.8);
                        margin-bottom: 10px;
                        font-weight: normal;
                        margin: 16px 0 14px;
                        -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    }

                    #master-addons-promotional-offer-notice h2 span {
                        position: relative;
                        top: 0;
                    }

                    #master-addons-promotional-offer-notice p{
                        color: #444;
                        font-size: 14px;
                        margin-bottom: 10px;
                        -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    }

                    #master-addons-promotional-offer-notice p strong.highlight-text{
                        color: #444;
                    }

                    #master-addons-promotional-offer-notice p a {
                        color: #fafafa;
                    }
                    #master-addons-promotional-offer-notice .notice-dismiss{
                        position: absolute;
                        bottom:0;
                        top:inherit;
                    }

                    /*#master-addons-promotional-offer-notice .notice-dismiss:before {*/
                        /*color: #fff;*/
                    /*}*/

                    #master-addons-promotional-offer-notice span.dashicons-megaphone {
                        position: absolute;
                        bottom: 46px;
                        right: 248px;
                        color: rgba(253, 253, 253, 0.29);
                        font-size: 96px;
                        transform: rotate(-21deg);
                    }

                    #master-addons-promotional-offer-notice a.promo-btn{
                        background: #149269;
                        border-color: #149269 #149269 #149269;
                        box-shadow: 0 1px 0 #149269;
                        /*color: #45E2D0;*/
                        text-decoration: none;
                        text-shadow: none;
                        position: absolute;
                        top: 30px;
                        right: 26px;
                        height: 40px;
                        line-height: 40px;
                        width: 130px;
                        text-align: center;
                        font-weight: 600;
                    }

                </style>

                <script type='text/javascript'>
                    jQuery('body').on('click', '#master-addons-promotional-offer-notice .notice-dismiss', function(e) {
                        e.preventDefault();

                        jQuery("#master-addons-promotional-offer-notice").hide();

                        wp.ajax.post('ma_el_promotional_offer_notice', {
                            dismissed: true
                        });
                    });
                </script>
				<?php

			}


			public function ma_el_review_notice_message(){

			    // Show only to Admins
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				$dismiss_notice  = get_option( 'ma_el_review_notice_dismiss', 'no' );
				$activation_time = get_option( 'ma_el_installed' );
				$total_entries   = 1000;

				// check if it has already been dismissed
				// and don't show notice in 15 days of installation, 1296000 = 15 Days in seconds
				if ( 'yes' == $dismiss_notice ) {
					return;
				}

				if ( (time() - $activation_time < 1296000) && $total_entries < 50 ) {
					return;
				}

				?>
                <div id="master-addons-review-notice" class="master-addons-review-notice">
                    <div class="master-addons-review-thumbnail">
                        <img src="https://ps.w.org/master-addons/assets/icon-256x256.png" alt="">
                    </div>
                    <div class="master-addons-review-text">
						<?php if( $total_entries >= 50 ) : ?>
                            <h3><?php _e( 'Enjoying <strong>Master Addons</strong>?', MELA_TD ) ?></h3>
                            <p><?php _e( 'Seems like you are enjoying <strong>Master Addons</strong>. Would you please show us a little Love by rating us in the <a href="https://wordpress.org/support/plugin/master-addons/reviews/#postform" target="_blank"><strong>WordPress.org</strong></a>?', MELA_TD ) ?></p>
						<?php else: ?>
                            <h3><?php _e( 'Enjoying <strong>Master Addons</strong>?', MELA_TD ) ?></h3>
                            <p><?php _e( 'Hope that you had a neat and snappy experience with the tool. Would you 
							please show us a little love by rating us in the <a 
							href="https://wordpress.org/support/plugin/master-addons/reviews/#postform" target="_blank"><strong>WordPress.org</strong></a>?', MELA_TD ) ?></p>
						<?php endif; ?>

                        <ul class="master-addons-review-ul">
                            <li><a href="https://wordpress.org/support/plugin/master-addons/reviews/#postform"
                                   target="_blank"><span class="dashicons dashicons-external"></span><?php _e( 'Sure! I\'d love to!', MELA_TD ) ?></a></li>
                            <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-smiley"></span><?php _e( 'I\'ve already left a review', MELA_TD ) ?></a></li>
                            <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Never show again', MELA_TD ) ?></a></li>
                        </ul>
                    </div>
                </div>

                <style type="text/css">
                    #master-addons-review-notice .notice-dismiss{
                        padding: 0 0 0 26px;
                    }

                    #master-addons-review-notice .notice-dismiss:before{
                        display: none;
                    }

                    #master-addons-review-notice.master-addons-review-notice {
                        padding: 15px 15px 15px 0;
                        background-color: #fff;
                        border-radius: 3px;
                        margin: 20px 20px 20px 0;
                        border-left: 4px solid transparent;
                    }

                    #master-addons-review-notice .master-addons-review-thumbnail {
                        width: 114px;
                        float: left;
                        line-height: 80px;
                        text-align: center;
                        border-right: 4px solid transparent;
                    }

                    #master-addons-review-notice .master-addons-review-thumbnail img {
                        width: 60px;
                        vertical-align: middle;
                    }

                    #master-addons-review-notice .master-addons-review-text {
                        overflow: hidden;
                    }

                    #master-addons-review-notice .master-addons-review-text h3 {
                        font-size: 24px;
                        margin: 0 0 5px;
                        font-weight: 400;
                        line-height: 1.3;
                    }

                    #master-addons-review-notice .master-addons-review-text p {
                        font-size: 13px;
                        margin: 0 0 5px;
                    }

                    #master-addons-review-notice .master-addons-review-ul {
                        margin: 0;
                        padding: 0;
                    }

                    #master-addons-review-notice .master-addons-review-ul li {
                        display: inline-block;
                        margin-right: 15px;
                    }

                    #master-addons-review-notice .master-addons-review-ul li a {
                        display: inline-block;
                        color: #4b00e7;
                        text-decoration: none;
                        padding-left: 26px;
                        position: relative;
                    }

                    #master-addons-review-notice .master-addons-review-ul li a span {
                        position: absolute;
                        left: 0;
                        top: -2px;
                    }
                </style>
                <script type='text/javascript'>
                    jQuery('body').on('click', '#master-addons-review-notice .notice-dismiss', function(e) {
                        e.preventDefault();
                        jQuery("#master-addons-review-notice").hide();

                        wp.ajax.post('ma_el_review_notice_dismiss', {
                            dismissed: true
                        });
                    });
                </script>
				<?php
			}


			public function ma_el_dismiss_offer_notice(){
				if ( ! empty( $_POST['dismissed'] ) ) {
					$offer_key = 'ma_el_promotional_offer_notice';
					update_option( $offer_key, 'hide' );
				}
			}


			public function ma_el_review_notice(){
				if ( ! empty( $_POST['dismissed'] ) ) {
					update_option( 'ma_el_review_notice_dismiss', 'yes' );
				}
			}


		}

		Master_Addons_Promotions::get_instance();
	}