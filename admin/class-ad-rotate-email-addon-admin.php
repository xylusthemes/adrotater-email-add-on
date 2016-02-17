<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Ad_Rotate_Email_Addon
 * @subpackage Ad_Rotate_Email_Addon/admin
 * @author     Dharmesh Patel <dharmesh@xylusinfo.com>
 */
class Ad_Rotate_Email_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ad_rotate_email_addon    The ID of this plugin.
	 */
	private $ad_rotate_email_addon;

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
	 * @var      string    $ad_rotate_email_addon       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $ad_rotate_email_addon, $version ) {

		$this->ad_rotate_email_addon = $ad_rotate_email_addon;
		$this->version = $version;


	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ad_Rotate_Email_Addon_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ad_Rotate_Email_Addon_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->ad_rotate_email_addon, plugin_dir_url( __FILE__ ) . 'css/ad-rotate-email-addon-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ad_Rotate_Email_Addon_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ad_Rotate_Email_Addon_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->ad_rotate_email_addon, plugin_dir_url( __FILE__ ) . 'js/ad-rotate-email-addon-admin.js', array( 'jquery' ), $this->version, false );

	}

    public static function dsp_wordpress_dashboard(){
        add_submenu_page('adrotate', 'AdRotate > '.__('Email', 'adrotate'), __('Email', 'adrotate'), 'adrotate_ad_manage', 'adrotate_email', array('Ad_Rotate_Email_Addon_Admin','dsp_ad_rotate_email'));
    }

    /*-------------------------------------------------------------
     Name:      dsp_adrotate_email

     Purpose:   Email Management page
     Receive:   -none-
     Return:    -none-
    -------------------------------------------------------------*/
    public static function dsp_ad_rotate_email(){
        include("ad-rotate-email-main.php");
    }

    public function max_state($id){
        global $wpdb;
        $max_stats = $wpdb->get_results("SELECT `maxclicks`,`maximpressions` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad`=".$id,ARRAY_A);
        return $max_stats;
    }
    public function find_user($id){
        global $wpdb;
        $id = $wpdb->get_var($wpdb->prepare("SELECT `user` FROM`".$wpdb->prefix."adrotate_linkmeta` WHERE `ad`=%d",$id));
        $users = $wpdb->get_results("SELECT `user_email`,`display_name` FROM `".$wpdb->prefix."users` WHERE `id`=".$id,ARRAY_A);
        return $users;
    }

    public static function dsp_enable_option(){
        register_setting('dsp_option_group','dsp_subject');
        register_setting('dsp_option_group','dsp_from');
        register_setting('dsp_option_group','dsp_from_email');
        register_setting('dsp_option_group','dsp_cc');
        register_setting('dsp_option_group','dsp_reply');
        register_setting('dsp_option_group','dsp_email_template');
    }
}
