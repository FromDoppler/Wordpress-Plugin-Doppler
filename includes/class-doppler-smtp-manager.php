<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SMTP runtime and Doppler Relay settings manager.
 */
class DPLR_SMTP_Manager {

	/**
	 * SMTP settings option name.
	 *
	 * @var string
	 */
	private $option_name = 'dplr_smtp_settings';

	/**
	 * SMTP settings option group.
	 *
	 * @var string
	 */
	private $option_group = 'dplr_smtp_options';

	/**
	 * Relay connection option name.
	 *
	 * @var string
	 */
	private $relay_option_name = 'dplr_relay_connection';

	/**
	 * Relay domains cache transient.
	 *
	 * @var string
	 */
	private $relay_domains_transient = 'dplr_relay_domains_cache';

	/**
	 * Relay API URL.
	 *
	 * @var string
	 */
	private $relay_api_base_url = 'https://api.dopplerrelay.com';

	/**
	 * Fixed SMTP host.
	 *
	 * @var string
	 */
	private $relay_smtp_host = 'smtp.dopplerrelay.com';

	/**
	 * Fixed SMTP port.
	 *
	 * @var int
	 */
	private $relay_smtp_port = 587;

	/**
	 * Registers the SMTP and Relay options.
	 */
	public function register_settings() {
		add_option( $this->option_name, $this->get_default_settings(), '', 'no' );
		add_option( $this->relay_option_name, $this->get_default_relay_connection(), '', 'no' );

		register_setting(
			$this->option_group,
			$this->option_name,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->get_default_settings(),
			)
		);
	}

	/**
	 * Returns the SMTP option group.
	 *
	 * @return string
	 */
	public function get_option_group() {
		return $this->option_group;
	}

	/**
	 * Returns the SMTP option name.
	 *
	 * @return string
	 */
	public function get_option_name() {
		return $this->option_name;
	}

	/**
	 * Returns the Relay option name.
	 *
	 * @return string
	 */
	public function get_relay_option_name() {
		return $this->relay_option_name;
	}

	/**
	 * Returns the fixed Relay SMTP host.
	 *
	 * @return string
	 */
	public function get_fixed_host() {
		return $this->relay_smtp_host;
	}

	/**
	 * Returns the fixed Relay SMTP port.
	 *
	 * @return int
	 */
	public function get_fixed_port() {
		return $this->relay_smtp_port;
	}

	/**
	 * Returns the fixed Relay SMTP encryption label.
	 *
	 * @return string
	 */
	public function get_fixed_encryption_label() {
		return 'TLS';
	}

	/**
	 * Returns the merged SMTP settings.
	 *
	 * @return array
	 */
	public function get_settings() {
		$stored_settings = get_option( $this->option_name, array() );

		if ( ! is_array( $stored_settings ) ) {
			$stored_settings = array();
		}

		return array_merge( $this->get_default_settings(), $stored_settings );
	}

	/**
	 * Returns the merged Relay connection data.
	 *
	 * @return array
	 */
	public function get_relay_connection() {
		$connection = get_option( $this->relay_option_name, array() );

		if ( ! is_array( $connection ) ) {
			$connection = array();
		}

		return array_merge( $this->get_default_relay_connection(), $connection );
	}

	/**
	 * Returns whether a Relay connection exists.
	 *
	 * @return bool
	 */
	public function has_relay_connection() {
		$connection = $this->get_relay_connection();

		return ! empty( $connection['account_name'] ) && ! empty( $connection['api_key'] );
	}

	/**
	 * Validates and stores the Relay connection.
	 *
	 * @param string $account_name Relay API account name.
	 * @param string $api_key Relay API key.
	 * @return array|WP_Error
	 */
	public function connect_relay( $account_name, $api_key ) {
		$account_name = trim( sanitize_text_field( $account_name ) );
		$api_key      = trim( sanitize_text_field( $api_key ) );

		if ( empty( $account_name ) || empty( $api_key ) ) {
			return new WP_Error(
				'dplr_relay_missing_credentials',
				__( 'Please enter your API Account Name and API Key / SMTP Password.', 'doppler-form' )
			);
		}

		$domains_data = $this->request_relay_domains( $account_name, $api_key );

		if ( is_wp_error( $domains_data ) ) {
			return $domains_data;
		}

		update_option(
			$this->relay_option_name,
			array(
				'account_name' => $account_name,
				'api_key'      => $api_key,
				'connected_at' => current_time( 'mysql' ),
			),
			false
		);

		$this->cache_relay_domains( $domains_data );

		return $domains_data;
	}

	/**
	 * Clears the Relay connection and dependent SMTP settings.
	 */
	public function disconnect_relay() {
		delete_option( $this->relay_option_name );
		$this->clear_relay_domains_cache();
		update_option( $this->option_name, $this->get_default_settings(), false );
	}

	/**
	 * Returns Relay domains data from cache or API.
	 *
	 * @param bool $force_refresh Whether to bypass cache.
	 * @return array|false|WP_Error
	 */
	public function get_relay_domains_data( $force_refresh = false ) {
		if ( ! $this->has_relay_connection() ) {
			return false;
		}

		if ( ! $force_refresh ) {
			$cached_domains = get_transient( $this->relay_domains_transient );
			if ( is_array( $cached_domains ) ) {
				return $cached_domains;
			}
		}

		$connection   = $this->get_relay_connection();
		$domains_data = $this->request_relay_domains( $connection['account_name'], $connection['api_key'] );

		if ( is_wp_error( $domains_data ) ) {
			return $domains_data;
		}

		$this->cache_relay_domains( $domains_data );

		return $domains_data;
	}

	/**
	 * Returns the verified Relay domains list.
	 *
	 * @param array|null $domains_data Relay domains response.
	 * @return array
	 */
	public function get_verified_domains( $domains_data = null ) {
		if ( null === $domains_data ) {
			$domains_data = $this->get_relay_domains_data();
		}

		if ( ! is_array( $domains_data ) || empty( $domains_data['domains'] ) || ! is_array( $domains_data['domains'] ) ) {
			return array();
		}

		$verified_domains = array();

		foreach ( $domains_data['domains'] as $domain ) {
			if ( empty( $domain['name'] ) ) {
				continue;
			}

			if ( ! empty( $domain['dkim_ready'] ) && ! empty( $domain['spf_ready'] ) && ! empty( $domain['dmarc_ready'] ) ) {
				$verified_domains[] = sanitize_text_field( $domain['name'] );
			}
		}

		return array_values( array_unique( $verified_domains ) );
	}

	/**
	 * Returns the selected verified domain for the current settings.
	 *
	 * @param array|null $settings SMTP settings.
	 * @param array|null $domains_data Relay domains data.
	 * @return string
	 */
	public function get_selected_domain( $settings = null, $domains_data = null ) {
		$settings         = is_array( $settings ) ? array_merge( $this->get_default_settings(), $settings ) : $this->get_settings();
		$verified_domains = $this->get_verified_domains( $domains_data );

		if ( empty( $verified_domains ) ) {
			return '';
		}

		if ( ! empty( $settings['from_domain'] ) && in_array( $settings['from_domain'], $verified_domains, true ) ) {
			return $settings['from_domain'];
		}

		if ( is_array( $domains_data ) && ! empty( $domains_data['default'] ) && in_array( $domains_data['default'], $verified_domains, true ) ) {
			return $domains_data['default'];
		}

		return reset( $verified_domains );
	}

	/**
	 * Sanitizes SMTP settings before saving them.
	 *
	 * @param mixed $input Raw input.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$existing_settings = $this->get_settings();
		$sanitized         = array_merge( $this->get_default_settings(), $existing_settings );
		$input             = is_array( $input ) ? $input : array();
		$has_errors         = false;
		$invalid_from_alias = false;

		$sanitized['enabled']         = empty( $input['enabled'] ) ? 0 : 1;
		$sanitized['smtp_user']       = isset( $input['smtp_user'] ) ? sanitize_text_field( $input['smtp_user'] ) : '';
		$sanitized['from_local_part'] = isset( $input['from_local_part'] ) ? trim( sanitize_text_field( $input['from_local_part'] ) ) : '';
		$sanitized['from_name']       = isset( $input['from_name'] ) ? sanitize_text_field( $input['from_name'] ) : '';

		if ( ! $this->has_relay_connection() ) {
			add_settings_error(
				$this->option_name,
				'dplr_smtp_missing_relay_connection',
				__( 'Connect your Doppler Relay account before configuring SMTP.', 'doppler-form' )
			);

			$sanitized['enabled']    = 0;
			$sanitized['from_domain'] = '';
			$sanitized['from_email'] = '';

			return $sanitized;
		}

		$domains_data = $this->get_relay_domains_data();
		if ( is_wp_error( $domains_data ) ) {
			add_settings_error(
				$this->option_name,
				'dplr_smtp_domains_fetch_error',
				$domains_data->get_error_message()
			);

			$sanitized['enabled'] = 0;

			return $sanitized;
		}

		$verified_domains = $this->get_verified_domains( $domains_data );
		if ( empty( $verified_domains ) ) {
			add_settings_error(
				$this->option_name,
				'dplr_smtp_no_verified_domains',
				__( 'You need at least one verified domain with DKIM, SPF and DMARC ready in Doppler Relay before enabling SMTP.', 'doppler-form' )
			);

			$sanitized['enabled']     = 0;
			$sanitized['from_domain'] = '';
			$sanitized['from_email']  = '';

			return $sanitized;
		}

		$requested_domain = isset( $input['from_domain'] ) ? sanitize_text_field( $input['from_domain'] ) : $this->get_selected_domain( $sanitized, $domains_data );

		if ( ! in_array( $requested_domain, $verified_domains, true ) ) {
			$requested_domain = $this->get_selected_domain( $sanitized, $domains_data );
			$has_errors       = true;

			add_settings_error(
				$this->option_name,
				'dplr_smtp_invalid_domain',
				__( 'Please select a verified domain from Doppler Relay.', 'doppler-form' )
			);
		}

		$sanitized['from_domain'] = $requested_domain;
		$sanitized['from_email']  = $this->compose_from_email( $sanitized['from_local_part'], $sanitized['from_domain'] );

		if ( ! empty( $sanitized['from_local_part'] ) && ! $this->is_valid_from_email_alias( $sanitized['from_local_part'], $sanitized['from_domain'] ) ) {
			$has_errors         = true;
			$invalid_from_alias = true;

			add_settings_error(
				$this->option_name,
				'dplr_smtp_invalid_from_alias',
				__( 'Enter only the From email alias, without @ or domain.', 'doppler-form' )
			);
		}

		if ( empty( $sanitized['smtp_user'] ) ) {
			$has_errors = true;
			add_settings_error(
				$this->option_name,
				'dplr_smtp_missing_user',
				__( 'SMTP user is required.', 'doppler-form' )
			);
		}

		if ( empty( $sanitized['from_local_part'] ) ) {
			$has_errors = true;
			add_settings_error(
				$this->option_name,
				'dplr_smtp_missing_from_alias',
				__( 'From email alias is required.', 'doppler-form' )
			);
		}

		if ( empty( $sanitized['from_name'] ) ) {
			$has_errors = true;
			add_settings_error(
				$this->option_name,
				'dplr_smtp_missing_from_name',
				__( 'From name is required.', 'doppler-form' )
			);
		}

		if ( ! $invalid_from_alias && ( empty( $sanitized['from_email'] ) || ! is_email( $sanitized['from_email'] ) ) ) {
			$has_errors = true;
			add_settings_error(
				$this->option_name,
				'dplr_smtp_invalid_from_email',
				__( 'Please select a verified domain and enter a valid From email alias.', 'doppler-form' )
			);
		}

		if ( $has_errors ) {
			add_settings_error(
				$this->option_name,
				'dplr_smtp_settings_not_saved',
				__( 'SMTP settings were not saved. Complete all required fields and try again.', 'doppler-form' )
			);

			return $existing_settings;
		}

		add_settings_error(
			$this->option_name,
			'dplr_smtp_settings_saved',
			__( 'SMTP settings saved successfully.', 'doppler-form' ),
			'updated'
		);

		return $sanitized;
	}

	/**
	 * Returns true when SMTP is enabled and fully configured.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$settings = $this->get_settings();

		if ( empty( $settings['enabled'] ) ) {
			return false;
		}

		return empty( $this->get_configuration_errors( $settings ) );
	}

	/**
	 * Configures PHPMailer to use Doppler Relay.
	 *
	 * @param object $phpmailer PHPMailer instance.
	 */
	public function configure_phpmailer( $phpmailer ) {
		$settings   = $this->get_settings();
		$connection = $this->get_relay_connection();

		if ( ! $this->is_enabled() ) {
			return;
		}

		$phpmailer->isSMTP();
		$phpmailer->Host       = $this->get_fixed_host();
		$phpmailer->Port       = $this->get_fixed_port();
		$phpmailer->Timeout    = 15;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->SMTPSecure = strtolower( $this->get_fixed_encryption_label() );
		$phpmailer->Username   = $settings['smtp_user'];
		$phpmailer->Password   = $connection['api_key'];

		if ( property_exists( $phpmailer, 'SMTPAutoTLS' ) ) {
			$phpmailer->SMTPAutoTLS = true;
		}

		if ( defined( 'WP_DEBUG_LOG_DOPPLER_PLUGINS' ) && WP_DEBUG_LOG_DOPPLER_PLUGINS ) {
			$phpmailer->SMTPDebug   = 2;
			$phpmailer->Debugoutput = array( $this, 'log_smtp_debug' );
		}

		$from_email = $this->get_from_email( $settings );
		$from_name  = $this->get_from_name( $settings );

		if ( ! empty( $from_email ) ) {
			if ( method_exists( $phpmailer, 'setFrom' ) ) {
				$phpmailer->setFrom( $from_email, $from_name, false );
			} else {
				$phpmailer->From     = $from_email;
				$phpmailer->FromName = $from_name;
			}
		}
	}

	/**
	 * Logs SMTP debug output when plugin debug is enabled.
	 *
	 * @param string $line Debug line.
	 * @param int    $level Debug level.
	 */
	public function log_smtp_debug( $line, $level ) {
		if ( false !== stripos( $line, 'CLIENT -> SERVER:' ) ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$dir        = trailingslashit( $upload_dir['basedir'] ) . 'logs';

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		$file  = trailingslashit( $dir ) . 'smtp-debug' . gmdate( '_d_m_Y' ) . '.log';
		$entry = current_time( 'mysql' ) . ' [' . absint( $level ) . '] ' . sanitize_text_field( $line ) . PHP_EOL;

		error_log( $entry, 3, $file );
	}

	/**
	 * Forces the mail from address when SMTP is enabled.
	 *
	 * @param string $from_email Existing from email.
	 * @return string
	 */
	public function filter_mail_from( $from_email ) {
		if ( ! $this->is_enabled() ) {
			return $from_email;
		}

		return $this->get_from_email( $this->get_settings() );
	}

	/**
	 * Forces the mail from name when SMTP is enabled.
	 *
	 * @param string $from_name Existing from name.
	 * @return string
	 */
	public function filter_mail_from_name( $from_name ) {
		if ( ! $this->is_enabled() ) {
			return $from_name;
		}

		return $this->get_from_name( $this->get_settings() );
	}

	/**
	 * Returns runtime configuration errors.
	 *
	 * @param array|null $settings SMTP settings to validate.
	 * @return array
	 */
	public function get_configuration_errors( $settings = null ) {
		$settings = is_array( $settings ) ? array_merge( $this->get_default_settings(), $settings ) : $this->get_settings();
		$errors   = array();

		if ( ! $this->has_relay_connection() ) {
			$errors[] = __( 'Connect your Doppler Relay account before enabling SMTP.', 'doppler-form' );
		}

		if ( empty( $settings['smtp_user'] ) ) {
			$errors[] = __( 'SMTP user is required when SMTP is enabled.', 'doppler-form' );
		}

		if ( empty( $settings['from_name'] ) ) {
			$errors[] = __( 'From name is required when SMTP is enabled.', 'doppler-form' );
		}

		if ( empty( $settings['from_email'] ) || ! is_email( $settings['from_email'] ) ) {
			$errors[] = __( 'A valid From email address is required when SMTP is enabled.', 'doppler-form' );
		}

		return array_unique( $errors );
	}

	/**
	 * Returns the default SMTP settings.
	 *
	 * @return array
	 */
	private function get_default_settings() {
		return array(
			'enabled'         => 0,
			'smtp_user'       => '',
			'from_local_part' => '',
			'from_domain'     => '',
			'from_email'      => '',
			'from_name'       => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		);
	}

	/**
	 * Returns the default Relay connection state.
	 *
	 * @return array
	 */
	private function get_default_relay_connection() {
		return array(
			'account_name' => '',
			'api_key'      => '',
			'connected_at' => '',
		);
	}

	/**
	 * Validates the alias used to compose the From email.
	 *
	 * @param string $local_part Local part.
	 * @param string $domain Domain part.
	 * @return bool
	 */
	private function is_valid_from_email_alias( $local_part, $domain ) {
		$local_part = trim( sanitize_text_field( $local_part ) );
		$domain     = trim( sanitize_text_field( $domain ) );

		if ( empty( $local_part ) || empty( $domain ) ) {
			return false;
		}

		if ( false !== strpos( $local_part, '@' ) || preg_match( '/\s/', $local_part ) ) {
			return false;
		}

		$from_email = $local_part . '@' . $domain;

		return $from_email === sanitize_email( $from_email ) && is_email( $from_email );
	}

	/**
	 * Builds the final From email.
	 *
	 * @param string $local_part Local part.
	 * @param string $domain Domain part.
	 * @return string
	 */
	private function compose_from_email( $local_part, $domain ) {
		$local_part = trim( sanitize_text_field( $local_part ) );
		$domain     = trim( sanitize_text_field( $domain ) );

		if ( empty( $local_part ) || empty( $domain ) ) {
			return '';
		}

		return sanitize_email( $local_part . '@' . $domain );
	}

	/**
	 * Returns the effective From email.
	 *
	 * @param array $settings SMTP settings.
	 * @return string
	 */
	private function get_from_email( $settings ) {
		if ( ! empty( $settings['from_email'] ) && is_email( $settings['from_email'] ) ) {
			return $settings['from_email'];
		}

		return sanitize_email( get_option( 'admin_email' ) );
	}

	/**
	 * Returns the effective From name.
	 *
	 * @param array $settings SMTP settings.
	 * @return string
	 */
	private function get_from_name( $settings ) {
		if ( ! empty( $settings['from_name'] ) ) {
			return $settings['from_name'];
		}

		return wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	}

	/**
	 * Caches the Relay domains response.
	 *
	 * @param array $domains_data Normalized domains data.
	 */
	private function cache_relay_domains( $domains_data ) {
		set_transient( $this->relay_domains_transient, $domains_data, 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * Clears the Relay domains cache.
	 */
	private function clear_relay_domains_cache() {
		delete_transient( $this->relay_domains_transient );
	}

	/**
	 * Requests Relay domains from the API.
	 *
	 * @param string $account_name Relay account name.
	 * @param string $api_key Relay API key.
	 * @return array|WP_Error
	 */
	private function request_relay_domains( $account_name, $api_key ) {
		$url = untrailingslashit( $this->relay_api_base_url ) . '/accounts/' . rawurlencode( $account_name ) . '/domains';

		$response = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Accept'        => 'application/json',
					'Authorization' => 'token ' . $api_key,
				),
				'timeout' => 20,
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'dplr_relay_request_failed',
				__( 'Ouch! There was an error communicating with Doppler Relay. Please try again later.', 'doppler-form' )
			);
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$data          = json_decode( $response_body, true );

		if ( $response_code < 200 || $response_code >= 300 ) {
			return new WP_Error(
				'dplr_relay_invalid_credentials',
				$this->get_relay_api_error_message( $response_code, $data )
			);
		}

		if ( ! is_array( $data ) ) {
			return new WP_Error(
				'dplr_relay_invalid_response',
				__( 'Doppler Relay returned an invalid response. Please try again later.', 'doppler-form' )
			);
		}

		return $this->normalize_domains_response( $data );
	}

	/**
	 * Normalizes the domains response.
	 *
	 * @param array $data Raw response data.
	 * @return array
	 */
	private function normalize_domains_response( $data ) {
		$normalized = array(
			'default' => '',
			'domains' => array(),
		);

		if ( ! empty( $data['default'] ) ) {
			$normalized['default'] = sanitize_text_field( $data['default'] );
		}

		if ( empty( $data['domains'] ) || ! is_array( $data['domains'] ) ) {
			return $normalized;
		}

		foreach ( $data['domains'] as $domain ) {
			if ( empty( $domain['name'] ) ) {
				continue;
			}

			$normalized['domains'][] = array(
				'name'       => sanitize_text_field( $domain['name'] ),
				'dkim_ready' => $this->to_bool( isset( $domain['dkim_ready'] ) ? $domain['dkim_ready'] : false ),
				'spf_ready'  => $this->to_bool( isset( $domain['spf_ready'] ) ? $domain['spf_ready'] : false ),
				'dmarc_ready' => $this->to_bool( isset( $domain['dmarc_ready'] ) ? $domain['dmarc_ready'] : false ),
			);
		}

		return $normalized;
	}

	/**
	 * Converts mixed boolean-ish values into bool.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	private function to_bool( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}

		if ( is_numeric( $value ) ) {
			return (bool) $value;
		}

		if ( is_string( $value ) ) {
			return in_array( strtolower( trim( $value ) ), array( '1', 'true', 'yes', 'on' ), true );
		}

		return ! empty( $value );
	}

	/**
	 * Returns a readable Relay API error message.
	 *
	 * @param int   $response_code HTTP response code.
	 * @param mixed $data Decoded body.
	 * @return string
	 */
	private function get_relay_api_error_message( $response_code, $data ) {
		if ( in_array( $response_code, array( 401, 403, 404 ), true ) ) {
			return __( 'Ouch! There\'s something wrong with your API Account Name or API Key. Please, try again.', 'doppler-form' );
		}

		if ( is_array( $data ) ) {
			foreach ( array( 'detail', 'message', 'title' ) as $key ) {
				if ( ! empty( $data[ $key ] ) ) {
					return sanitize_text_field( $data[ $key ] );
				}
			}
		}

		return __( 'Ouch! There was an error communicating with Doppler Relay. Please try again later.', 'doppler-form' );
	}
}
