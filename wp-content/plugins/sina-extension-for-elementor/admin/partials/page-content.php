<div class="sina-ext-banner" style="background-image: url(<?php echo esc_url( SINA_EXT_URL .'admin/assets/img/sina-extension-banner.jpg' ); ?>);">
	<h1><?php _e( 'Sina Extension', 'sina-ext' ); ?></h1>
</div>

<div class="sina-ext-wrap">
	<form action="options.php" method="POST">
		<?php settings_errors(); ?>

		<div class="sina-ext-my sina-ext-pt sina-ext-pb">
		<?php
			$get_widgets = get_option( 'sina_widgets' );
			$set_widgets = SINA_WIDGETS;
			$go_pro = 'sina-ext-pro';
			if ( defined('SINA_EXT_PRO_WIDGETS')) {
				$set_widgets = array_merge(SINA_WIDGETS, SINA_EXT_PRO_WIDGETS);
				$go_pro = '';
			}
			submit_button(null, 'primary', 'submit', false, ['id' => 'submit-top']);
			if ( '' == $go_pro ) :
				printf('<a class="button sina-doc-btn" href="%1$s" target="_blank">%2$s</a>', 'https://sinaextra.com/docs/sina-extension/', __( 'Documentation', 'sina-ext' ) );
			endif;
		?>
		</div>

		<div class="sina-ext-tabs-wrap">
			<div class="sina-ext-btns">
				<a class="active" href="#sina-ext-settings"><?php _e( 'Settings', 'sina-ext' ); ?></a>
				<a href="#sina-ext-widgets"><?php _e( 'Widgets', 'sina-ext' ); ?></a>
				<a href="#sina-ext-extenders"><?php _e( 'Extenders', 'sina-ext' ); ?></a>
			</div>

			<div class="sina-ext-tab-content show sina-ext-pt sina-ext-pb" id="sina-ext-settings">
				<h2 class="sina-ext-tab-title"><?php _e( 'Settings', 'sina-ext' ); ?></h2>
				<p class="sina-ext-pb">
					<?php _e( 'Set your expected options.', 'sina-ext' ); ?>
				</p>

				<?php do_action( 'sina_ext_before_api_settings'); ?>

				<h2 class="sina-ext-pt"><?php _e( 'API Settings', 'sina-ext' ); ?></h2>
				<div class="sina-ext-pb">
					<?php do_settings_sections( 'sina_ext_settings' ); ?>
				</div>

				<?php do_action( 'sina_ext_before_templates_settings'); ?>

				<div class="sina-ext-options sina-ext-pt">
					<h2><?php _e( 'Template Settings', 'sina-ext' ); ?></h2>
					<p class="sina-ext-pb">
						<?php _e( 'You can use <strong><i>SINA TEMPLATES</i></strong> on your site. Enjoy!', 'sina-ext' ); ?>
					</p>

					<div class="sina-ext-pb">
						<?php do_settings_sections( 'sina_ext_templates' ); ?>
					</div>
				</div>

				<div class="sina-ext-options sina-ext-pb">
					<h2><?php _e( 'Rollback to Previous Version', 'sina-ext' ); ?></h2>
					<p>
						<?php _e( 'Experiencing an issue with this version? You can rollback the previous version.', 'sina-ext' ); ?>
					</p>
					<?php
					printf( '<a href="%1$s" class="sina-ext-rollback-btn button elementor-button-spinner elementor-rollback-button">%2$s</a>',
						wp_nonce_url( admin_url( 'admin-post.php?action=sina_ext_rollback' ), 'sina_ext_rollback' ),
						sprintf(
							__( 'Reinstall v%s', 'elementor' ),
							SINA_EXT_PREVIOUS_VERSION
						)
					);
					?>
					<p style="color: #e00;">
						<?php _e( 'Warning: Please backup your database before making the rollback.', 'sina-ext' ); ?>
					</p>
				</div>
			</div>

			<div class="sina-ext-tab-content sina-ext-pt sina-ext-pb" id="sina-ext-widgets">
				<h2 class="sina-ext-tab-title"><?php _e( 'Widgets', 'sina-ext' ); ?></h2>
				<p class="sina-ext-pb">
					<?php _e( 'You can disable widget(s) if you would like to not using on your site.', 'sina-ext' ); ?>
				</p>

				<div class="sina-ext-options">
					<?php
						foreach ($set_widgets as $cat => $data) {
							$sina_pro = ('pro' == $cat) ? $go_pro : '';
							$checked = isset($get_widgets[$cat]) ? 'checked' : '';
							?>
							<div class="sina-ext-pb sina-toggle-all-<?php echo esc_attr($cat); ?>">
								<div class='sina-ext-pb sina-ext-my'>
									<div class="sina-toggle-section <?php echo esc_attr($sina_pro); ?> sina-ext-toggle" data-cat="<?php echo esc_attr($cat); ?>">
										<?php printf('<input type="checkbox" id="sina_widgets[%s]" %s value="1">', $cat, $checked); ?>
										<?php printf('<label for="sina_widgets[%1$s]"><div class="sina-ext-label">%2$s</div><div class="sina-ext-toggle-btn"> <div></div></div></label>', $cat, __( ucfirst($cat), 'sina-ext' )); ?>
									</div>
								</div>
							<?php
							do_settings_sections( 'sina_widgets_'.$cat );
							echo '</div>';
						}
						settings_fields( 'sina_settings_group' );
					?>
				</div>
			</div>

			<div class="sina-ext-tab-content sina-ext-pt sina-ext-pb" id="sina-ext-extenders">
				<h2 class="sina-ext-tab-title"><?php _e( 'Extenders', 'sina-ext' ); ?></h2>
				<p class="sina-ext-pb">
					<?php _e( 'You can disable extender(s) if you would like to not using on your site.', 'sina-ext' ); ?>
				</p>

				<div class="sina-ext-options">
					<?php
					$get_extenders = get_option( 'sina_extenders' );
					$checked = empty($get_extenders) ? '' : 'checked';
					?>
					<div class='sina-ext-pb sina-ext-my'>
						<div class="sina-toggle-section sina-ext-toggle <?php echo esc_attr( $go_pro ); ?>" data-cat="extenders">
							<?php printf('<input type="checkbox" id="sina_extenders" %s value="1">', $checked); ?>
							<?php printf('<label for="sina_extenders"><div class="sina-ext-label">%1$s</div><div class="sina-ext-toggle-btn"> <div></div></div></label>', __( 'Extenders', 'sina-ext' )); ?>
						</div>
					</div>
					<div class="sina-ext-pb sina-toggle-all-extenders">
						<?php do_settings_sections( 'sina_extenders' ); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="sina-ext-pb">
		<?php
			submit_button(null, 'primary', 'submit', false, ['id' => 'submit-bottom']);
			if ( '' == $go_pro ) :
				printf('<a class="button sina-doc-btn" href="%1$s" target="_blank">%2$s</a>', 'https://sinaextra.com/docs/sina-extension/', __( 'Documentation', 'sina-ext' ) );
			endif;
		?>
		</div>
	</form>

	<p>
		<?php _e( 'Found any issue or need help?', 'sina-ext' ); ?>
		<a href="https://sinaextra.com/support/" target="_blank"><?php _e( 'Open Ticket', 'sina-ext' ); ?></a>
	</p>

	<div class="sina-ext-options">
	    <p><?php _e( 'Did you like', 'sina-ext' ); ?> <strong><i><?php _e( 'Sina Extension', 'sina-ext' ); ?></i></strong> <?php _e( 'Plugin?', 'sina-ext' ); ?> <a href="https://wordpress.org/support/plugin/sina-extension-for-elementor/reviews/#new-post" target="_blank"><?php _e( 'Leave a Review', 'sina-ext' ); ?></a></p>
	</div>
</div>