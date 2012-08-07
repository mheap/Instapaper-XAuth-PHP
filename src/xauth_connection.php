<?php
class XAuth_Connection
{
	private $_params = array(
		"x_auth_mode" => "client_auth"
		, "oauth_signature_method" => "HMAC-SHA1"
		, "oauth_version" => "1.0"
	),

	$_access_url = '';

	public function __construct($key, $private, $access_url)
	{
		$this->_params['oauth_consumer_key'] = $key;
		$this->_params['oauth_nonce'] = md5(uniqid(rand(), true)); 
		$this->_params['oauth_timestamp'] = time(); 

		$this->_oauth_consumer_private = $private;
		$this->_access_url = $access_url;
	}

	public function set_credentials($user, $password)
	{
		$this->_params['x_auth_username'] = $user;
		$this->_params['x_auth_password'] = $password;
	}

	public function generate_oauth_params()
	{

		ksort($this->_params);

		$req = array();
		foreach ($this->_params as $k => $v)
		{
			$req[] = $this->encode($k) . "%3D" . $this->encode( $this->encode($v) ); 
		}

		return implode("%26", $req);
	}

	public function generate_signature()
	{
		$url = $this->encode( $this->_access_url );
		$oauth_params = $this->generate_oauth_params();

		$signature_base = 'POST&'. $this->encode($this->_access_url) .'&'.$oauth_params;

		$key = $this->_oauth_consumer_private . '&'; 

		return base64_encode(hash_hmac("sha1",$signature_base, $key, true));
	}


	public function login()
	{

		$this->_params['oauth_signature'] = $this->generate_signature();

		$oauth_str = '';
		foreach ($this->_params as $k => $v)
		{
			$oauth_str .= $k.'='.$this->encode($v).'&';
		}

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->_access_url); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $oauth_str); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$exec = curl_exec($ch); 
		$info = curl_getinfo($ch); 
		curl_close($ch);

		if ($info['http_code'] != 200)
		{
			return false;
		}

		parse_str($exec, $r);
		return $r;

	}

	private function encode($s)
	{
		return ($s === false ? $s : str_replace('%7E','~',rawurlencode($s))); 
	}

}
