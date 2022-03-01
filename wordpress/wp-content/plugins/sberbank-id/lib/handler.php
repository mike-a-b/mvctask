<?php 

/**
	Sberbank ID 
	v 0.9.2
 */
namespace Sberbank;
// this constant allready used
// define('SBERBANK_ID_SERTIFICATE_DIR', SBERBANK_ID_SERTIFICATE_DIR);

class sberbank_id_handler
{
	private $gate_urls = [
		'AUTH' => 'https://online.sberbank.ru/CSAFront/oidc/authorize.do',
		'TOKEN' => 'https://api.sberbank.ru/ru/prod/tokens/v2/oidc',
		'USERINFO' => 'https://api.sberbank.ru/ru/prod/sberbankid/v2.1/userinfo',
	];

	private $options = [
		'client_id' => false,
		'client_secret' => false,
		'sertificate_file' => false,
		'sertificate_password' => false,
		'sertificate_dir' => false,
		'nonce' => false,
		'state' => false,
		'code' => false,
		'access_token' => false,
		'scope' => 'openid+name+email',
		'redirect_url' => false,
		'pkce' => false,
		'web_to_app' => false,
		'code_verifier' => false,
		'code_challenge' => false,
		'debug_mode' => false
	];
	
	private $debugData = array();
	private $userinfo = array();
	private $oauth_url = false;
	private $errors = false;
	

	public function __construct() {
		$this->options['nonce'] = 'n-' . $this->generateRandomString(3) . '_' . $this->generateRandomString(10);
		$this->options['state'] = $this->generateRandomString();
		$this->options['sertificate_dir'] = SBERBANK_ID_SERTIFICATE_DIR;
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == '1') ? 'https://' : 'http://';
		$domain_name = $_SERVER['HTTP_HOST'];
		$this->options['redirect_url'] =  $protocol . $domain_name .'/?oauth=sberbank_id';
	}

	public function setClientId($value) {
		$this->options['client_id'] = $value;
	}
	public function setClientSecret($value) {
		$this->options['client_secret'] = $value;
	}
	public function setSertificatePassword($value) {
		$this->options['sertificate_password'] = $value;
	}
	public function setSertificateFile($value) {
		$this->options['sertificate_file'] = $value;
	}
	public function getSertificateDir() {
		 return $this->options['sertificate_dir'];
	}

	public function generate_oauth_url() {}
	
	public function setProperty($prop,$value) {
		if($prop == 'code') {
			$this->options['code'] = $value;
			return true;
		} else if($prop == 'access_token') {
			$this->options['access_token'] = $value;
			return true;
		} else if($prop == 'pkce') {
			$this->options['pkce'] = $value;
			return true;
		} else if($prop == 'web_to_app') {
			$this->options['web_to_app'] = $value;
			return true;
		} else if($prop == 'code_verifier') {
			$this->options['code_verifier'] = $value;
			return true;
		}
		else if($prop == 'scope') {
			$this->options['scope'] = $value;
			return true;
		}
		return false;
	}
	public function getProperty($prop) {
		if($prop == 'code') {
			return $this->options['code'];
		} else if($prop == 'access_token') {
			return $this->options['access_token'];
		} else if($prop == 'pkce') {
			return $this->options['pkce'];
		} else if($prop == 'web_to_app') {
			return $this->options['web_to_app'];
		} else if($prop == 'code_verifier') {
			return $this->options['code_verifier'];
		}
		return false;
	}
	public function getUserinfo() {
		return $this->userinfo;
	}

	public function redirect_to_auth_url() {
		$auth_url = 'https://online.sberbank.ru/CSAFront/oidc/authorize.do' .
		'?response_type=code' .
		'&client_type=PRIVATE' .
		'&scope=' . $this->options['scope'] .
		'&client_id=' . urlencode($this->options['client_id']) .
		'&redirect_uri=' . urlencode($this->options['redirect_url']) .
		($this->options['nonce'] <> '' ? '&nonce=' . urlencode($this->options['nonce']) : '') .
		($this->options['state'] <> '' ? '&state=' . $this->options['state'] : '');

		if($this->options['pkce']) {
			$code_verifier = null;
			$code_challenge = null;
			$random = bin2hex(openssl_random_pseudo_bytes(32));
			if($code_verifier === null) {
				$code_verifier = $this->base64url_encode(pack('H*', $random));
			}
			if($code_challenge === null) {
				$code_challenge = $this->base64url_encode(pack('H*', hash('sha256', $code_verifier)));
			}
			$this->options['code_verifier'] = $code_verifier;

			$auth_url .= '&code_challenge=' . $code_challenge;
			$auth_url .= '&code_challenge_method=S256';
		}

		array_push($this->debugData, array(
			'METHOD' => 'GENERATE_AUTH_LINK',
			'AUTH_URL' => $auth_url, 
		));
		return $auth_url;
  
	}


	public function reqGetAccessToken() {

		$this->checkFileSertificate();
		if($this->errors) {
			return false;
		}
		$headers = array(
			'accept:' . 'application/json',
	    	'content-type:' . 'application/x-www-form-urlencoded',
	    	'rquid:' . $this->generateRandomString(),
	    	'x-ibm-client-id: ' . $this->options['client_id'],
		);
		$data = array(
			'grant_type' => 'authorization_code',
			'scope' => $this->options['scope'],
			'client_id' => $this->options['client_id'],
			'client_secret' => $this->options['client_secret'],
			'code' => $this->options['code'],
			'redirect_uri' => $this->options['redirect_url'],
		);
		if($this->options['code_verifier']) {
			$data['code_verifier'] = $this->options['code_verifier'];
		}
		
		$response = $this->gateway( $headers, $data, $this->gate_urls['TOKEN'] );
		
		array_push($this->debugData, array(
			'METHOD' => 'GET_ACCESS_TOKEN',
			'REQUEST_URL'=> $this->gate_urls['TOKEN'], 
			'HEADERS'=> $headers,
			'REQUEST_DATA' => $data, 
			'RESPONSE' => $response
		));

		if(isset($response['access_token'])) {
			$this->setProperty( 'access_token', $response['access_token'] );
		} else {
			$this->errors = true;
			return false;
		}

		return true;
	}

	public function reqGetUserInfo() {

		$this->checkFileSertificate();
		if($this->errors) {
			return false;
		}
		$headers = array(
		    'accept:' . 'application/json',
		    'x-ibm-client-id: ' . $this->options['client_id'],
		    'x-introspect-rquid:' . $this->generateRandomString(),
		    'Authorization:Bearer ' . $this->options['access_token'],
		);
		
		$data = array();

		$response = $this->gateway( $headers, $data, $this->gate_urls['USERINFO'] );

		array_push($this->debugData, array(
			'METHOD' => 'GET_USER_INFO',
			'REQUEST_URL'=> $this->gate_urls['USERINFO'], 
			'HEADERS'=> $headers,
			'REQUEST_DATA' => $data, 
			'RESPONSE' => $response
		));
		if(isset($response['httpCode'])) {
			$this->errors = true;	
			return false;
		}
		$this->userinfo = $response;
		return true;
		
	}


	private function generateRandomString($length = 32) {
	    $characters = 'ABCDEFabcdef0123456789';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function sertificate_crop($uploaded_file) {
		if(!$this->getSertificateDir()) {
			return false;
		}

		if(!file_exists($this->options['sertificate_dir'])) {
			mkdir($this->options['sertificate_dir'], 0700);
		}

		move_uploaded_file($uploaded_file, $this->getSertificateDir() . 'sertificate.p12');
		$cert_store = file_get_contents($this->options['sertificate_dir'] . 'sertificate.p12');

		openssl_pkcs12_read($cert_store, $cert_info, $this->options['sertificate_password']);

		$fp = fopen($this->options['sertificate_dir'] . "client.crt", "w");
		fwrite($fp, $cert_info['cert'].$cert_info['extracerts'][0].$cert_info['extracerts'][1]);
		fclose($fp);
		$fp = fopen($this->options['sertificate_dir'] . "client.key", "w");
		fwrite($fp, $cert_info['pkey']);
		fclose($fp);
	
	}

	public function base64url_encode($plainText)
	{
	    $base64 = base64_encode($plainText);
	    $base64 = trim($base64, "=");
	    $base64url = strtr($base64, '+/', '-_');
	    return ($base64url);
	}


	public function showDebug($method = false) {
	}

	public function authSuccess() {
		if(!$this->errors) {
			return true;
		}
		return false;
	}

	public function showError($method = false) {

		if($method) {
			return array('errorMessage' => 'ВОЗНИКЛА НЕПРЕДВИДЕННАЯ ОШИБКА!', 'debugData' => $this->debugData);
		} else {
			echo '<div style="font-family:arial;margin:20px 10px;">';
            echo '<br><b>Ошибка авторизации, обратитесь к администратору!</b><br>' . '' . '<br>' ;
            echo '<a href="/">Вернуться на главную</a>';
            echo "</div>";
           	if($this->options['debug_mode']) {
				echo '<div style="margin:20px 10px;padding:20px;border:#ccc 1px solid; font-family:arial;"><span style="color:red">Включен режим отладки:</span><br>';
				echo "<pre>";
					print_r($this->debugData);
				echo "</pre>";
				
			}
			die;
		}

    }
    public function authSetError($data = false) {
    	if($data) {
    		array_push($this->debugData,$data);
    	}
    	$this->errors = true;
    }

	private function gateway($headers = array(),$data = array(), $gate_url = '') {

		// $response = wp_remote_post($gate_url,[
		// 	'headers' => $headers,
		// 	'body' => $data,
		// 	'sslverify' => true,
		// 	'sslcertificates' => $this->options['sertificate_dir'] . 'client.crt'
		// ]);
		// print_r($response);
		// die;
		if ( function_exists('curl_init') ) {
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_URL => $gate_url,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_HTTPHEADER => $headers,
			    // CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSLCERT => $this->options['sertificate_dir'] . 'client.crt',
				CURLOPT_SSLKEY => $this->options['sertificate_dir'] . 'client.key',
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSLKEYPASSWD => $this->options['sertificate_password'],
			));
			if(count($data) > 0) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			}
			$response = curl_exec($curl);
			$response = json_decode( $response, true );
			// print_r(curl_error($curl));
			// print_r($response);
			curl_close($curl);
			return $response;
		} else {
			echo "ERROR! NEED INSTALL CURL!";
			die;
		}
	}

	public function logger() {
        $file_name = date("y-m-d") . "_sberbank_id.log";
        error_log("\n" . $file_name . "\n" ."Sberbank ID: \n" . print_r($this->debugData,true) . "\n\n", 3, SBERBANK_ID_PLUGIN_DIR . "log/log.php");
    }

    private function checkFileSertificate() {
    	if($this->errors) {
    		return false;
    	}
    	$fileSert = $this->options['sertificate_dir'] . 'client.crt';
    	
    	if(!$this->getSertificateDir()) {
    		$this->authSetError(array(
    			'METHOD' => 'SERTIFICATE_DIR_EXIST',
    			'MESSAGE' => 'Sertificate directory not specified!'
    		));
    		return false;
    	}
    	if (!file_exists($fileSert)) {
    		$this->authSetError(array(
    			'METHOD' => 'SERTIFICATE_FILE_EXIST',
    			'MESSAGE' => 'Sertificate file not found!'
    		));
    		return false;
        }
        if(filesize($fileSert) <= 1) {
        	$this->authSetError(array(
    			'METHOD' => 'SERTIFICATE_VERIFY',
    			'MESSAGE' => 'Sertificate password not alowed!'
    		));
    		return false;
        }
        return true;
    			        
    }
    public function checkServer() {
  //   	echo "<pre>";
		// $server_info = array();
		// $server_info[] = array("PHP version:", phpversion() );
	 //    if (function_exists('curl_version')) {
	 //        $curl = curl_version();
	 //        $server_info[] = array("cURL version:", $curl["version"] );
	 //        $ch = curl_init('https://www.howsmyssl.com/a/check');
	 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 //        $data = curl_exec($ch);
	 //        curl_close($ch);
	 //        $json = json_decode($data);
	 //        $server_info[] = array("TLS version: ", $json->tls_version );
	 //    } else {
	 //    	$server_info[] = array("PHP version:", phpversion() );
	 //    	$server_info[] = array("cURL", 'Not installed!!!' );
	 //    }
	 //    $server_info[] = array("OpenSSL version text: ", OPENSSL_VERSION_TEXT );
	 //    $server_info[] = array("OpenSSL version number: ", OPENSSL_VERSION_NUMBER );
	 //    print_r($server_info);
	 //    echo "</pre>";
	 //    die;
    }
}

?>