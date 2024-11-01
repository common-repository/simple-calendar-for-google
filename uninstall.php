<?php
/**
 * Uninstall
 *
 * @package Simple Calendar for Google
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$option_name1 = 'scalg_api';
$option_name2 = 'scalg_ids';
$option_name3 = 'scalg_color';
$option_name4 = 'scalg_bgcolor';
$option_name5 = 'scalg_duplimit';
$option_name6 = 'scalg_dupcolor';
$option_name7 = 'scalg_alttext';

/* For Single site */
if ( ! is_multisite() ) {
	delete_option( 'scalg_admin' );
	delete_option( 'scalg_css' );
	$blogusers = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach ( $blogusers as $user ) {
		delete_user_option( $user->ID, $option_name1, false );
		delete_user_option( $user->ID, $option_name2, false );
		delete_user_option( $user->ID, $option_name3, false );
		delete_user_option( $user->ID, $option_name4, false );
		delete_user_option( $user->ID, $option_name5, false );
		delete_user_option( $user->ID, $option_name6, false );
		delete_user_option( $user->ID, $option_name7, false );
	}
} else {
	/* For Multisite */
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		delete_option( 'scalg_admin' );
		delete_option( 'scalg_css' );
		$blogusers = get_users(
			array(
				'blog_id' => $blogid,
				'fields' => array( 'ID' ),
			)
		);
		foreach ( $blogusers as $user ) {
			delete_user_option( $user->ID, $option_name1, false );
			delete_user_option( $user->ID, $option_name2, false );
			delete_user_option( $user->ID, $option_name3, false );
			delete_user_option( $user->ID, $option_name4, false );
			delete_user_option( $user->ID, $option_name5, false );
			delete_user_option( $user->ID, $option_name6, false );
			delete_user_option( $user->ID, $option_name7, false );
		}
	}
	switch_to_blog( $original_blog_id );

	/* For site options. */
	delete_site_option( 'scalg_admin' );
	delete_site_option( 'scalg_css' );

}
