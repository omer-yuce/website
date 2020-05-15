<?php
namespace MasterAddons\Inc\Classes;

use \Elementor\Controls_Manager;
use \MasterAddons\Inc\Controls\MA_Transform_Element;
use \MasterAddons\Inc\Classes\JLTMA_Extension_Prototype;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; };

class JLTMA_Extension_Transforms extends JLTMA_Extension_Prototype {

    private static $instance = null;
    public $name = 'Transforms';
    public $has_controls = true;
   
    private function add_controls($element, $args) {

        $element_type = $element->get_type();

        $element->add_control(
            'enabled_transform', [
                'label' => __('Enabled Transforms', MELA_TD ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $element->add_group_control(
            MA_Transform_Element::get_type(), [
                'name' => 'transforms',
                'label' => 'Transforms',
                'default' => '',
                'selector' => '{{WRAPPER}} .ma-el-transforms',
                'condition' => [
                    'enabled_transform!' => '',
                ],
            ]
        );

    }

    protected function add_actions() {
        // Activate controls for widgets
        add_action('elementor/element/common/jltma_section_transforms_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);

        add_filter('elementor/widget/print_template', array($this, 'transforms_print_template'), 10, 2);

        add_action('elementor/widget/render_content', array($this, 'transforms_render_template'), 10, 2);
    }


    public function transforms_print_template($content, $widget) {
        if (!$content)
            return '';

        $content = "<# if ( settings.enabled_transform ) { #><div class=\"ma-el-transforms\"><div class=\"ma-el-transforms-wrap\">" . $content . "</div></div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function transforms_render_template($content, $widget) {
        $settings = $widget->get_settings_for_display();

        if ($settings['enabled_transform']) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {}
            $content = '<div class="ma-el-transforms"><div class="ma-el-transforms-wrap">' . $content . '</div></div>';    
        }
        return $content;
    }
    

    public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
    }
    
}

JLTMA_Extension_Transforms::get_instance();