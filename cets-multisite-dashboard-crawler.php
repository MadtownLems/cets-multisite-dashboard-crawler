<?php
/*

Plugin Name: MultiSite Dashboard Crawler (CETS)
Description: Lets network admins crawl the dashboard of each site in multisite
Version: 0.1
Network: true
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

add_action( 'network_admin_menu', 'multisite_dashboard_crawler_network_admin_menu');

function multisite_dashboard_crawler_network_admin_menu() {
	add_submenu_page('settings.php', 'Crawl Dashboards', 'Crawl Dashboards', 'manage_network', 'multisite_dashboard_crawler', 'multisite_dashboard_crawler_network_admin_page');
}


function multisite_dashboard_crawler_network_admin_page() {

	echo "<div class='wrap'><h2>MultiSite Dashboard Crawler</h2>";

	if (isset($_REQUEST['multisite_dashboard_crawler_done'])) {
		echo "<p>All Done!</p>";
	} else {
		echo "<p>This tool will hit the dashboard of every site on the network, one at a time, and bring you back here when it's done.</p>";
		$first_id = _multisite_dashboard_crawler_get_first_site_id();

		if ($first_id) {
			switch_to_blog($first_id);
			$first_url = admin_url();
			echo "<a href='{$first_url}?multisite_dashboard_crawler=1'>$first_url</a>";
			restore_current_blog();
		} else {
			echo "<strong>Error:</strong> Couldn't find first site URL.";
		}
	}
	echo "</div>";
}




add_action('admin_footer', 'multisite_dashboard_crawler_single_admin_footer');

function multisite_dashboard_crawler_single_admin_footer() {
	if (current_user_can('manage_network') && isset($_REQUEST['multisite_dashboard_crawler']) && $_REQUEST['multisite_dashboard_crawler'] == 1) {

		$next_id = _multisite_dashboard_crawler_get_next_site_id();
		if ($next_id > 0) {
			switch_to_blog($next_id);
			$next_url = admin_url() . "?multisite_dashboard_crawler=1";
			restore_current_blog();
		} else {
			$next_url = network_admin_url("/settings.php?page=multisite_dashboard_crawler&multisite_dashboard_crawler_done=1");
		}
		echo "<script>window.location='{$next_url}'</script>";
	}
}


function _multisite_dashboard_crawler_get_first_site_id() {
	
	global $wpdb;
	$sql = "SELECT MIN(blog_id) FROM wp_blogs WHERE archived = 0 AND deleted = 0";
	$result = (int) $wpdb->get_var($sql);

	if ($result && is_integer($result)) {
		return $result;
	} 

	return 0;
}


function _multisite_dashboard_crawler_get_next_site_id() {
	global $blog_id;
	global $wpdb;

	// get next highest blog id
	$sql = "SELECT MIN(blog_id) FROM wp_blogs WHERE archived = 0 AND deleted = 0 AND blog_id > {$blog_id}";
	
	$result = (int) $wpdb->get_var($sql);

	if ($result && is_integer($result)) {
		return $result;
	}

	return 0;
}
