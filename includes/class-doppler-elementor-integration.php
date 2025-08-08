<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Doppler_Elementor_Integration extends \ElementorPro\Modules\Forms\Classes\Integration_Base {

    protected $doppler_service;

    public function __construct($doppler_service) {
       $this->doppler_service = $doppler_service;
    }

    public function get_name() {
        return 'doppler';
    }

    public function get_label() {
        return __( 'Doppler', 'doppler-form' );
    }

    public function register_settings_section( $widget ): void {
		$widget->start_controls_section(
			'doppler_section',
			[
				'label' => __( 'Doppler', 'doppler-form' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'doppler_list',
			[
				'label' => __( 'List', 'doppler-form' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_user_lists(),
				'render_type' => 'none',
			]
		);

        $this->register_fields_map_control( $widget );

		$widget->end_controls_section();

	}

    public function on_export( $element ) {
		unset(
			$element['settings']['doppler_list'],
			$element['settings']['doppler_fields_map']
		);

		return $element;
	}

    public function run( $record, $ajax_handler ) {
        $list_id = $record->get_form_settings('doppler_list');
        $subscriber = $this->map_fields($record);
        
        $subscriber_resource = $this->doppler_service->getResource('subscribers');
        $response = $subscriber_resource->addSubscriber($list_id, $subscriber);

        if (is_array($response) && isset($response['response']['code']) && $response['response']['code'] >= 400) {
            $error_body = json_decode($response['body'], true);
            $error = '';

            if (json_last_error() === JSON_ERROR_NONE && !empty($error_body['errors'])) {
                $error_details = array_column($error_body['errors'], 'detail');
                $error = implode(' - ', $error_details);
            }
            if (empty($error)) {
                $error = 'An error occurred while submitting the form.';
            }

            $code = $response['response']['code'];
            throw new \Exception( "HTTP {$code} - {$error}" );
        }
    }

    protected function get_fields_map_control_options() {
		return [
			'condition' => [
				'doppler_list!' => '',
			],
		];
	}

    private function get_user_lists() {
        $lists_resource = $this->doppler_service->getResource('lists');
        $lists = $lists_resource->getAllLists();

        if (is_array($lists) && isset($lists[0]) && is_array($lists[0])) {
            $lists = $lists[0];
        }

        return array_reduce($lists, function($carry, $list) {
            $carry[$list->listId] = $list->name;
            return $carry;
        }, []);
    }

    /**
     * Maps Elementor's form record object to the array structure required by Doppler's subscriber.
     *
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $record
     * @return array
     */
    private function map_fields( $record ) {
        $output = [
            'email' => '',
            'fields' => [],
        ];
        $fields = $record->get( 'fields' );
        $map = $record->get_form_settings( 'doppler_fields_map' );

        foreach ( $map as $map_item ) {
            if ( ! isset( $fields[ $map_item['local_id'] ] ) ) {
                continue;
            }
            $value = $fields[ $map_item['local_id'] ]['value'] ?? '';

            $sanitized_value = $this->get_sanitized_field_value($map_item['remote_type'], $value);
            if (is_null($sanitized_value) || $sanitized_value === '') {
                continue;
            }

            if ( strtoupper($map_item['remote_label']) === 'EMAIL' ) {
                $output['email'] = $sanitized_value;
            } else {
                $output['fields'][] = [
                    'name'  => $map_item['remote_label'],
                    'value' => $sanitized_value,
                ];
            }
        }

        return $output;
    }

    private function get_sanitized_field_value($type, $value) {
        switch ($type) {
            case 'date':
                return $this->sanitize_date($value);
            case 'acceptance':
                return $this->sanitize_boolean($value);
            case 'tel':
                return $this->sanitize_phone($value);
            default:
                return $value;
        }
    }

    private function sanitize_date($value) {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if ($date) {
            return $date->format('Y-m-d');
        }

        $date = DateTime::createFromFormat('Ymd', $value);
        if ($date) {
            return $date->format('Y-m-d');
        }

        $date = DateTime::createFromFormat('m/d/Y', $value);
        if ($date) {
            return $date->format('Y-m-d');
        }
        
        return null;
    }

    private function sanitize_boolean($value) {
        if ($value === '') {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    private function sanitize_phone($value) {
        $sanitized_value = preg_replace('/[()\-\s]/', '', $value);

        if (preg_match('/^\+?\d+$/', $sanitized_value)) {
            if (strpos($sanitized_value, '+') !== 0) {
                return '+' . $sanitized_value;
            }

            return $sanitized_value;
        }

        return null;
    }
}