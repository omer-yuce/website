<?php
	/**
	 * Snippet Name: RSS Feed to dashboard
	 * Snippet URL: https://jeweltheme.com/category/master-addons/feed/
	 */

	add_action('wp_dashboard_setup', 'ma_el_dashboard_widgets');

	function ma_el_dashboard_widgets() {
		global $wp_meta_boxes;
		unset(
			$wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
			$wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
			$wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']
		);

		// add a custom dashboard widget
		wp_add_dashboard_widget(
			'master-addons-news-feed',
			'<img src="https://master-addons.com/wp-content/uploads/2019/06/icon-128x128.png" height="20" width="20">' .
			esc_html__
			('Master Addons News & Updates', MELA_TD),
			'ma_el_dashboard_news_feed' );
	}


	function get_dashboard_overview_widget_footer_actions() {
		$base_actions = [
			'blog' => [
				'title' => __( 'Blog', MELA_TD ),
				'link' => 'https://master-addons.com/blog/',
			],
			'help' => [
				'title' => __( 'Help', MELA_TD ),
				'link' => 'https://master-addons.com/docs/',
			],
		];

		$additions_actions = [
			'go-pro' => [
				'title' => __( 'Go Pro', MELA_TD ),
				'link' => 'http://bit.ly/2ly5eaQ#utm_source=dashboard&utm_medium=dashboard&utm_campaign=Dashboard&utm_term=dashboard&utm_content=dashboard',
			],
		];

		$additions_actions = apply_filters( 'master_addons/admin/dashboard_overview_widget/footer_actions',
			$additions_actions );

		$actions = $base_actions + $additions_actions;

		return $actions;
	}




	function ma_el_dashboard_news_feed() {
		echo '<div class="master-addons-posts">';
		wp_widget_rss_output(array(
			'url' => 'https://master-addons.com/blog/',
			'title' => __('Master Addons News & Updates',MELA_TD),
			'items' => 5,
			'show_summary' => 1,
			'show_author' => 0,
			'show_date' => 1
		));
		echo "</div>";
		?>

		<div class="master-addons-dashboard_footer">
			<ul>
				<?php foreach ( get_dashboard_overview_widget_footer_actions() as $action_id => $action ) : ?>
					<li class="ma-el-overview__<?php echo esc_attr( $action_id ); ?>"><a href="<?php echo esc_attr(
						$action['link'] ); ?>" target="_blank"><?php echo esc_html( $action['title'] ); ?> <span
								class="screen-reader-text"><?php echo __( '(opens in a new window)', MELA_TD );
								?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

<?php
	}


	function ma_el_array_flatten($array) {
		if (!is_array($array)) {
			return false;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
//				$result = array_merge($result, array_values($value));
				$result[$key] = $value[0];
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}





	function ma_el_image_filter_gallery_categories( $gallery_items ){

        if (!is_array($gallery_items)) {
            return false;
        }

        $gallery_category_names = array();
        $gallery_category_names_final = array();

        if( is_array( $gallery_items ) ){

            foreach( $gallery_items as $gallery_item ) :
                $gallery_category_names[] = $gallery_item['gallery_category_name'];
            endforeach;

            if( is_array($gallery_category_names) && !empty($gallery_category_names) ){
                foreach ($gallery_category_names as $gallery_category_name ) {
                    $gallery_category_names_final[] = explode(',', $gallery_category_name);
                }
            }

            if( is_array($gallery_category_names_final) && !empty($gallery_category_names_final) && function_exists('ma_el_image_filter_gallery_array_flatten') ){
                $gallery_category_names_final = ma_el_image_filter_gallery_array_flatten($gallery_category_names_final);
                return array_unique( array_filter($gallery_category_names_final) );
            }

        }
    }

    /*
     * Gallery Item Class
     */
	function ma_el_image_filter_gallery_category_classes( $gallery_classes, $id ){

		if (!($gallery_classes)) {
			return false;
		}

		$gallery_cat_classes    = array();
		$gallery_classes        = explode(',', $gallery_classes);

		if( is_array($gallery_classes) && !empty($gallery_classes) ){
			foreach ($gallery_classes as $gallery_class ) {
				$gallery_cat_classes[] = sanitize_title( $gallery_class ) . '-' . $id;
			}
		}

		return implode(' ', $gallery_cat_classes);
	}


	// Ribbon Categories
	function ma_el_image_filter_gallery_categories_parts( $gallery_classes ){

		if (!($gallery_classes)) {
			return false;
		}

		$gallery_cat_classes    = array();
		$gallery_classes        = explode(',', $gallery_classes);

		if( is_array($gallery_classes) && !empty($gallery_classes) ){
			foreach ($gallery_classes as $gallery_class ) {
				$gallery_cat_classes[] = '<div class="ma-el-label ma-el-added ma-el-image-filter-cat">' . sanitize_title( $gallery_class ) . '</div>';
			}
		}

		return implode(' ', $gallery_cat_classes);
	}


    function ma_el_image_filter_gallery_array_flatten($array) {
        if (!is_array($array)) {
            return false;
        }

        $result = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, ma_el_image_filter_gallery_array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }



    function ma_el_multi_dimension_flatten($array, $prefix = '') {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + ma_el_multi_dimension_flatten($value, $prefix . $key . '.');
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }


	function ma_el_hex2rgb_array( $hex ) {
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else { // strlen($hex) != 3
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );
		return $rgb; // returns an array with the rgb values
	}


	//reference https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
	function ma_el_hex2Rgb($hex, $alpha = false) {
		$hex      = str_replace('#', '', $hex);
		$length   = strlen($hex);
		$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
		$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
		$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
		if ( $alpha ) {
			$rgb['a'] = $alpha;
		}
		return $rgb;
	}
