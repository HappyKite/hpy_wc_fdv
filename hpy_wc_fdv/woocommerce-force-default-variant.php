<?php
/*
Plugin Name: WooCommerce Force Default Variant
Plugin URI: http://www.happykite.co.uk
Description: Removes the standard WooCommerce 'Select an Option' from variant Drop Downs and the option to Clear Selection.
Author: HappyKite
Author URI: http://www.happykite.co.uk/
Version: 0.1
*/

/*
 This file is part of wooCommerce-force-default.
 wooCommerce-force-default is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 wooCommerce-force-default is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with wooCommerce-force-default.  If not, see <http://www.gnu.org/licenses/>.
 */

/***************************
* includes
***************************/

include( dirname(__FILE__).'/includes/variations.php' ); //Variant code.
include( dirname(__FILE__).'/includes/settings.php' ); //Settings Area

/***********************************************
* check for WooCommerce Templates within Plugin
************************************************/

add_filter( 'woocommerce_locate_template', 'hpy_fdv_woocommerce_locate_template', 10, 3 );
function hpy_fdv_woocommerce_locate_template( $template, $template_name, $template_path ) {
  global $woocommerce;
 
  $_template = $template;

  if ( ! $template_path ) $template_path = $woocommerce->template_url;
  $plugin_path  = myplugin_plugin_path() . '/woocommerce/';
 
  if ( file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;
  
  if ( ! $template ) {
      $template = locate_template(
      array(
        $template_path . $template_name,
        $template_name
      )
    );
  }

  // Use default template
  if ( ! $template )
    $template = $_template;

  // Return what we found
  return $template;
}

function myplugin_plugin_path() {
  // gets the absolute path to this plugin directory
  return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

/***************************
* Get Current WC Version.
***************************/

function hpy_check_wc_version() {
  //Checking if get_plugins is available.
  if( !function_exists( 'get_plugins' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  }

  //Adding required variables
  $woo_folder = get_plugins( '/' . 'woocommerce' );
  $woo_file = 'woocommerce.php';

  //Checking if Version number is set.
  if( isset( $woo_folder[$woo_file]['Version'] ) ) {
    return $woo_folder[$woo_file]['Version'];
  } else {
    return NULL;
  }

}


/***************************
* Activation Notice
***************************/

if ( !class_exists( 'WooCommerce' ) ) {

  $woo_version = hpy_check_wc_version();

  if ( $woo_version < '4.2.10' ) {
    register_activation_hook(__FILE__, 'hpy_plugin_activation');
    function hpy_plugin_activation() {
      $url = admin_url('tools.php?page=uuc-options');
      $notices= get_option('my_plugin_deferred_admin_notices', array());
      $notices[]= "Attention: WooCommerce Force Default Variant requires at least WooCommerce Version 4.2.10, you currently have " . $woo_version . ". Please update WooCommerce before activating this plugin.";
      update_option('my_plugin_deferred_admin_notices', $notices);
    }

    deactivate_plugins( basename( __FILE__ ) );
  }

  add_action('admin_notices', 'hpy_plugin_admin_notices');
  function hpy_plugin_admin_notices() {
    if ($notices= get_option('my_plugin_deferred_admin_notices')) {
      foreach ($notices as $notice) {
        echo "<div class='updated'><p>$notice</p></div>";
      }
      delete_option('my_plugin_deferred_admin_notices');
    }
  }

  register_deactivation_hook(__FILE__, 'hpy_plugin_deactivation');
  function hpy_plugin_deactivation() {
    delete_option('my_plugin_version'); 
    delete_option('my_plugin_deferred_admin_notices');
  }

}


/***************************
* Adding Plugin Settings Link
***************************/

function your_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=wc-settings&tab=products&section=hpy_variants">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

