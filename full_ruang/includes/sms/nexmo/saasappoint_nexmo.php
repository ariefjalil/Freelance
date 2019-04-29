<?php 
class saasappoint_nexmo{
	/* Send SMS using Nexmo */
	public function saasappoint_send_nexmo_sms($phone,$sms_body,$saasappoint_nexmo_api_key,$saasappoint_nexmo_api_secret,$saasappoint_nexmo_from) {
		/* Prepare data for GET request */
		$queryinfo = array('api_key' => $saasappoint_nexmo_api_key, 'api_secret' => $saasappoint_nexmo_api_secret, 'to' => $phone, 'from' => $saasappoint_nexmo_from, 'text' => $sms_body);
		$url = 'https://rest.nexmo.com/sms/json?' . http_build_query($queryinfo);
		
		/* Send the GET request with cURL */
		$ch = curl_init($url);
		/* curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		return $response;
	}
}