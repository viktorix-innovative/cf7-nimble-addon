<?php
/*
 * Nimble CRM API Class v0.1
 * http://viktorixinnovative.com/
 * 
 * Copyright 2013 Viktorix Innovative  (email : support@viktorixinnovative.com)

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
 * 
*/


class NimbleAPI
{

  public function __construct()
  {
    $this->config = array(
      'api_key' => get_option('nimble_client_id'),
      'secret_key' => get_option('nimble_client_secret'),
      'redirect_uri' => get_option('nimble_redirect_uri')
    );
  }
 
 public function nimble_get_auth_code()
{
$handle = 'https://api.nimble.com/oauth/authorize?client_id='.$this->config['api_key'].'&redirect_uri='.$this->config['redirect_uri'].'&response_type=code';
header("Location: $handle");
}
 
 public function nimble_get_access_token($code)
{
    $authen_code = $code;
    $url         = 'https://api.nimble.com/oauth/token';
    $method      = 'POST';
    $data        = 'client_id='.$this->config['api_key'].'&client_secret='.$this->config['secret_key'].'&code=' . $authen_code . '&grant_type=authorization_code';
    
    $response_data = $this->nimble_request($url, $method, $data);
    
    return $response_data;
}

 public function nimble_refreshtoken_get_access_token()
{
    $request_token = get_option('nimble_refresh_token');
    $url           = 'https://api.nimble.com/oauth/token';
    $method        = 'POST';
    $data          = 'client_id='.$this->config['api_key'].'&client_secret='.$this->config['secret_key'].'&refresh_token=' . $request_token . '&grant_type=refresh_token';
    
    $response_data = $this->nimble_request($url, $method, $data);
    
    return $response_data[1]->access_token;
    
}

  
 public function nimble_search_contact($emailaddress)
{
    $access_token =  get_option('nimble_access_token');
    $url    = 'https://api.nimble.com/api/v1/contacts/list?access_token=' . $access_token . '&query={"email":{"is":"' . $emailaddress . '"}}&fields=first%20name';
    $method = 'GET';
    
    $response_data = $this->nimble_request($url, $method, $data);
    
    if (($response_data[0] == 401) && ($response_data[1]->Error == 'Access Token is invalid or expired.')) {
        $access_token = $this->nimble_refreshtoken_get_access_token();
				update_option('nimble_access_token',$access_token);
        
        $this->nimble_search_contact( $emailaddress);
    } else if (($response_data[0] == 200) && ($response_data[1]->meta->total == 0)) {
        return "OK";
    } else if (($response_data[0] == 200) && ($response_data[1]->meta->total != 0)) {
        return "Email Already Exist";
    } else if (($response_data[0] != 200)) {
        if ($response_data[1] == '') {
            return "syntax error";
        } else {
            return $response_data[1]->message;
        }
    }
    
}



 public function nimble_add_contact( $firstname, $lastname, $emailaddress, $phone_work, $phone_mobile, $title)
{
     
    $response_status = $this->nimble_search_contact($emailaddress);
	
    $access_token =  get_option('nimble_access_token');
	
    if ($response_status == 'OK') {
        $url    = 'https://api.nimble.com/api/v1/contact?access_token=' . $access_token;
        $method = 'POST';
    
		$description = ' this is description data';
		
if ( get_option('CHKN_Fname') =='on'	)	
{  $data_fields = '"first name":[{"value":"'.$firstname.'","modifier":""}],'; }	
if ( get_option('CHKN_Lname') =='on'	)	
{  $data_fields .= '"last name":[{"value":"'.$lastname.'","modifier":""}],'; }
if ( get_option('CHKN_title') =='on'	)	
{  $data_fields .= '"title":[{"value":"'.$title.'","modifier":""}],'; }
if ( get_option('CHKN_email') =='on'	)	
{  $data_fields .= '"email":[{"value":"'.$emailaddress.'","modifier":"personal"}],'; }
			
 if (( get_option('CHKN_phone_work') =='on'	)	&&  ( get_option('CHKN_phone_mobile') =='on'	)	)
{  $data_fields .= '"phone":[{"value":"'.$phone_work.'","modifier":"work"},{"value":"'.$phone_mobile.'","modifier":"mobile"}],'; }
else if (( get_option('CHKN_phone_work') =='on'	) )
{  $data_fields .= '"phone":[{"value":"'.$phone_work.'","modifier":"work"}],'; }
else if (( get_option('CHKN_phone_mobile') =='on'	) )
{  $data_fields .= '"phone":[{"value":"'.$phone_mobile.'","modifier":"mobile"}],'; }
     
 $data_fields = substr($data_fields, 0, -1);
 
	 $data = '{"fields":{'.$data_fields.'},"type":"person"}';	
 
 $response_data = $this->nimble_request($url, $method, $data);
          
    } else { 	
      
    }
}


public function nimble_request($url, $method, $data)
{   
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
    );
       
    $handle = curl_init();
    
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HEADER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($handle, CURLOPT_VERBOSE, FALSE);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($handle, CURLOPT_TIMEOUT, 90);
    
    
    switch ($method) {
        
        case 'GET':
            break;
        
        case 'POST':
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            break;
        
        case 'PUT':
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            break;
        
        case 'DELETE':
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
            
    }
    
    $response = curl_exec($handle);
    $code     = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    
    $header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
    $body        = substr($response, $header_size);
    $error       = json_decode($body);
    
    $response_data    = Array();
    $response_data[0] = $code;
    $response_data[1] = $error;
    
    return $response_data;
    
}
}
?>