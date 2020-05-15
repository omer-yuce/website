
<!-- Start tabs -->
<ul class="wp-tab-bar master_addons_navbar">

    <li class="wp-tab-active">
        <a href="#ma-addons">
			<?php _e( 'Addons', MELA_TD ); ?>
        </a>
    </li>

	<li>
		<a href="#extensions">
			<?php _e( 'Extensions', MELA_TD ); ?>
		</a>
	</li>
<!---->
<!--	<li>-->
<!--		<a href="#ma_api_keys">-->
<!--			--><?php //_e( 'API Keys', MELA_TD ); ?>
<!--		</a>-->
<!--	</li>-->
<!---->
<!--	<li>-->
<!--		<a href="#ma_el_third_party_plugins">-->
<!--			--><?php //_e( 'Third Party Plugins', MELA_TD ); ?>
<!--		</a>-->
<!--	</li>-->

	<li>
		<a href="#docs">
			<?php _e( 'Docs', MELA_TD ); ?>
		</a>
	</li>

	<li>
		<a href="#support">
			<?php _e( 'Support', MELA_TD ); ?>
		</a>
	</li>

	<li>
		<a href="#free-themes">
			<?php
				if ( ma_el_fs()->can_use_premium_code() ) {
					_e( 'Osaka Pro', MELA_TD );
				}else{
					_e( 'Free Themes', MELA_TD );
                }
                 ?>
		</a>
	</li>

	<li>
		<a href="#changelogs">
			<?php _e( 'Changelogs', MELA_TD ); ?>
		</a>
	</li>


</ul>
<!-- End tabs -->

