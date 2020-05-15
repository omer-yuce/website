<?php
	namespace MasterAddons\Admin\Dashboard\Addons;
	use MasterAddons\Master_Elementor_Addons;
?>


<div class="wp-tab-panel" id="ma-addons">
    <div class="master_addons_features">

        <div class="master-addons-el-dashboard-header-wrapper">
            <div class="master-addons-el-dashboard-header-right">
                <button type="submit" class="master-addons-el-btn master-addons-el-js-element-save-setting">
					<?php _e('Save Settings', MELA_TD ); ?>
                </button>
            </div>
        </div>


        <div class="master-addons-el-dashboard-wrapper">
            <form action="" method="POST" id="master-addons-el-settings" class="master-addons-el-settings"
                  name="master-addons-el-settings">

				<?php wp_nonce_field( 'maad_el_settings_nonce_action' ); ?>

                <div class="master-addons-el-dashboard-tabs-wrapper">

                    <div id="master-addons-elements" class="master-addons-el-dashboard-header-left master-addons-dashboard-tab master_addons_features">

                        <div class="master_addons_feature">

                            <h3><?php echo __('Master Addons', MELA_TD);?></h3>

							<?php foreach( array_slice(Master_Elementor_Addons::$maad_el_default_widgets, 0, 32) as
								$key=>$widget ) : ?>

                                <div class="master-addons-dashboard-checkbox col">

                                    <p class="master-addons-el-title">
										<?php
											$is_pro = "";
											if ( isset( $widget ) ) {
												if ( is_array( $widget ) ) {
													$is_pro = $widget[1];
													$widget = $widget[0];

													if( !ma_el_fs()->can_use_premium_code()) {
														echo '<span class="pro-ribbon">';
														echo ucwords( $is_pro );
														echo '</span>';
													}
												}
												$ma_el_first_two_upper = strtoupper( substr( $widget, 0, 2 ) ).substr($widget, 2 );
												echo esc_html( ucwords( str_replace( "-", " ", $ma_el_first_two_upper ) ) );
											}
										?>
                                    </p>

                                    <?php
										if ( isset( $widget ) ) {
											if ( is_array( $widget ) ) {
												$is_pro = $widget[1];
											}
										}
										?>

                                        <label for="<?php echo esc_attr( $widget ); ?>" class="switch switch-text
                                             switch-primary switch-pill <?php
											if( !ma_el_fs()->can_use_premium_code() && isset($is_pro) && $is_pro !="") { echo
											"ma-el-pro";} ?>">
                                            <input
                                                    type="checkbox" id="<?php echo esc_attr( $widget ); ?>"
                                                    class="switch-input "
                                                    name="<?php echo esc_attr( $widget ); ?>"
												<?php
													if( !ma_el_fs()->can_use_premium_code() && $is_pro =="pro") {
														checked( 1, $this->maad_el_get_settings[$widget], false );
//														echo "disabled";
													}else{
														checked( 1, $this->maad_el_get_settings[$widget] );
													}  ?> />

                                            <span data-on="On" data-off="Off" class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                </div>
							<?php endforeach; ?>

                        </div> <!--  .master_addons_feature-->

                        <div class="master_addons_feature">

                            <h3><?php echo esc_html__('Form Addons', MELA_TD);?></h3>

							<?php foreach( array_slice( Master_Elementor_Addons::$maad_el_default_widgets, 32, 6 ) as
								$key=>$widget ) : ?>

                                <div class="master-addons-dashboard-checkbox col">

                                    <p class="master-addons-el-title">
										<?php
											$is_pro = "";
											if ( isset( $widget ) ) {
												if ( is_array( $widget ) ) {
													$is_pro = $widget[1];
													$widget = $widget[0];

													if( !ma_el_fs()->can_use_premium_code()) {
														echo '<span class="pro-ribbon">';
														echo ucwords( $is_pro );
														echo '</span>';
													}
												}
												echo esc_html( ucwords( str_replace( "-", " ", $widget ) ) );
											}
										?>
                                    </p>


									<?php
										if ( isset( $widget ) ) {
											if ( is_array( $widget ) ) {
												$is_pro = $widget[1];
											}
										}
										?>

                                        <label for="<?php echo esc_attr( $widget ); ?>" class="switch switch-text
                                             switch-primary switch-pill <?php
											if( !ma_el_fs()->can_use_premium_code() && isset($is_pro) && $is_pro !="") { echo
											"ma-el-pro";} ?>">
                                            <input
                                                    type="checkbox" id="<?php echo esc_attr( $widget ); ?>"
                                                    class="switch-input "
                                                    name="<?php echo esc_attr( $widget ); ?>"
	                                            <?php
		                                            if( !ma_el_fs()->can_use_premium_code() && $is_pro =="pro") {
			                                            checked( 1, $this->maad_el_get_settings[$widget], false );
//														echo "disabled";
		                                            }else{
			                                            checked( 1, $this->maad_el_get_settings[$widget] );
		                                            }  ?> />

                                            <span data-on="On" data-off="Off" class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                </div>

							<?php endforeach; ?>

                        </div> <!--  .master_addons_feature-->


                        <div class="master_addons_feature">

                            <h3><?php echo esc_html__('Marketing', MELA_TD);?></h3>

		                    <?php foreach( array_slice( Master_Elementor_Addons::$maad_el_default_widgets, 38, 1 ) as
			                    $key=>$widget ) : ?>

                                <div class="master-addons-dashboard-checkbox col">

                                    <p class="master-addons-el-title">
					                    <?php
						                    $is_pro = "";
						                    if ( isset( $widget ) ) {
							                    if ( is_array( $widget ) ) {
								                    $is_pro = $widget[1];
								                    $widget = $widget[0];

								                    if( !ma_el_fs()->can_use_premium_code()) {
									                    echo '<span class="pro-ribbon">';
									                    echo ucwords( $is_pro );
									                    echo '</span>';
								                    }
							                    }
							                    $ma_el_first_two_upper = strtoupper( substr( $widget, 0, 2 ) ).substr($widget, 2 );
							                    echo esc_html( ucwords( str_replace( "-", " ", $ma_el_first_two_upper ) ) );
						                    }
					                    ?>
                                    </p>


				                    <?php
					                    if ( isset( $widget ) ) {
						                    if ( is_array( $widget ) ) {
							                    $is_pro = $widget[1];
						                    }
					                    }
				                    ?>

                                    <label for="<?php echo esc_attr( $widget ); ?>" class="switch switch-text
                                             switch-primary switch-pill <?php
					                    if( !ma_el_fs()->can_use_premium_code() && isset($is_pro) && $is_pro !="") { echo
					                    "ma-el-pro";} ?>">
                                        <input
                                                type="checkbox" id="<?php echo esc_attr( $widget ); ?>"
                                                class="switch-input "
                                                name="<?php echo esc_attr( $widget ); ?>"
						                    <?php
							                    if( !ma_el_fs()->can_use_premium_code() && $is_pro =="pro") {
								                    checked( 1, $this->maad_el_get_settings[$widget], false );
//														echo "disabled";
							                    }else{
								                    checked( 1, $this->maad_el_get_settings[$widget] );
							                    }  ?> />

                                        <span data-on="On" data-off="Off" class="switch-label"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </div>

		                    <?php endforeach; ?>

                        </div> <!--  .master_addons_feature-->




                    </div>
                </div> <!-- .master-addons-el-dashboard-tabs-wrapper-->


            </form>



        </div>
    </div>
</div>