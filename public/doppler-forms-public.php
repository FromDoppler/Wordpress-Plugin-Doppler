<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class DPLR_Doppler_Form_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $doppler_service ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->doppler_service = $doppler_service;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'jquery-ui-datepicker', plugin_dir_url( __FILE__ ) . 'css/vendor/jquery-ui.css', array(), '1.12.1', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/doppler-form-public.css', array(), $this->version, 'all' );
		wp_enqueue_style('css-input-tel', plugin_dir_url( __FILE__ ) . 'css/vendor/intlTelInput.css', array(), '18.2.1', 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/doppler-form-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('js-input-tel', plugin_dir_url( __FILE__ ) . 'js/vendor/intlTelInput.min.js', array('jquery'), '18.2.1', false);
		wp_enqueue_script('js-input-date', plugin_dir_url( __FILE__ ) . 'js/vendor/datepickr.js', array('jquery'), '1.0.0', false);
		wp_localize_script( $this->plugin_name, 'dplr_obj_vars',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'errorMsg',
			array( 'err' => esc_html__("Invalid Format.",'doppler-form') )
		);

	}

	public function submit_form() {
		check_ajax_referer( 'dplr_form_submit_nonce_action', 'dplr_form_submit_nonce' );

		try
		{
			if(isset($_POST['subscriber']) && isset($_POST['list_id']) && isset($_POST['form_id'])){
				$this->doppler_service->pluginLogger(array( 'action' => 'init submit_form', 'data' => $_POST), 'submit_form');

				$options = get_option('dplr_settings'); 
				$this->doppler_service->setCredentials(['api_key' => $options['dplr_option_apikey'], 'user_account' => $options['dplr_option_useraccount']]);
	
				$subscriber_resource = $this->doppler_service->getResource('subscribers');
	
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$subscriber = $_POST['subscriber'];
				$form_id = absint(wp_unslash($_POST['form_id']));
				$list_id = absint(wp_unslash($_POST['list_id']));
	
				$form = DPLR_Form_Model::get($form_id, true);
				$subscriber["form_doble_optin"] = $form->settings["form_doble_optin"] ?? 'no';
				$subscriber["form_plantilla_id"] = $form->settings["form_plantilla_id"] ?? null;
	
				if(isset($subscriber['hp']) && $subscriber['hp']==''){
					unset($subscriber['hp']);
					if($form->settings["form_doble_optin"] === "yes"){
						$result = $subscriber_resource->addSubscriberDobleOptIn($list_id, $subscriber);
					}
					else{
						$result = $subscriber_resource->addSubscriber($list_id, $subscriber);
					}
					$this->doppler_service->pluginLogger(array('action' => 'result submit_form', 'data' => $result), 'submit_form');
				}
	
				if (is_array($result) && isset($result['response']['code']) && $result['response']['code'] === 200) {
					self::registerSubmitEvent($form_id);
				}
	
				$this->doppler_service->pluginLogger(array( 'action' => 'finish submit_form'), 'submit_form');
			}
		}
		catch(\Exception $err) {
			$this->doppler_service->pluginLogger(array( 'action' => 'error submit_form', 'data' => $err), 'submit_form_error');
		}

	}

	/**
	 * Add tracking script to site's header.
	 */
	public function add_tracking_script() {
		$script = get_option('dplr_hub_script');
		if(!empty($script)){
			echo wp_kses(
				stripslashes(html_entity_decode($script)),
				array(
					'script'      => array(
						'type'  => array(),
						'async' => array(),
						'src' => array(),
					)
				));
		}
	}

	private static function registerSubmitEvent($formId) {
		require_once(plugin_dir_path( __FILE__ ) . "../includes/enums/EventType.php");

		DPLR_Form_Model::insertEvent([
			'parent_id'=>$formId,
			'event_type' => EventType::SUBMIT,
			'event_date' => DPLR_Form_Model::now()
		]);
	}
}
