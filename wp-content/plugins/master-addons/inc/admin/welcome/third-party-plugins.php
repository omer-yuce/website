<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/5/19
	 */
	?>


	<div class="wp-tab-panel" id="ma_el_third_party_plugins">
		<h1 class="black textcenter main_heading">
			<?php _e( 'Welcome to Master Addons for Elementor', MELA_TD ); ?>
		</h1>

		<h3 class="black textcenter sub-heading">
			<?php _e( '20+ Stunning Designed Elementor Addon Collections !', MELA_TD ); ?>
		</h3>

		<div class="parent">

			<div class="left_column">
				<div class="left_block">
					<p>
						<?php echo sprintf( '<strong>Master Addons</strong> will bring life to your Website. Turn On/Off any elements. We are working every day on its Development and regularly Updating Master Addons.', MELA_TD ); ?>
					</p>
					<br>

					<h3><?php _e( 'Master Addons Demos:', MELA_TD ); ?></h3>

					<?php require MELA_PLUGIN_PATH . '/inc/admin/welcome/demos.php'; ?>

				</div>
			</div>

			<div class="right_column">
				<img class="tab-banner" src="<?php echo MELA_PLUGIN_URL .'/assets/images/banner-image.png';?>" alt="<?php echo MELA;?> Banner Image">
			</div>
		</div>


	</div>

