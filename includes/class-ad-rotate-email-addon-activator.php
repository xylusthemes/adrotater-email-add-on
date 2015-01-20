<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ad_Rotate_Email_Addon
 * @subpackage Ad_Rotate_Email_Addon/includes
 * @author     Dharmesh Patel <dharmesh@xylusinfo.com>
 */

class Ad_Rotate_Email_Addon_Activator{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

     public function activate() {
         /**
          * Set Options for Email template and Subject
          */
         $mail = '<h1>Your Company_name advertising report</h1>';
         $mail .='<p>Dear {aduser},</p>';
         $mail .='<p>Thank you for advertising in the Company_name. Last month, your ad received {adimpression} impressions and {adclick} clicks. That is an excellent result and a lot of exposure. I have attached a report that shows your daily views and clicks for the month.</p>';
         $mail .='{adreport}';
         $mail .='<p>Thanks and Regards,</p>';
         $mail .='<p>Your Company_name.</p>';

         $subject ='Company_name Ad Report';
         $email   = get_option( 'admin_email' );

         update_option( 'dsp_email_template', $mail);
         update_option( 'dsp_subject', $subject);
         update_option( 'dsp_from', "Your Company Name");
         update_option( 'dsp_from_email', $email);
         update_option( 'dsp_cc', $email);
         update_option( 'dsp_reply', $email);

	 }
}

