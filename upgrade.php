<?php
$rb_interactivemap_options_arr = get_option('rb_interactivemap_options');

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/*
// Upgrade from 1.0
if ($rb_interactivemap_options_arr["databaseVersion"] == "1.0") {

	// DB changes needed!
	$results = $wpdb->query("ALTER TABLE " . table_interactivemap_location . " ADD MapLocationHours VARCHAR(255)");

	// Updating version number!
	$rb_interactivemap_options_arr = array("databaseVersion" => "1.1");
	update_option('rb_interactivemap_options', $rb_interactivemap_options_arr);
	
} 
*/
?>