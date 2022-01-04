<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist\Model;

use Directorist\Helper;
use ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Account {

	protected static $instance = null;

	private function __construct() {

	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function render_shortcode_login( $atts = [] ) {
		if ( is_user_logged_in() ) {

			do_action( 'atbdp_show_flush_messages' );

			$error_message = sprintf( __( 'Login page is not for logged-in user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_login_page_loggedIn_msg', $error_message ) );
			return ob_get_clean();
		}

		ob_start();
		if ( ! empty( $atts['shortcode'] ) ) { Helper::add_shortcode_comment( $atts['shortcode'] ); }
		echo Helper::get_template_contents( 'account/login' );

		return ob_get_clean();
	}

	public function render_shortcode_registration( $atts ) {
		if ( ! is_user_logged_in() ) {
			$atts = shortcode_atts( array(
				'user_type'			  => '',
			), $atts );

			$user_type = ! empty( $atts['user_type'] ) ? $atts['user_type'] : '';
			$user_type = ! empty( $_REQUEST['user_type'] ) ? $_REQUEST['user_type'] : $user_type;

			$args = array(
				'parent'               => 0,
				'container_fluid'      => is_directoria_active() ? 'container' : 'container-fluid',
				'username'             => get_directorist_option( 'reg_username', __( 'Username', 'directorist' ) ),
				'password'             => get_directorist_option( 'reg_password', __( 'Password', 'directorist' ) ),
				'display_password_reg' => get_directorist_option( 'display_password_reg', 1 ),
				'require_password'     => get_directorist_option( 'require_password_reg', 1 ),
				'email'                => get_directorist_option( 'reg_email', __( 'Email', 'directorist' ) ),
				'display_website'      => get_directorist_option( 'display_website_reg', 0 ),
				'website'              => get_directorist_option( 'reg_website', __( 'Website', 'directorist' ) ),
				'require_website'      => get_directorist_option( 'require_website_reg', 0 ),
				'display_fname'        => get_directorist_option( 'display_fname_reg', 0 ),
				'first_name'           => get_directorist_option( 'reg_fname', __( 'First Name', 'directorist' ) ),
				'require_fname'        => get_directorist_option( 'require_fname_reg', 0 ),
				'display_lname'        => get_directorist_option( 'display_lname_reg', 0 ),
				'last_name'            => get_directorist_option( 'reg_lname', __( 'Last Name', 'directorist' ) ),
				'require_lname'        => get_directorist_option( 'require_lname_reg', 0 ),
				'display_bio'          => get_directorist_option( 'display_bio_reg', 0 ),
				'bio'                  => get_directorist_option( 'reg_bio', __( 'About/bio', 'directorist' ) ),
				'require_bio'          => get_directorist_option( 'require_bio_reg', 0 ),
				'reg_signup'           => get_directorist_option( 'reg_signup', __( 'Sign Up', 'directorist' ) ),
				'display_login'        => get_directorist_option( 'display_login', 1 ),
				'login_text'           => get_directorist_option( 'login_text', __( 'Already have an account? Please login', 'directorist' ) ),
				'login_url'            => ATBDP_Permalink::get_login_page_link(),
				'log_linkingmsg'       => get_directorist_option( 'log_linkingmsg', __( 'here', 'directorist' ) ),
				'terms_label'          => get_directorist_option( 'regi_terms_label', __( 'I agree with all', 'directorist' ) ),
				'terms_label_link'     => get_directorist_option( 'regi_terms_label_link', __( 'terms & conditions', 'directorist' ) ),
				't_C_page_link'        => ATBDP_Permalink::get_terms_and_conditions_page_url(),
				'privacy_page_link'    => ATBDP_Permalink::get_privacy_policy_page_url(),
				'privacy_label'        => get_directorist_option( 'registration_privacy_label', __( 'I agree to the', 'directorist' ) ),
				'privacy_label_link'   => get_directorist_option( 'registration_privacy_label_link', __( 'Privacy & Policy', 'directorist' ) ),
				'user_type'			   => $user_type,
				'author_checked'	   => ( 'general' != $user_type ) ? 'checked' : '',
				'general_checked'	   => ( 'general' == $user_type ) ? 'checked' : ''
			);

			ob_start();
			if ( ! empty( $atts['shortcode'] ) ) { Helper::add_shortcode_comment( $atts['shortcode'] ); }
			echo Helper::get_template_contents( 'account/registration', $args );

			return ob_get_clean();
		}
		else {
			$error_message = sprintf( __( 'Registration page is only for unregistered user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_registration_page_registered_msg', $error_message ) );
			return ob_get_clean();
		}

		return '';
	}

}