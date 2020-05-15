<?php
	/*
	 * Master Addons : Welcome Screen by Jewel Theme
	 */
?>

<div class="master_addons">
	<div class="wrappper about-wrap">

        <div class="intro_wrapper">

            <header class="header">
				<a class="ma_el_logo" href="https://wordpress.org/plugins/master-addons" target="_blank">
                    <div class="wp-badge welcome__logo ma_logo"></div>
				</a>
				
                <h1 class="ma_title">
			        <?php printf( __( '%s <small>v %s</small>', MELA_TD ), MELA, MELA_VERSION ); ?>
                </h1>
                <div class="about-text">
			        <?php printf( __( "Ultimate and Essential Addons for Elementor Page Builder.", MELA ,
				        MELA_TD ),
				        MELA_VERSION ); ?>
                </div>

            </header>


            <div class="waveWrapper waveAnimation">
                <div class="waveWrapperInner bgTop">
                    <div class="wave waveTop" style="background-image: url('<?php echo MELA_IMAGE_DIR. "wave-top.png";?>')"></div>
                </div>
                <div class="waveWrapperInner bgMiddle">
                    <div class="wave waveMiddle" style="background-image: url('<?php echo MELA_IMAGE_DIR. "wave-mid.png";?>')"></div>
                </div>
                <div class="waveWrapperInner bgBottom">
                    <div class="wave waveBottom" style="background-image: url('<?php echo MELA_IMAGE_DIR. "wave-bot.png";?>')"></div>
                </div>
            </div>

        </div>

        <?php require_once MELA_PLUGIN_PATH . '/inc/admin/welcome/navigation.php';?>



		<div class="master_addons_contents">

			<?php
				require MELA_PLUGIN_PATH . '/inc/admin/welcome/addons.php';
				require MELA_PLUGIN_PATH . '/inc/admin/welcome/extensions.php';
				require MELA_PLUGIN_PATH . '/inc/admin/welcome/api-keys.php';
//				require MELA_PLUGIN_PATH . '/inc/admin/welcome/third-party-plugins.php';
				//                require MELA_PLUGIN_PATH . '/inc/admin/welcome/how-to.php';
				require MELA_PLUGIN_PATH . '/inc/admin/welcome/docs.php';
			    require MELA_PLUGIN_PATH . '/inc/admin/welcome/supports.php';
				if ( ma_el_fs()->can_use_premium_code() ) {
					require MELA_PLUGIN_PATH . '/inc/admin/welcome/osaka-pro.php';
				}else{
					require MELA_PLUGIN_PATH . '/inc/admin/welcome/free-themes.php';
                }
			    require MELA_PLUGIN_PATH . '/inc/admin/welcome/changelogs.php';
			?>

		</div>

	</div>
</div>


<script>
	jQuery(document).ready(function(){
		jQuery( "#accordion" ).accordion();
	});
</script>