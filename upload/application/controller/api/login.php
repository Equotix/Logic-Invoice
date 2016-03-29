<?php
defined('_PATH') or die('Restricted!');

class ControllerApiLogin extends Controller {
    public function index() {
        $this->load->language('api/login');
		
		$json = array();
		
		$json['success'] = false;
		
		$realm = 'API Area';
		
		// Re-authenticate as was previously logged in
		if (isset($this->session->data['api_key'])) {
			unset($this->session->data['api_key']);
			
			unset($this->request->server['PHP_AUTH_DIGEST']);
		}

        $this->load->model('api/api');
        $this->load->model('system/activity');
		
		if (isset($this->request->server['PHP_AUTH_DIGEST'])) {
			$data = $this->http_digest_parse($this->request->server['PHP_AUTH_DIGEST']);
			
			if ($data) {
				$api_info = $this->model_api_api->getApiUserByKey($data['username']);

				if ($api_info) {
					$A1 = md5($data['username'] . ':' . $realm . ':' . $api_info['secret']);
					$A2 = md5($this->request->server['REQUEST_METHOD'] . ':' . $data['uri']);
					$valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

					if ($data['response'] == $valid_response) {
						$json['success'] = true;

						$this->session->data['api_key'] = md5(mt_rand());
						$this->session->data['username'] = $api_info['username'];

						$json['api_key'] = $this->session->data['api_key'];

						$json['cookie'] = $this->session->getId();

						$this->model_system_activity->addActivity(sprintf($this->language->get('text_login'), $this->session->data['username']));
					}
				}
			}
		}
		
		if ($json['success'] == false) {
			$json['error'] = $this->language->get('error_login');
			
			$this->response->addHeader('HTTP/1.1 401 Unauthorized');
			$this->response->addHeader('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
		}
		
		$this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
	
	protected function http_digest_parse($text) {
		$required_parts = array(
			'nonce'    => 1,
			'nc'       => 1,
			'cnonce'   => 1,
			'qop'      => 1,
			'username' => 1,
			'uri'      => 1,
			'response' => 1
		);
		
		$data = array();

		$keys = implode('|', array_keys($required_parts));

		preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $text, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$data[$match[1]] = str_replace('&quot;', '', $match[3] ? $match[3] : $match[4]);
			
			unset($required_parts[$match[1]]);
		}

		return $required_parts ? false : $data;
	}
}