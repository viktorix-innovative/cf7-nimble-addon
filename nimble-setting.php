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
{	       
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
?>