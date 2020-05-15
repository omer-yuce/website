<div class="wp-tab-panel" id="free-themes" style="display: none;">
    <div class="master_addons_features">
        <div class="master_addons_feature">
            <h2>
				<?php esc_html_e( 'Recommended Free WordPress Themes for Master Addons', MELA_TD ); ?>
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
							<?php _e( 'Osaka Lite', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

                            <?php
                                $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=osaka-light' ), 'install-theme_osaka-light' );
                                $button_text = esc_html__( 'Install', MELA_TD );
                                $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Osaka Light" data-slug="osaka-light" aria-label="Install Osaka Light">' . $button_text . '</a>';
                                printf($button);
                            ?>


                            <a class="button"
                               href="<?php echo esc_url( 'http://wp.jeweltheme.com/osaka-pro/' ); ?>"
                               target="_blank">
		                        <?php _e( 'View Demo', MELA_TD ); ?>
                            </a>
                        </div>

                    </div>
                </div>



                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/ezra.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Polmo Lite', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=ezra' ), 'install-theme_ezra' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Ezra" data-slug="ezra" aria-label="Install Ezra">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button"
                               href="<?php echo esc_url( 'https://prowptheme.com/themes/ezra/' ); ?>"
                               target="_blank">
								<?php _e( 'View Demo', MELA_TD ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/polmo-lite.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Polmo Lite', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=polmo-lite' ), 'install-theme_polmo-lite' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Polmo Lite" data-slug="polmo-lite" aria-label="Install Polmo Lite">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button"
                               href="<?php echo esc_url( 'https://polmo-pro.jeweltheme.com/' ); ?>"
                               target="_blank">
								<?php _e( 'View Demo', MELA_TD ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08//jewel-blog.jpg" alt="" />
                    </div>

                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Jewel Blog', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=jewel-blog' ), 'install-theme_jewel-blog' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Jewel Blog" data-slug="jewel-blog" aria-label="Install Jewel Blog">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button" href="<?php echo esc_url( 'http://wordpress.jeweltheme.com/jewel-blog/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>



                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/videostories.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'VideoStories', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=videostories' ), 'install-theme_videostories' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Videostories" data-slug="videostories" aria-label="Install Videostories">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button" href="<?php echo esc_url( 'https://videostories.jeweltheme.com/' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/reader-lite.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Reader Lite', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">
	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=reader' ), 'install-theme_reader' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Reader" data-slug="reader" aria-label="Install Reader">' . $button_text . '</a>';
		                        printf($button);
	                        ?>
                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/reader/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/videofy.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Videofy', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">
	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=videofy' ), 'install-theme_videofy' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Videofy" data-slug="videofy" aria-label="Install Videofy">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/videofy/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/revideo.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Videofire', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">

	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=revideo' ), 'install-theme_revideo' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Revideo" data-slug="revideo" aria-label="Install Revideo">' . $button_text . '</a>';
		                        printf($button);
	                        ?>

                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/revideo/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>



                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/videofire.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Videofire', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">
	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=videofire' ), 'install-theme_videofire' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Videofire" data-slug="videofire" aria-label="Install Videofire">' . $button_text . '</a>';
		                        printf($button);
	                        ?>
                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/videofire/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>



                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/videomag.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Videomag', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">
	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=videomag' ), 'install-theme_videomag' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Videomag" data-slug="videomag" aria-label="Install Videomag">' . $button_text . '</a>';
		                        printf($button);
	                        ?>
                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/videomag/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="theme">
                    <div class="theme-screenshot">
                        <img src="https://master-addons.com/wp-content/uploads/2019/08/videofire.png" alt="" />
                    </div>
                    <div class="theme-id-container">
                        <h2 class="theme-name">
							<?php _e( 'Ultimate AMP', MELA_TD ); ?>
                        </h2>
                        <div class="theme-actions">
	                        <?php
		                        $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=ultimate-amp' ), 'install-theme_ultimate-amp' );
		                        $button_text = esc_html__( 'Install', MELA_TD );
		                        $button = '<a href="' . $activation_url . '" class="button button-primary theme-install" data-name="Ultimate Amp" data-slug="ultimate-amp" aria-label="Install Ultimate Amp">' . $button_text . '</a>';
		                        printf($button);
	                        ?>
                            <a class="button" href="<?php echo esc_url( 'https://demo.prowptheme.com/ultimate-amp/' ); ?>" target="_blank">
								<?php _e( 'View Demo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

