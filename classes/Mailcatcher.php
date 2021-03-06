<?php

namespace AUSWEB\Plugins\Mailcatcher;

if (!defined('ABSPATH')) die(header('HTTP/1.0 403 Forbidden'));

/**
 * Mailcatcher class.
 *
 * @since 1.0
 * @package Mailcatcher
 */

class Mailcatcher {

	/**
	 * Send in arguments for mailer.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $config Configuration values. These will take presedence over $defaults.
	 */

	public function __construct(array $config = array()) {

		// Setup our defaults

		$defaults = array(
			"from"	   => "local@local.dev",
			"fromname" => "local",
			"host"     => "127.0.0.1",
			"port"     => "1025",
			"smtpauth" => false
		);

		$config = wp_parse_args($config, $defaults);
		
		// Setup mailer

		$this->setupMail($config);

	}

	/**
	 * Setup mailer.
	 *
	 * @since 1.0
	 * @access private
	 * @param array $config Parsed configuration values.
	 */

	private function setupMail(array $config)
	{
		add_action('phpmailer_init', function($phpmailer) use($config) {

			extract($config, EXTR_SKIP);

			$phpmailer->From     = $from;
			$phpmailer->FromName = $fromname;
			$phpmailer->Sender   = $phpmailer->From;
			$phpmailer->Host     = $host;
			$phpmailer->Port     = (int) $port;
			$phpmailer->SMTPAuth = (boolean) $smtpauth;
			$phpmailer->isSMTP();

		});
	}

	/**
	 * Send a test email.
	 *
	 * @since 1.0
	 * @access public
	 */

	public function sendMail()
	{
		add_action('plugins_loaded', function(){

			$to 		= "local@local.dev";
			$subject 	= "Subject Generated by Mailcatcher";
			$message 	= "Body Generated by Mailcatcher for WordPress";

			\wp_mail($to, $subject, $message);

		});
	}

}