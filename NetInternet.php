<?php

/**
 * NetInternet.php
 *
 * WAF Services
 *
 * @category   WAF
 * @package    WAF.Documents
 * @author     isReH Software Services
 * @copyright  2021.08.27 / 07:05 - isReH Software Services
 * @version    1.0
 * @link       https://www.netinternet.com.tr/
 * @link       https://www.yazilimcin.net/
 */

class NetInternet{
	
	private $API_KEY;
	private $CRYPTO_KEY;
	private $API_URL;
	private $TOKEN;

	public function __construct(){
		
		$this->API_KEY = "";
		$this->CRYPTO_KEY = "";
		$this->API_URL = "https://waf.ni.net.tr/panel/api/v1/";
		$this->TOKEN = $this->token();

	}

	private function token(){

		$getToken = $this->request("token","POST",[
			"access" => $this->API_KEY, // api key * [required]
			"secret" => $this->CRYPTO_KEY // crypto key * [required]
		]);
		$arrayToken = json_decode($getToken);
		return $arrayToken->data->token;

	}

	public function maintenance($status = true){

		return $this->request("maintenance","POST",[
			"maintenance" => $status // true or false * [required]
		]);

	}

	public function protection($challenge = "captcha", $status = true){

		return $this->request("challenge","POST",[
			"challenge" => $challenge, // captcha or javascript * [required]
			"status" => $status // true or false * [required]
		]);

	}

	public function listServers(){

		return $this->request("servers","GET");

	}

	public function editServers($server = "https://127.0.0.1:3000", $status = true){

		return $this->request("servers","POST",[
			"server" => $server, // example(https://127.0.0.1:3000) * [required]
			"status" => $status // true or false * [required]
		]);

	}

	public function listRules(){

		return $this->request("rules","GET");

	}

	public function editRules($ruleName = "", $status = true){

		return $this->request("rules","POST",[
			"rule" => $ruleName, // example(bypass-test) * [required]
			"status" => $status // true or false * [required]
		]);

	}

	public function listBannedIP(){

		return $this->request("autoban","GET");

	}

	private function request($module = "", $requestType = "GET", $postParams = []){

		$curl = curl_init();
		curl_setopt_array($curl, [
		  CURLOPT_URL => $this->API_URL.$module,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $requestType,
		  CURLOPT_POSTFIELDS => $postParams,
		  CURLOPT_HTTPHEADER => [
		    "accept: application/json",
		    "authorization: Bearer {$this->TOKEN}"
		  ],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if($err){
		  return "cURL Error #:" . $err;
		}else{
		  return $response;
		}

	}

}