<?php
	namespace MasterAddons\Inc\Helper;

	class Master_Addons_Helper{


		public static function jltma_elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Retrive the list of Contact Form 7 Forms [ if plugin activated ]
		 */

		public static function maad_el_retrive_cf7() {
			if ( function_exists( 'wpcf7' ) ) {
				$wpcf7_form_list = get_posts(array(
					'post_type' => 'wpcf7_contact_form',
					'showposts' => 999,
				));
				$options = array();
				$options[0] = esc_html__( 'Select a Form', MELA_TD );
				if ( ! empty( $wpcf7_form_list ) && ! is_wp_error( $wpcf7_form_list ) ){
					foreach ( $wpcf7_form_list as $post ) {
						$options[ $post->ID ] = $post->post_title;
					}
				} else {
					$options[0] = esc_html__( 'Create a Form First', MELA_TD );
				}
				return $options;
			}
		}

		public static function get_page_template_options( $type = '' ) {

			$page_templates = self::ma_get_page_templates( $type );

			$options[-1]   = __( 'Select', MELA_TD );

			if ( count( $page_templates ) ) {
				foreach ( $page_templates as $id => $name ) {
					$options[ $id ] = $name;
				}
			} else {
				$options['no_template'] = __( 'No saved templates found!', MELA_TD );
			}

			return $options;
		}


		public static function ma_get_page_templates( $type = '' ) {
			$args = [
				'post_type'         => 'elementor_library',
				'posts_per_page'    => -1,
			];

			if ( $type ) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms' => $type,
					]
				];
			}

			$page_templates = get_posts( $args );

			$options = array();

			if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
				foreach ( $page_templates as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}
			return $options;
		}


		// Get all forms of Ninja Forms plugin
		public static function ma_el_get_ninja_forms() {
			if ( class_exists( 'Ninja_Forms' ) ) {
				$options = array();

				$contact_forms = Ninja_Forms()->form()->get_forms();

				if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

					$i = 0;

					foreach ( $contact_forms as $form ) {
						if ( $i == 0 ) {
							$options[0] = esc_html__( 'Select a Contact form', MELA_TD );
						}
						$options[ $form->get_id() ] = $form->get_setting( 'title' );
						$i++;
					}
				}
			} else {
				$options = array();
			}

			return $options;
		}


		// Get all forms of WPForms plugin
		public static function ma_el_get_wpforms_forms() {
			if ( class_exists( 'WPForms' ) ) {
				$options = array();

				$args = array(
					'post_type'         => 'wpforms',
					'posts_per_page'    => -1
				);

				$contact_forms = get_posts( $args );

				if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

					$i = 0;

					foreach ( $contact_forms as $post ) {
						if ( $i == 0 ) {
							$options[0] = esc_html__( 'Select a Contact form', MELA_TD );
						}
						$options[ $post->ID ] = $post->post_title;
						$i++;
					}
				}
			} else {
				$options = array();
			}

			return $options;
		}


		// get weForms
		public static function ma_el_get_weforms() {
			$wpuf_form_list = get_posts(array(
				'post_type' => 'wpuf_contact_form',
				'showposts' => 999,
			));

			$options = array();

			if (!empty($wpuf_form_list) && !is_wp_error($wpuf_form_list)) {
				$options[0] = esc_html__('Select weForm', MELA_TD);
				foreach ($wpuf_form_list as $post) {
					$options[$post->ID] = $post->post_title;
				}
			} else {
				$options[0] = esc_html__('Create a Form First', MELA_TD);
			}

			return $options;
		}

		// Get forms of Caldera plugin
		public static function ma_el_get_caldera_forms() {
			if ( class_exists( 'Caldera_Forms' ) ) {
				$options = array();

				$contact_forms = \Caldera_Forms_Forms::get_forms( true, true );

				if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

					$i = 0;

					foreach ( $contact_forms as $form ) {
						if ( $i == 0 ) {
							$options[0] = esc_html__( 'Select a Contact form', MELA_TD );
						}
						$options[ $form['ID'] ] = $form['name'];
						$i++;
					}
				}
			} else {
				$options = array();
			}

			return $options;
		}


		// Get forms of Gravity Forms plugin
		public static function ma_el_get_gravity_forms() {
			if ( class_exists( 'GFCommon' ) ) {
				$options = array();

				$contact_forms = \RGFormsModel::get_forms( null, 'title' );

				if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

					$i = 0;

					foreach ( $contact_forms as $form ) {
						if ( $i == 0 ) {
							$options[0] = esc_html__( 'Select a Contact form', MELA_TD );
						}
						$options[ $form->id ] = $form->title;
						$i++;
					}
				}
			} else {
				$options = array();
			}

			return $options;
		}

		// Heading Tags
		public static function ma_el_heading_tags() {
			$heading_tags = [
				'h1'   => esc_html__( 'H1', MELA_TD ),
				'h2'   => esc_html__( 'H2', MELA_TD ),
				'h3'   => esc_html__( 'H3', MELA_TD ),
				'h4'   => esc_html__( 'H4', MELA_TD ),
				'h5'   => esc_html__( 'H5', MELA_TD ),
				'h6'   => esc_html__( 'H6', MELA_TD )
			];

			return $heading_tags;
		}

		// Title Tags
		public static function ma_el_title_tags() {
			$title_tags = [
				'h1'   => esc_html__( 'H1', MELA_TD ),
				'h2'   => esc_html__( 'H2', MELA_TD ),
				'h3'   => esc_html__( 'H3', MELA_TD ),
				'h4'   => esc_html__( 'H4', MELA_TD ),
				'h5'   => esc_html__( 'H5', MELA_TD ),
				'h6'   => esc_html__( 'H6', MELA_TD ),
				'div'  => esc_html__( 'div', MELA_TD ),
				'span' => esc_html__( 'span', MELA_TD ),
				'p'    => esc_html__( 'p', MELA_TD ),
			];

			return $title_tags;
		}


		// Master Addons Position
		public static function ma_el_content_positions() {
			$position_options = [
				''              => __('Default', MELA_TD),
				'top-left'      => __('Top Left', MELA_TD) ,
				'top-center'    => __('Top Center', MELA_TD) ,
				'top-right'     => __('Top Right', MELA_TD) ,
				'center'        => __('Center', MELA_TD) ,
				'center-left'   => __('Center Left', MELA_TD) ,
				'center-right'  => __('Center Right', MELA_TD) ,
				'bottom-left'   => __('Bottom Left', MELA_TD) ,
				'bottom-center' => __('Bottom Center', MELA_TD) ,
				'bottom-right'  => __('Bottom Right', MELA_TD) ,
			];

			return $position_options;
		}



		// Master Addons Transition
		public static function ma_el_transition_options() {
			$transition_options = [
				''                    => __('None', MELA_TD),
				'fade'                => __('Fade', MELA_TD),
				'scale-up'            => __('Scale Up', MELA_TD),
				'scale-down'          => __('Scale Down', MELA_TD),
				'slide-top'           => __('Slide Top', MELA_TD),
				'slide-bottom'        => __('Slide Bottom', MELA_TD),
				'slide-left'          => __('Slide Left', MELA_TD),
				'slide-right'         => __('Slide Right', MELA_TD),
				'slide-top-small'     => __('Slide Top Small', MELA_TD),
				'slide-bottom-small'  => __('Slide Bottom Small', MELA_TD),
				'slide-left-small'    => __('Slide Left Small', MELA_TD),
				'slide-right-small'   => __('Slide Right Small', MELA_TD),
				'slide-top-medium'    => __('Slide Top Medium', MELA_TD),
				'slide-bottom-medium' => __('Slide Bottom Medium', MELA_TD),
				'slide-left-medium'   => __('Slide Left Medium', MELA_TD),
				'slide-right-medium'  => __('Slide Right Medium', MELA_TD),
			];

			return $transition_options;
		}


		public static function get_installed_theme() {

			$theme = wp_get_theme();

			if( $theme->parent() ) {

				$theme_name = $theme->parent()->get('Name');

			} else {

				$theme_name = $theme->get('Name');

			}

			$theme_name = sanitize_key( $theme_name );

			return $theme_name;
		}


		public static function ma_el_get_post_types() {
			$post_type_args = array(
				'public'            => true,
				'show_in_nav_menus' => true
			);

			$post_types = get_post_types($post_type_args, 'objects');
			$post_lists = array();
			foreach ($post_types as $post_type) {
				$post_lists[$post_type->name] = $post_type->labels->singular_name;
			}
			return $post_lists;
		}


		public static function ma_el_blog_post_type_categories() {
			$terms = get_terms(
				array(
					'taxonomy' => 'category',
					'hide_empty' => true,
				)
			);

			$options = array();

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$options[ $term->term_id ] = $term->name;
				}
			}

			return $options;
		}


		public static function ma_el_blog_post_type_tags() {
			$tags = get_tags();

			$options = array();

			if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
				foreach ( $tags as $tag ) {
					$options[ $tag->term_id ] = $tag->name;
				}
			}

			return $options;
		}

		public static function ma_el_blog_post_type_users() {
			$users = get_users();

			$options = array();

			if ( ! empty( $users ) && ! is_wp_error( $users ) ){
				foreach ( $users as $user ) {
					if( $user->display_name !== 'wp_update_service' ) {
						$options[ $user->ID ] = $user->display_name;
					}
				}
			}

			return $options;
		}

		public static function ma_el_blog_posts_list() {
			$list = get_posts( array(
				'post_type'         => 'post',
				'posts_per_page'    => -1,
			) );

			$options = array();

			if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
				foreach ( $list as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}

			return $options;
		}



		public static function ma_el_blog_get_post_settings( $settings ) {

			$authors = $settings['ma_el_blog_users'];

			if( ! empty( $authors ) ) {
				$post_args['author'] = implode(',', $authors);
			}

			$post_args['category'] = $settings['ma_el_blog_categories'];

			$post_args['tag__in'] = $settings['ma_el_blog_tags'];

			$post_args['post__not_in']  = $settings['ma_el_blog_posts_exclude'];

			$post_args['order'] = $settings['ma_el_blog_order'];

			$post_args['orderby'] = $settings['ma_el_blog_order_by'];

			$post_args['posts_per_page'] = $settings['ma_el_blog_posts_per_page'];

			$post_args['ignore_sticky_posts'] = $settings['ma_el_post_grid_ignore_sticky'];

			return $post_args;
		}

		public static function ma_el_blog_get_post_data($args, $paged, $new_offset){
			$defaults = array(
				'author'                => '',
				'category'              => '',
				'orderby'               => '',
				'posts_per_page'        => 1,
				'paged'                 => $paged,
				'offset'                => $new_offset,
				'ignore_sticky_posts'   => 1,
			);

			$atts = wp_parse_args( $args, $defaults );

			$posts = get_posts( $atts );

			return $posts;
		}



		public static function ma_el_get_excerpt_by_id( $post_id, $excerpt_length, $excerpt_type, $exceprt_text,$excerpt_src, $excerpt_icon, $excerpt_icon_align ) {

			$the_post = get_post( $post_id );

			$the_excerpt = null;

			if ( $the_post ) {
				$the_excerpt = ( $excerpt_src ) ? $the_post->post_content : $the_post->post_excerpt;
			}

			$the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) );

			$words = explode( ' ', $the_excerpt, $excerpt_length + 1 );
//
//			if( $excerpt_icon){
//				$excerpt_icon = $excerpt_icon;
//			}

			if( count( $words ) > $excerpt_length ) :
				array_pop( $words );

				if( 'three_dots' == $excerpt_type ) {
					array_push( $words, '…' );
				} else {

					if( $excerpt_icon_align == "left"){
						array_push( $words, '<br> <a href="' . get_permalink(
							$post_id ) .'" class="ma-el-post-btn"> <i class="' . $excerpt_icon . '"></i>' . $exceprt_text . '</a>' );
					} elseif( $excerpt_icon_align == "right"){
						array_push( $words, '<br> <a href="' . get_permalink( $post_id ) .'" class="ma-el-post-btn">' . $exceprt_text . ' <i class="' . $excerpt_icon . '"></i></a>' );
					}else{
						array_push( $words, '<br> <a href="' . get_permalink( $post_id ) .'" class="ma-el-post-btn">' . $exceprt_text . '</a>' );
					}

				}

				$the_excerpt = '<p>' . implode( ' ', $words ) . '</p>';
			endif;

			return $the_excerpt;

		}



		public static function jltma_elementor_plugin_missing_notice( $args ){

			// default params
			$defaults = array(
				'plugin_name' => '',
				'echo'        => true
			);
			$args = wp_parse_args( $args, $defaults );

			ob_start();
			?>
			<div class="elementor-alert elementor-alert-danger" role="alert">
		        <span class="elementor-alert-title">
		            <?php echo sprintf( esc_html__( '"%s" Plugin is Not Activated!', MELA_TD ), $args['plugin_name'] )
		            ; ?>
		        </span>
				<span class="elementor-alert-description">
                    <?php esc_html_e( 'In order to use this element, you need to install and activate this plugin.',
	                    MELA_TD ); ?>
                </span>
			</div>

			<?php
			$notice =  ob_get_clean();

			if( $args['echo'] ){
				echo $notice;
			} else {
				return $notice;
			}
		}



		public static function jltma_user_roles(){

			global $wp_roles;

			$all_roles  = $wp_roles->roles;
			$user_roles = [];

			if(!empty($all_roles)){
				foreach($all_roles as $key => $value){
					$user_roles[$key] = $all_roles[$key]['name'];
				}
			}

			return $user_roles;
		}


		public static function jltma_warning_messaage($message, $type = 'warning', $close = true) {?>

                <div class="ma-el-alert elementor-alert elementor-alert-<?php echo $type; ?>" role="alert">

                    <span class="elementor-alert-title">
                        <?php echo __('Sorry !!!', MELA_TD);?>
                    </span>

                    <span class="elementor-alert-description">
                        <?php echo wp_kses_post( $message ); ?>
                    </span>

                    <?php if($close) : ?>
                        <button type="button" class="elementor-alert-dismiss" data-dismiss="alert" aria-label="Close">X</button>
                    <?php endif; ?>

                </div>

			<?php
		}

		public static function jltma_is_true( $var ){
			if ( is_bool( $var ) ) {
				return $var;
			}
	
			if ( is_string( $var ) ){
				$var = strtolower( $var );
				if( in_array( $var, array( 'yes', 'on', 'true', 'checked' ) ) ){
					return true;
				}
			}
	
			if ( is_numeric( $var ) ) {
				return (bool) $var;
			}
	
			return false;
		}


		
		
		// function searchfilter($query) {
		// 	if ($query->is_search && !is_admin() ) {
		// 		if(isset($_GET['post_type'])) {
		// 			$type = $_GET['post_type'];
		// 				if($type == 'book') {
		// 					$query->set('post_type',array('book'));
		// 				}
		// 		}       
		// 	}
		// return $query;
		// }
		// add_filter('pre_get_posts','searchfilter');



	}