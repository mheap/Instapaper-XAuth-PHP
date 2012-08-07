<?php

require_once 'xauth_connection.php';
class Instapaper_XAuth {

	protected $_access_token_endpoint = 'https://www.instapaper.com/api/1/oauth/access_token',
		$consumer_key, $consumer_secret;

	public function __construct($consumer_key, $consumer_secret){
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
	}

	public function login($username, $password){
		$xac = new XAuth_Connection($this->consumer_key, $this->consumer_secret, $this->_access_token_endpoint);
		$xac->set_credentials($username, $password);

		$resp = $xac->login();
		if (!$resp){
			throw new Exception("Invalid Credentials");
		}

		return $resp;
	}

}
