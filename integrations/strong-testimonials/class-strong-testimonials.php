<?php

defined( 'ABSPATH' ) or exit;

/**
 * Class MC4WP_Strong_Testimonials_Integration
 * @ignore
 */
class MC4WP_Strong_Testimonials_Integration extends MC4WP_Integration {

	/**
	 * @var string
	 */
	public $name = "Strong Testimonials";

	/**
	 * @var string
	 */
	public $description = "Subscribes people from a Strong Testimonials form.";

	/**
	 * Add hooks
	 */
	public function add_hooks() {

		if( ! $this->options['implicit'] ) {
			add_action( 'wpmtst_mc4wp', array( $this, 'output_checkbox' ), 90 );
		}

		// hooks for checking if we should subscribe the commenter
		add_action( 'wpmtst_form_submission', array( $this, 'subscribe_from_strong_testimonials' ), 40 );
	}

	/**
	 * Grabs data from WP Comment Form
	 *
	 * @param int    $comment_id
	 * @param string $comment_approved
	 *
	 * @return bool|string
	 */
	public function subscribe_from_strong_testimonials() {

		// was sign-up checkbox checked?
		if ( ! $this->triggered() ) {
			return false;
		}

		$data = array(
			'EMAIL' => $_POST['email'],
			'NAME' => ! empty( $_POST['client_name'] ) ? $_POST['client_name'] : '',
		);

		return $this->subscribe( $data );
	}

	/**
	 * @return bool
	 */
	public function is_installed() {
		return class_exists( 'Strong_Testimonials', false );
	}

	/**
	 * @since 3.0
	 * @return array
	 */
	public function get_ui_elements() {
		return array_diff( parent::get_ui_elements(), array( 'enabled', 'implicit' ) );
	}

}