<?php

 function RTapexApiAuthentication($requestType)
 {
	$headers = array(
		'Content-Type' => 'application/x-www-form-urlencoded',
		'outputtype' => 'json'
	);
	//Connecting with apexidx.com server for validation
	$apiResponse = wp_remote_get('http://apexidx.com/check_api.php?apikey='.get_option('apexAgentApiKey').'&requestType='.$requestType , array( 'timeout' => 120, 'sslverify' => false, 'headers' => $headers ));
	
	try 
	{
 
		$data = json_decode( $apiResponse['body'] );
		
		if($data->code == 404)
		{
			return new WP_Error("apex_api_error", __("Error :  $data->message"));
		}
		else
		{
			return $data;
		}		  
	   
    } 
	catch ( Exception $ex ) 
	{
        return new WP_Error("apex_api_error", __("Error : $e->getMessage()"));
    } 

}