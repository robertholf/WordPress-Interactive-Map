<?php 
/*
  Plugin Name: RB Interactive Map
  Plugin URI: http://interactivemap.rbplugin.com/
  Description: An interactive map plugin for WordPress.
  Author: Rob Bertholf
  Author URI: http://rob.bertholf.com/
  Version: 1.0
*/
if (!session_id())
session_start();

if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8 ?>
<div class="error" style="margin-top:30px;">
<p>This plugin requires WordPress version 2.8 or newer.</p>
</div>
<?php
return;
}
// *************************************************************************************************** //

// Plugin configuration variables
define("rb_interactivemap_version", "1.0");
if (!defined("rb_interactivemap_URL"))
	define("rb_interactivemap_URL", get_option("siteurl")."/wp-content/plugins/rb-interactivemap/");
if (!defined('rb_interactivemap_path'))
	define("rb_interactivemap_path", "/wp-content/plugins/rb-interactivemap/");

// SetTable Names
if (!defined("table_interactivemap_location"))
	define("table_interactivemap_location", "rb_interactivemap_location");
if (!defined("table_interactivemap_maptype"))
	define("table_interactivemap_maptype", "rb_interactivemap_maptype");

// Call default functions
include_once(dirname(__FILE__).'/functions.php');

// Does it need a diaper change?
include_once(dirname(__FILE__).'/upgrade.php');

// *************************************************************************************************** //

// Creating tables on plugin activation
function rb_interactivemap_install() {
	// Required for all WordPress database manipulations
	global $wpdb;
	
	// Set Options
	$rb_interactivemap_options_arr = array(
		"databaseVersion" => rb_interactivemap_version
		);
	// Update the options in the database
	update_option('rb_interactivemap_options', $rb_interactivemap_options_arr);
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// Map Locations / Coordinates 
	if ($wpdb->get_var("show tables like '". table_interactivemap_location ."'") != table_interactivemap_location) { // No, Create
		$sqlBusiness = "CREATE TABLE ".table_interactivemap_location." (
			MapLocationID BIGINT(20) NOT NULL AUTO_INCREMENT,
			MapLocationTitle VARCHAR(255),
			MapLocationText TEXT,
			MapLocationPhone VARCHAR(255),
			MapLocationHours VARCHAR(255),
			MapLocationTags TEXT,
			MapTypeID INT(10) NOT NULL DEFAULT '0',
			MapLocationFloor INT(10) NOT NULL DEFAULT '0',
			MapLocationCoords TEXT,
			MapLocationActive INT(10) NOT NULL DEFAULT '1',
			PRIMARY KEY (MapLocationID)
			);";
		dbDelta($sqlBusiness);
	}

	// Types
	if ($wpdb->get_var("show tables like '". table_interactivemap_maptype ."'") != table_interactivemap_maptype) { // No, Create
		$sqlMenu = "CREATE TABLE ".table_interactivemap_maptype." (
			MapTypeID INT(10) NOT NULL AUTO_INCREMENT,
			MapTypeTitle VARCHAR(255),
			MapTypeText TEXT,
			MapTypeImage VARCHAR(255),
			MapTypeActive INT(10) NOT NULL DEFAULT '1',
			PRIMARY KEY (MapTypeID)
			);";
		dbDelta($sqlMenu);
	}

}
//Activate Install Hook
register_activation_hook(__FILE__,'rb_interactivemap_install');

// *************************************************************************************************** //

// Action hook to register our option settings
add_action('admin_init', 'rb_interactivemap_register_settings');

// Register our Array of settings
function rb_interactivemap_register_settings() {
	register_setting('rb_interactivemap-settings-group', 'rb_interactivemap_options');
}

// *************************************************************************************************** //

//Activate Menu Hook
add_action('admin_menu','set_interactivemap_menu');

//Create Admin Menu
function set_interactivemap_menu(){
	add_menu_page("Interactive Map","Interactive Map",1,"rb_interactivemap_menu","interactivemap_dashboard","div");
	add_submenu_page("rb_interactivemap_menu","Overview","Overview",1,"rb_interactivemap_menu","interactivemap_dashboard");
	add_submenu_page("rb_interactivemap_menu","Manage Locations","Manage Locations",7,"interactivemap_locations","interactivemap_locations");
	add_submenu_page("rb_interactivemap_menu","Settings","Settings",7,"interactivemap_settings","interactivemap_settings");
}

//Pages
function interactivemap_dashboard(){
	include_once('admin/overview.php');
}
function interactivemap_locations(){
	include_once('admin/locations.php');
}
function interactivemap_settings(){
	include_once('admin/settings.php');
}

// *************************************************************************************************** //

//Uninstall
function interactivemap_uninstall() {
	// Define Tables
	global $wpdb;

	// Drop the tables
	$wpdb->query("DROP TABLE " . table_interactivemap_location);
	$wpdb->query("DROP TABLE " . table_interactivemap_maptype);

	// Final Cleanup
	$thepluginfile="rb-interactivemap/rb-interactivemap.php";
	$current = get_settings('active_plugins');
	array_splice($current, array_search( $thepluginfile, $current), 1 );
	update_option('active_plugins', $current);
	do_action('deactivate_' . $thepluginfile );

	echo "<div style=\"padding:50px;font-weight:bold;\"><p>Almost done...</p><h1>One More Step</h1><a href=\"plugins.php?deactivate=true\">Please click here to complete the uninstallation process</a></h1></div>";
	die;
}

// *************************************************************************************************** //
?>