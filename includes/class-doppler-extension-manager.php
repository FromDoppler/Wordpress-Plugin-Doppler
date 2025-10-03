<?php

class Doppler_Extension_Manager {

    private $doppler_service;

    public function __construct(  $doppler_service ) {
        $this->doppler_service = $doppler_service;
    }

    public $extensions = array( 
        'doppler-for-woocommerce' => array( 'class_name' => 'Doppler_For_Woocommerce',
											'dependency' => 'WooCommerce',
                                            'zip_file'   => 'https://downloads.wordpress.org/plugin/doppler-for-woocommerce.zip',
                                            'settings'   => 'doppler_woocommerce_menu' ),
        'doppler-for-learnpress'  => array( 'class_name' => 'Doppler_For_Learnpress',
											'dependency' => 'LearnPress',
                                            'zip_file'   => 'https://downloads.wordpress.org/plugin/doppler-for-learnpress.zip',
                                            'settings'   => 'doppler_learnpress_menu' )
	);

    /**
     * Check if an extension is active.
     */
    public function is_active( $extension_slug ) {
        if( !array_key_exists($extension_slug, $this->extensions) || !class_exists($this->extensions[$extension_slug]['class_name']) ){
            return false;
        }else if( class_exists($this->extensions[$extension_slug]['class_name']) ){
            return true;
        }
        return false;
    }
	
	/**
     * Check if an extension is active.
     */
    public function has_dependency( $extension_slug ) {
        if( !array_key_exists($extension_slug, $this->extensions) || !class_exists($this->extensions[$extension_slug]['dependency']) ){
            return false;
        }else if( class_exists($this->extensions[$extension_slug]['dependency']) ){
            return true;
        }
        return false;
    }

    /**
     * Install extensions.
     */
    public function install_extension() {
        $this->check_admin_permissions();

        if(empty($_POST['extensionName'])) return false;

        $slug = $_POST['extensionName'];
        
        if(!$this->is_plugin_installed($slug)){
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            wp_cache_flush();
            $upgrader = new Plugin_Upgrader();
            $buffer = $upgrader->install( $this->extensions[$slug]['zip_file'], array( 'clear_destination' => true ) );
        }
        
        if(!$this->is_active($slug)){
            $extension_path = DOPPLER_PLUGINS_PATH .$slug.'\\'.$slug.'.php';
            if(activate_plugin($extension_path) == NULL){
                echo '1';
            }
        }

        if(!$this->has_latest_plugin_version($slug)){
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            wp_cache_flush();
            $upgrader = new Plugin_Upgrader();
            $upgrader->upgrade($slug.'/'.$slug.'.php');

            $extension_path = DOPPLER_PLUGINS_PATH .$slug.'\\'.$slug.'.php';
            if(activate_plugin($extension_path) == NULL){
                echo '1';
            }
        }

        exit();
    }

    /**
     * Check if an extension is installed.
     */
    public function is_plugin_installed( $slug ) {
        if ( ! function_exists( 'get_plugins' ) ) {
          require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $extension = $slug.'/'.$slug.'.php';
        $all_plugins = get_plugins();
                
        if ( !empty( $all_plugins[$extension] ) ) {
          return true;
        } else {
          return false;
        }
    }
    
    private function get_latest_plugin_version( $extension_slug ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
    
        $args = array(
            'slug' => $extension_slug,
            'fields' => array(
                'version' => true,
            ),
        );
    
        $response = plugins_api( 'plugin_information', $args );
    
        if ( is_wp_error( $response ) ) {
            return false;
        }
    
        return $response->version;
    }

    public function has_latest_plugin_version($extension_slug) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
          }
        
        $all_plugins = get_plugins();

        $plugin_file = '';
        foreach ( $all_plugins as $file => $plugin_data ) {
            $plugin_folder = dirname( $file );
            if ( $plugin_folder === $extension_slug ) {
                $plugin_file = $file;
                break;
            }
        }

        if ( empty( $plugin_file ) ) {            
            return false;
        }

        $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );
        $current_version = $plugin_data['Version'];

        $latest_version = $this->get_latest_plugin_version( $extension_slug );
    
        if ( $latest_version && version_compare( $current_version, $latest_version, '<' ) ) {
            return false;
        } else {
            return true;
        }
    }

    public function add_elementor_action( $form_actions_registrar ) {
        include_once( __DIR__ .  '/class-doppler-elementor-integration.php' );
        $form_actions_registrar->register( new Doppler_Elementor_Integration($this->doppler_service) );
    }

    private function check_admin_permissions() {
		if ( ! current_user_can('manage_options') ) {
			wp_send_json_error(['message' => __('Unauthorized', 'doppler-form')]);
			wp_die();
    	}
	}
}