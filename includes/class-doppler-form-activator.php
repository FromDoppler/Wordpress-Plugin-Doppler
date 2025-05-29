<?php

if (!defined('WPINC')) {
    die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Doppler_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		DPLR_Form_Model::init();
		DPLR_Field_Model::init();

		//Activate the tracking
		$script = '<script type="text/javascript" async="async" src="https://hub.fromdoppler.com/public/dhtrack.js" ></script>';
		update_option( 'dplr_hub_script', sanitize_text_field(htmlentities(trim($script))));

	}

}