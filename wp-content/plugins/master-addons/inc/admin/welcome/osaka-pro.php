<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 11/17/19
	 */
	?>
<div class="wp-tab-panel" id="free-themes" style="display: none;">
    <div class="master_addons_features">
        <div class="master_addons_feature">
            <h2>
				<?php esc_html_e( 'Download Osaka Pro Theme', MELA_TD ); ?>
            </h2>
            <p class="textcenter">
                <?php echo sprintf( 'These themes are guaranteed to work seamlessly with <strong>Master Addons</strong> for Elementor Page Builder.', MELA_TD ); ?>
            </p>
        </div>
    </div>



	<?php
		include_once  ABSPATH . 'wp-admin/includes/theme-install.php'; //for themes_api..

		if ( ! current_user_can('install_themes') ){
			return;
		}
	?>


    <div class="featured-themes">
        <div class="theme-browser">
            <div class="themes wp-clearfix">


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/osaka-light.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Osaka Pro', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

                            <?php
                                $button_text = esc_html__( 'Download', MELA_TD );
                                $button = '<a href="' . esc_url('https://jeweltheme.com/wp-content/uploads/2014/11/osaka-pro.zip') . '" class="button button-primary theme-install" data-name="Osaka Pro" data-slug="osaka-light" aria-label="Install Osaka Pro">' . $button_text . '</a>';
                                printf($button);
                            ?>


                            <a class="button"
                               href="<?php echo esc_url( 'https://wp.jeweltheme.com/osaka-pro' ); ?>"
                               target="_blank">
		                        <?php _e( 'View Demo', MELA_TD ); ?>
                            </a>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

