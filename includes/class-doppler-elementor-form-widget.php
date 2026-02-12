<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/Form_Helper.php';

if ( class_exists( '\Elementor\Widget_Base' ) && ! class_exists( 'DPLR_Elementor_Form_Widget' ) ) {
	class DPLR_Elementor_Form_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'doppler_form';
		}

		public function get_title() {
			return esc_html__( 'Doppler Form', 'doppler-form' );
		}

		public function get_icon() {
			return 'eicon-form-horizontal';
		}

		public function get_categories() {
			return [ 'doppler-category' ];
		}

		public function get_keywords() {
			return [ 'doppler', 'form', 'subscription', 'formulario' ];
		}
		
		public function get_custom_help_url(): string {
			return esc_html__('https://help.fromdoppler.com/en/how-to-integrate-wordpress-forms-with-doppler?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form');
		}

		public function has_widget_inner_wrapper(): bool {
			return false;
		}

		protected function is_dynamic_content(): bool {
			return false;
		}

		protected function register_controls(): void {
			$this->start_controls_section(
				'doppler_form_section',
				[
					'label' => esc_html__( 'Form', 'doppler-form' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'form_id',
				[
					'label'       => esc_html__( 'Choose Form', 'doppler-form' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'options'     => $this->get_forms_options(),
					'default'     => '',
					'description' => esc_html__( 'Select the Form you want to use', 'doppler-form' ),
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$form_id = isset( $settings['form_id'] ) ? absint( $settings['form_id'] ) : 0;

			if ( ! $form_id ) {
				echo esc_html__( 'Select the Doppler Form you want to display here.', 'doppler-form' );
				return;
			}

			$form = DPLR_Form_Model::get( $form_id, true );
			if ( !$form ) {
				echo esc_html__( 'The selected form was not found.', 'doppler-form' );
				return;
			}

			$context = [
				'form'   => $form,
				'fields' => DPLR_Field_Model::getBy( [ 'form_id' => $form_id ], [ 'sort_order' ], true ),
			];

			$form_markup = DPLR_Form_Helper::generate( $context );
			$allowed_tags = DPLR_Form_Helper::get_allowed_tags();

			$output = $form_markup;
			$title = ! empty( $form->title ) ? $form->title : '';
			$output = '<div">' .
				'<h2 class="widget-title subheading heading-size-3">' . esc_html( $title ) . '</h2>' .
				$form_markup .
				'</div>';

			echo wp_kses( $output, $allowed_tags );
		}

		private function get_forms_options() {
			$options = [ '' => __( 'Select a form', 'doppler-form' ) ];
			$forms = DPLR_Form_Model::getAll();

			if ( empty( $forms ) || ! is_array( $forms ) ) {
				return $options;
			}

			foreach ( $forms as $form ) {
				if ( isset( $form->id ) && isset( $form->name ) ) {
					$options[ $form->id ] = $form->name;
				}
			}

			return $options;
		}
	}
}

