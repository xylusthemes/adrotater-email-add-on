<?php

/**
 * Plugin Name:       AdRotater Email Add-on
 * Plugin URI:        http://xylusthemes.com/
 * Description:       This is a add-on plugin for Add Rotate, this Plugin has functionality to send Emails to Advertisers.
 * Version:           1.0.3
 * Author:            Xylus Themes
 * Author URI:        http://xylusthemes.com/
 * License:           GPLv2 or later
 * Text Domain:       add-rotate-email-addon
 * Domain Path:       /languages
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-rotate-email-addon-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-rotate-email-addon-deactivator.php';

/**
 * The code that required for sending E-Mails.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-rotate-email-addon-send-email.php';

/** This action is documented in includes/class-plugin-name-activator.php */
register_activation_hook( __FILE__, array( 'Ad_Rotate_Email_Addon_Activator', 'activate' ) );

/** This action is documented in includes/class-plugin-name-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Ad_Rotate_Email_Addon_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-rotate-email-addon.php';

add_action( 'plugins_loaded', 'dsp_self_deactivate');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active("adrotate/adrotate.php")){
    add_action('admin_menu',array('Ad_Rotate_Email_Addon_Admin','dsp_wordpress_dashboard'),100);
    add_action('admin_init',array('Ad_Rotate_Email_Addon_Admin','dsp_enable_option'));
}

/**
 * If dependency requirements are not satisfied, self-deactivate
 */
function dsp_self_deactivate() {
    if(!is_plugin_active("adrotate/adrotate.php") && !is_plugin_active("adrotate-pro/adrotate.php")){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
        add_action( 'admin_notices','self_deactivate_notice');
    }
}
/**
 * Display an error message when the plugin deactivates itself.
 */
function self_deactivate_notice() {
	?>
    <div class="error">
	    <p>
			<?php _e("AdRotate Email Add-on has deactivated itself because AdRotate is no longer active","add-rotate-email-addon");?>
	    </p>
    </div>
<?php
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ad_rotate_email_addon() {

    $plugin = new Ad_Rotate_Email_Addon();
	$plugin->run();
}

run_ad_rotate_email_addon();


