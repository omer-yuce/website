<?php

namespace PrimeSlider;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
if ( !function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}
final class Manager
{
    private  $_modules = null ;
    private function is_module_active( $module_id )
    {
        $module_data = $this->get_module_data( $module_id );
        $options = get_option( 'prime_slider_active_modules', [] );
        
        if ( !isset( $options[$module_id] ) ) {
            return $module_data['default_activation'];
        } else {
            
            if ( $options[$module_id] == "on" ) {
                return true;
            } else {
                return false;
            }
        
        }
    
    }
    
    private function has_module_style( $module_id )
    {
        $module_data = $this->get_module_data( $module_id );
        
        if ( isset( $module_data['has_style'] ) ) {
            return $module_data['has_style'];
        } else {
            return false;
        }
    
    }
    
    private function has_module_script( $module_id )
    {
        $module_data = $this->get_module_data( $module_id );
        
        if ( isset( $module_data['has_script'] ) ) {
            return $module_data['has_script'];
        } else {
            return false;
        }
    
    }
    
    private function get_module_data( $module_id )
    {
        return ( isset( $this->_modules[$module_id] ) ? $this->_modules[$module_id] : false );
    }
    
    public function __construct()
    {
        $modules = [
            'blog',
            'flogia',
            'general',
            'isolate',
            'multiscroll',
            'woocommerce'
        ];
        // Fetch all modules data
        foreach ( $modules as $module ) {
            $this->_modules[$module] = (require BDTPS_MODULES_PATH . $module . '/module.info.php');
        }
        $direction_suffix = ( is_rtl() ? '.rtl' : '' );
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        foreach ( $this->_modules as $module_id => $module_data ) {
            if ( !$this->is_module_active( $module_id ) ) {
                continue;
            }
            $class_name = str_replace( '-', ' ', $module_id );
            $class_name = str_replace( ' ', '', ucwords( $class_name ) );
            $class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\\Module';
            // register widget css
            if ( $this->has_module_style( $module_id ) ) {
                wp_register_style(
                    'ps-' . $module_id,
                    BDTPS_URL . 'assets/css/ps-' . $module_id . $direction_suffix . '.css',
                    [],
                    BDTPS_VER
                );
            }
            // register widget javascript
            if ( $this->has_module_script( $module_id ) ) {
                wp_register_script(
                    'ps-' . $module_id,
                    BDTPS_URL . 'assets/js/widgets/ps-' . $module_id . $suffix . '.js',
                    [
                    'jquery',
                    'bdt-uikit',
                    'elementor-frontend',
                    'prime-slider-site'
                ],
                    BDTPS_VER,
                    true
                );
            }
            $class_name::instance();
        }
    }

}