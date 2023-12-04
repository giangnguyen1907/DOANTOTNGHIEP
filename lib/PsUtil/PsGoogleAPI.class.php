<?php
class PsGoogleAPI {

	protected $firebase_access_key = 'AIzaSyBKI6sB56oZYek0Bbr_wL1KMZzPJJxA7lI';

	protected $googleapis_url = 'https://iid.googleapis.com/iid';
	
	public function __construct($setting) {
		
		$this->setHeader ();
		
		$this->setRegistrationId ( $setting->registrationId );
	}
	
	public function setHeader() {
		
		$this->headers = array (
				'Authorization: key=' . $this->firebase_access_key,
				'Content-Type: application/json'
		);
	}
	
	public function getHeader() {
	
		return $this->headers;
	}
	
	public function setRegistrationId($registrationId) {
	
		$this->registrationId = $registrationId;
	}
	
	/**
	 * @return array()
	 */
	public function getRegistrationId() {
	
		return $this->registrationId;
	}
	
	public function getInfo() {
		
		$url_info = $this->googleapis_url.'/info/'.$this->registrationId.'?details=true';
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url_info );
		curl_setopt ( $ch, CURLOPT_HTTPGET, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $this->getHeader () );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		//curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $fields ) );
		$result = curl_exec ( $ch );
		
		curl_close ( $ch );
		
		return $result;
	}

}