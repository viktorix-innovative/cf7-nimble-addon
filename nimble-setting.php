<?php 
/*
Plugin Name:  Contact Form 7 - Nimble Addon
Plugin URI: http://viktorixinnovative.com/app/contact-form-7-nimble-addon/
Description: This plugin integrates Contact Form 7 with Nimble CRM to automatically import leads on form submission.
Author: Viktorix Innovative
Author URI: http://viktorixinnovative.com/
Version: 0.1
License: GPL2 or Later

Copyright 2013 Viktorix Innovative  (email : support@viktorixinnovative.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

add_action('admin_menu', 'nimble_add_pages');

function nimble_add_pages()
{

     add_menu_page('Nimble', 'Nimble', 'edit_pages', 'nimble', 'nimble_main_admin', plugins_url('nimble-integration-with-cf7/images/nimble.png'), 25);
	 add_submenu_page( 'nimble', 'Mapping Fields', 'Mapping Fields', 'manage_options', 'mapping-fields', 'nimble_mapping_fields');
}




function nimble_mapping_fields() 
{	        $data = json_encode(array(
            "fields" => array(
                'first name' => array(
                    array(
                        'value' => 'firstname',
                        'modifier' => ''
                    )
                ),
                'last name' => array(
                    array(
                        'value' => 'lastname',
                        'modifier' => ''
                    )
                ),
                'title' => array(
                    array(
                        'value' => 'title',
                        'modifier' => ''
                    )
                ),
				
                'phone' => array(
                    array(
                        'value' => '334343433',
                        'modifier' => 'work'
                    ),
		    array(
                        'value' => '121212112',
                        'modifier' => 'mobile'
                    )
                ),				
                'email' => array(
                    array(
                        'value' => 'emailaddress',
                        'modifier' => 'personal'
                    )
                )
            ),
            "type" => 'person'
        ));

	
	
	include('nimble_map_fields.php'); 
}

function nimble_main_admin() 
{
	
	if(!isset($_GET['action']))
	{
	   include('nimble_admin_setting.php'); 
	}
}

function contact7_nimble($cfdata)
{


$formtitle = $cfdata->title;


$formdata = $cfdata->posted_data;

 require_once('api_nimble.php');

 $nimble = new NimbleAPI();

try {
         $N_Fname = get_option('N_Fname');		 
		 $N_Lname = stripslashes(get_option('N_Lname'));
		 $N_title = get_option('N_title');
		 $N_phone_work = get_option('N_phone_work');			 
		 $N_phone_mobile = get_option('N_phone_mobile');
		 $N_email = get_option('N_email'); 
  
	$access_token = 	$nimble->nimble_refreshtoken_get_access_token();	
	update_option('nimble_access_token',$access_token);
	$counter = get_option('nimble_refresh_token_counter');
	$counter = $counter + 1;
	update_option('nimble_refresh_token_counter',$counter);
   
	 $nimble->nimble_add_contact($formdata[$N_Fname], $formdata[$N_Lname], $formdata[$N_email],$formdata[$N_phone_work],$formdata[$N_phone_mobile],$formdata[$N_title]);

} catch(AWeberAPIException $exc) {
  
}
}

 add_action('wpcf7_mail_sent', 'contact7_nimble', 1);

 
 /**
* PressTrends Plugin API
*/
function presstrends_plugin() {

		// PressTrends Account API Key
		$api_key = 'ezd1mxaiad0aj2saeuj2soea9q00njro0zri';
		$auth    = 'keovadix2ewqpkbxssqgsbjzuo4co3j7k';

		// Start of Metrics
		global $wpdb;
		$data = get_transient( 'presstrends_cache_data' );
		if ( !$data || $data == '' ) {
			$api_base = 'http://api.presstrends.io/index.php/api/pluginsites/update/auth/';
			$url      = $api_base . $auth . '/api/' . $api_key . '/';

			$count_posts    = wp_count_posts();
			$count_pages    = wp_count_posts( 'page' );
			$comments_count = wp_count_comments();

			// wp_get_theme was introduced in 3.4, for compatibility with older versions, let's do a workaround for now.
			if ( function_exists( 'wp_get_theme' ) ) {
				$theme_data = wp_get_theme();
				$theme_name = urlencode( $theme_data->Name );
			} else {
				$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
				$theme_name = $theme_data['Name'];
			}

			$plugin_name = '&';
			foreach ( get_plugins() as $plugin_info ) {
				$plugin_name .= $plugin_info['Name'] . '&';
			}
			// CHANGE __FILE__ PATH IF LOCATED OUTSIDE MAIN PLUGIN FILE
			$plugin_data         = get_plugin_data( __FILE__ );
			$posts_with_comments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' AND comment_count > 0" );
			$data                = array(
				'url'             => stripslashes( str_replace( array( 'http://', '/', ':' ), '', site_url() ) ),
				'posts'           => $count_posts->publish,
				'pages'           => $count_pages->publish,
				'comments'        => $comments_count->total_comments,
				'approved'        => $comments_count->approved,
				'spam'            => $comments_count->spam,
				'pingbacks'       => $wpdb->get_var( "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'" ),
				'post_conversion' => ( $count_posts->publish > 0 && $posts_with_comments > 0 ) ? number_format( ( $posts_with_comments / $count_posts->publish ) * 100, 0, '.', '' ) : 0,
				'theme_version'   => $plugin_data['Version'],
				'theme_name'      => $theme_name,
				'site_name'       => str_replace( ' ', '', get_bloginfo( 'name' ) ),
				'plugins'         => count( get_option( 'active_plugins' ) ),
				'plugin'          => urlencode( $plugin_name ),
				'wpversion'       => get_bloginfo( 'version' ),
			);

			foreach ( $data as $k => $v ) {
				$url .= $k . '/' . $v . '/';
			}
			wp_remote_get( $url );
			set_transient( 'presstrends_cache_data', $data, 60 * 60 * 24 );
		}
	}

	$CHKN_support = get_option('CHKN_support');
	if ($CHKN_support=='on') {
// PressTrends WordPress Action
add_action('admin_init', 'presstrends_plugin');
}
?>