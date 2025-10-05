<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$option_name = 'dplr_settings';
delete_option($option_name);
delete_site_option($option_name);

$option_name = 'widget_dplr_form_widget';
delete_option($option_name);
delete_site_option($option_name);

$option_name = 'dplr_version';
delete_option($option_name);
delete_site_option($option_name);

$option_name = 'dplr_2_0_updated';
delete_option($option_name);
delete_site_option($option_name);

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}dplr_field_settings, {$wpdb->prefix}dplr_field, {$wpdb->prefix}dplr_form_settings, {$wpdb->prefix}dplr_form_events, {$wpdb->prefix}dplr_form" );
