<?php
/**
 * Image Differ Widget.
 *
 * @since 3.1.0
 */

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Border;
use \Elementor\Control_Media;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sina_Image_Differ_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 3.1.0
	 */
	public function get_name() {
		return 'sina_image_differ';
	}

	/**
	 * Get widget title.
	 *
	 * @since 3.1.0
	 */
	public function get_title() {
		return __( 'Sina Image Differ', 'sina-ext' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 3.1.0
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 3.1.0
	 */
	public function get_categories() {
		return [ 'sina-extension' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 3.1.0
	 */
	public function get_keywords() {
		return [ 'sina image differ', 'sina image comparison', 'sina image box', 'sina before after' ];
	}

	/**
	 * Get widget styles.
	 *
	 * Retrieve the list of styles the widget belongs to.
	 *
	 * @since 3.1.0
	 */
	public function get_style_depends() {
		return [
			'twentytwenty',
			'sina-widgets',
		];
	}

	/**
	 * Get widget scripts.
	 *
	 * Retrieve the list of scripts the widget belongs to.
	 *
	 * @since 3.1.0
	 */
	public function get_script_depends() {
		return [
			'jquery-event-move',
			'jquery-twentytwenty',
			'sina-widgets',
		];
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 */
	protected function _register_controls() {
		// Start Differ Content
		// =====================
		$this->start_controls_section(
			'differ_content',
			[
				'label' => __( 'Differ Content', 'sina-ext' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'before_image',
			[
				'label' => __( 'Before Image', 'sina-ext' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => SINA_EXT_URL .'assets/img/choose-img.jpg',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'after_image',
			[
				'label' => __( 'After Image', 'sina-ext' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => SINA_EXT_URL .'assets/img/choose-img.jpg',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
		// End Differ Content
		// ===================


		// Start Differ Settings
		// ======================
		$this->start_controls_section(
			'differ_settings',
			[
				'label' => __( 'Differ Settings', 'sina-ext' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'before_text',
			[
				'label' => __( 'Before Text', 'sina-ext' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Before',
			]
		);
		$this->add_control(
			'after_text',
			[
				'label' => __( 'After Text', 'sina-ext' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'After',
			]
		);
		$this->add_control(
			'orientation',
			[
				'label' => __( 'Orientation', 'sina-ext' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => __( 'Horizontal', 'sina-ext' ),
					'vertical' => __( 'Vertical', 'sina-ext' ),
				],
				'default' => 'horizontal',
			]
		);
		$this->add_control(
			'offset',
			[
				'label' => __( 'Slider Offset', 'sina-ext' ),
				'type' => Controls_Manager::NUMBER,
				'step' => 0.01,
				'min' => 0,
				'max' => 1,
				'default' => '0.5',
			]
		);
		$this->add_control(
			'no_overlay',
			[
				'label' => __( 'Overlay', 'sina-ext' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'sina-ext' ),
				'label_off' => __( 'Off', 'sina-ext' ),
			]
		);
		$this->add_control(
			'hover_move',
			[
				'label' => __( 'Slider move on hover', 'sina-ext' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'sina-ext' ),
				'label_off' => __( 'Off', 'sina-ext' ),
			]
		);
		$this->add_control(
			'click_move',
			[
				'label' => __( 'Slider move on click', 'sina-ext' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'sina-ext' ),
				'label_off' => __( 'Off', 'sina-ext' ),
			]
		);

		$this->end_controls_section();
		// End Differ Settings
		// ====================


		// Start Differ Style
		// =====================
		$this->start_controls_section(
			'differ_style',
			[
				'label' => __( 'Differ', 'sina-ext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_bg',
			[
				'label' => __( 'Overlay Background', 'sina-ext' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.3)',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-overlay:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'handle_bg',
			[
				'label' => __( 'Handle Background', 'sina-ext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1085e4',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-handle' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'handle_separator',
			[
				'label' => __( 'Handle Separator Color', 'sina-ext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-handle:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-handle:after' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'handle_arrow',
			[
				'label' => __( 'Arrow Color', 'sina-ext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-left-arrow' => 'border-right-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-down-arrow' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-up-arrow' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'handle_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'color' => [
						'default' => '#fff',
					],
					'width' => [
						'default' => [
							'top' => '4',
							'right' => '4',
							'bottom' => '4',
							'left' => '4',
							'isLinked' => true,
						]
					],
				],
				'selector' => '{{WRAPPER}} .twentytwenty-handle',
			]
		);

		$this->end_controls_section();
		// End Labels Style
		// ==================


		// Start Labels Style
		// =====================
		$this->start_controls_section(
			'lables_style',
			[
				'label' => __( 'Labels', 'sina-ext' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'labels_width',
			[
				'label' => __( 'Min Width', 'sina-ext' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'max' => 300,
					],
					'em' => [
						'max' => 20,
					],
				],
				'default' => [
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'fields_options' => [
					'typography' => [ 
						'default' =>'custom', 
					],
					'font_weight' => [
						'default' => '700',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
						],
					],
				],
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			]
		);
		$this->add_control(
			'labels_color',
			[
				'label' => __( 'Text Color', 'sina-ext' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .twentytwenty-after-label:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'lables_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'lables_shadow',
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'lables_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'color' => [
						'default' => '#fafafa',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						]
					],
				],
				'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
			]
		);
		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => __( 'Padding', 'sina-ext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '0',
					'right' => '15',
					'bottom' => '0',
					'left' => '15',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-after-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'lables_radius',
			[
				'label' => __( 'Radius', 'sina-ext' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .twentytwenty-before-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .twentytwenty-after-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// End Labels Style
		// ==================
	}


	protected function render() {
		$data = $this->get_settings_for_display();
		$before_img_alt = Control_Media::get_image_alt( $data['before_image'] );
		$after_img_alt = Control_Media::get_image_alt( $data['after_image'] );
		?>
		<div class="sina-image-differ"
		data-orientation="<?php echo esc_attr( $data['orientation'] ) ?>"
		data-overlay="<?php echo esc_attr( $data['no_overlay'] ) ?>"
		data-offset="<?php echo esc_attr( $data['offset'] ) ?>"
		data-click="<?php echo esc_attr( $data['click_move'] ) ?>"
		data-hover="<?php echo esc_attr( $data['hover_move'] ) ?>"
		data-before="<?php echo esc_attr( $data['before_text'] ) ?>"
		data-after="<?php echo esc_attr( $data['after_text'] ) ?>">
			<div class="twentytwenty-container">
				<img src="<?php echo esc_url( $data['before_image']['url'] ); ?>" alt="<?php echo esc_attr( $before_img_alt ) ?>" />
				<img src="<?php echo esc_url( $data['after_image']['url'] ); ?>" alt="<?php echo esc_attr( $after_img_alt ) ?>" />
			</div>
		</div><!-- .sina-image-differ -->
		<?php
	}


	protected function _content_template() {

	}
}