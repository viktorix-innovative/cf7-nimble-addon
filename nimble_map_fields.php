<?php  
/*  Copyright 2013 Viktorix Innovative  (email : support@viktorixinnovative.com)

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
 
 $plugin_name = 'contact-form-7/wp-contact-form-7.php';
$is_active = is_plugin_active($plugin_name);
if ($is_active == '1') {
} else {
  echo '<br /><br /><div class="alert alert-error">Contact Form 7 plugin is required to configure this Nimble plugin. Please install and activate Contact Form 7 plugin and then return to this page.</div>';
  exit;
}
     if($_POST['bcpl_hidden'] == 'Y') 
	 {   
         $N_Fname = $_POST['N_Fname'];  
         update_option('N_Fname', $N_Fname);  
		 $CHKN_Fname = $_POST['CHKN_Fname'];
		 update_option('CHKN_Fname', $CHKN_Fname); 
		
		 $N_Lname = $_POST['N_Lname'];	
		update_option('N_Lname',$N_Lname);
		$CHKN_Lname = $_POST['CHKN_Lname'];
        update_option('CHKN_Lname',$CHKN_Lname ); 
		
		$N_title = $_POST['N_title'];
		 update_option('N_title',$N_title);
		 $CHKN_title = $_POST['CHKN_title'];
		 update_option('CHKN_title', $CHKN_title);
		 
		 $N_phone_work = $_POST['N_phone_work'];
		 update_option('N_phone_work',$N_phone_work);
		 $CHKN_phone_work = $_POST['CHKN_phone_work'];
		 update_option('CHKN_phone_work', $CHKN_phone_work); 
		 
		 $N_phone_mobile = $_POST['N_phone_mobile'];
		 update_option('N_phone_mobile',$N_phone_mobile );
		 $CHKN_phone_mobile = $_POST['CHKN_phone_mobile'];
		 update_option('CHKN_phone_mobile', $CHKN_phone_mobile); 
		 
		 $N_email  = $_POST['N_email'];
		 update_option('N_email',$N_email );
		 $CHKN_email = $_POST['CHKN_email'];
		 update_option('CHKN_email', $CHKN_email); 
		 
		 $CHKN_support = $_POST['CHKN_support'];
		 update_option('CHKN_support', $CHKN_support); 
		 
         ?>  
         <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>  
         <?php  
     }
	 else 
	 {  
         $N_Fname = get_option('N_Fname');
		 $CHKN_Fname = get_option('CHKN_Fname'); 		 
		 $N_Lname = stripslashes(get_option('N_Lname'));
		 $CHKN_Lname = get_option('CHKN_Lname'); 
		 $N_title = get_option('N_title');
		 $CHKN_title = get_option('CHKN_title'); 
		 $N_phone_work = get_option('N_phone_work');	
		 $CHKN_phone_work = get_option('CHKN_phone_work'); 		 
		 $N_phone_mobile = get_option('N_phone_mobile');
		  $CHKN_phone_mobile = get_option('CHKN_phone_mobile'); 
		 $N_email = get_option('N_email'); 
		$CHKN_email = get_option('CHKN_email'); 
		$CHKN_support = get_option('CHKN_support'); 		
     }  
 ?> 
 <link href="<?php echo get_bloginfo( 'url' ); ?>/wp-content/plugins/nimble-integration-with-cf7/style.css" rel="stylesheet" media="screen">
      <div class="wrap">  
      <?php    echo "<h2>" . __( 'Mapping Fields', 'bcpl_trdom' ) . "</h2>"; ?>  
        <form name="bcpl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
      <table><tbody>
			<tr>
				<th>Nimble Fields</th>
				<th>Contact Form 7 Fields <small>(Without square brackets)</small></th>
				<br />	
			</tr>
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_Fname" <?php if ($CHKN_Fname=='on') {echo 'checked="checked"';}  ?>  />&nbsp;&nbsp;&nbsp;  First Name </td>
						<td><input type="text" name="N_Fname" value="<?php echo $N_Fname; ?>" size="40" /></td>
					</tr>	
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_Lname" <?php if ($CHKN_Lname=='on') {echo 'checked="checked"';} ?>  />&nbsp;&nbsp;&nbsp; Last Name </td>
						<td><input type="text" name="N_Lname" value="<?php echo $N_Lname; ?>" size="40" /></td>
					
					</tr>
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_title" <?php if ($CHKN_title=='on') {echo 'checked="checked"';} ?>  />&nbsp;&nbsp;&nbsp; Title </td>
						<td><input type="text" name="N_title" value="<?php echo $N_title; ?>" size="40" /></td>
						
					</tr>
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_phone_work" <?php if ($CHKN_phone_work=='on') {echo 'checked="checked"';} ?>  />&nbsp;&nbsp;&nbsp; Phone(work) </td>
						<td><input type="text" name="N_phone_work" value="<?php echo $N_phone_work; ?>" size="40" /></td>
					
					</tr>
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_phone_mobile" <?php if ($CHKN_phone_mobile=='on') {echo 'checked="checked"';} ?>  />&nbsp;&nbsp;&nbsp; Phone(mobile) </td>
						<td><input type="text" name="N_phone_mobile" value="<?php echo $N_phone_mobile; ?>" size="40" /></td>
						
					</tr>					
					<tr>
						<td style="width:20em;padding-bottom: 8px;" ><input type="checkbox" name="CHKN_email" <?php if ($CHKN_email=='on') {echo 'checked="checked"';} ?>  />&nbsp;&nbsp;&nbsp; Email </td>
						<td><input type="text" name="N_email" value="<?php echo $N_email; ?>" size="40" /></td>
						
					</tr>					
				
		</tbody>
</table>

		  <input type="hidden" name="bcpl_hidden" value="Y" />
        <p class="submit">  
        <input type="submit" class="btn btn-primary" name="Submit" value="<?php _e('Save Changes', 'bcpl_trdom' ) ?>" />  
        </p>  
       </form>  
	   
  </div>
  
<div class="info-box">
	<a href="http://twitter.com/vxhq" class="btn" target="_blank">Follow @vxhq</a>
	<a href="http://viktorixinnovative.com/track/support-cf7-nimble" class="btn" target="_blank">Get Support</a>
	<a href="http://viktorixinnovative.com/track/bug-cf7-nimble" class="btn" target="_blank">Report a Bug</a>
	<a href="http://viktorixinnovative.com/track/rate-cf7-nimble" class="btn btn-info" target="_blank">Rate & Review</a>
	<a href="http://viktorixinnovative.com/track/donate-cf7-nimble" class="btn btn-success" target="_blank">Donate</a>
	
	</div>