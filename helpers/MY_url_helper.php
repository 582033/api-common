<?php
function current_url() { //{{{
	$CI =& get_instance();
	$request_uri = $CI->input->server('REQUEST_URI');
	$host = $CI->input->server('HTTP_HOST');
	$url = "http://$host" . $request_uri;
	return $url;
} //}}}

function url_append_params($url, $params) { // {{{
	/**
	 * Replace or append param, ignore empty one
	 * @return - new url
	 */

	$parts = preg_split('/[?&]/', $url);
	$base_url = $parts[0];
	$all_params = array_slice($parts, 1);

	foreach ($params as $k => $v) {
		if ($v === FALSE || $v === '') continue;
		$found = FALSE;
		foreach ($all_params as &$param) {
			if (preg_match("/$k=/", $param)) {
				$param = "$k=$v";
				$found = TRUE;
				break;
			}
		}
		unset($param);
		if (!$found) {
			$all_params[] = "$k=$v";
		}
	}
	if ($all_params) {
		$url = "$base_url?" . implode('&', $all_params);
	}
	return $url;
} // }}}

function url_remove_params($url, $keys) { // {{{
	/**
	 * Remove given keys from url
	 * @return - the new url
	 */
	$parts = preg_split('/[?&]/', $url);
	$base_url = $parts[0];
	$old_params = array_slice($parts, 1);
	$new_params = array();
	foreach ($old_params as $param) {
		$found = FALSE;
		foreach ($keys as $k) {
			if (preg_match("/$k=/", $param)) {
				$found = TRUE;
				break;
			}
		}
		if (!$found) {
			$new_params[] = $param;
		}
	}

	if ($new_params) {
		$url = "$base_url?" . implode('&', $new_params);
	}
	else {
		$url = $base_url;
	}
	return $url;
} //}}}
function post_request($url, $data) { //{{{
	/**
	 * data - array('k1' => 'v1', 'k2' => 'v2')
	 */
	$postdata = http_build_query($data);
	$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
				)
			);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
} //}}}

function retrieve_url_info($url, $headers=NULL) { //{{{
	/**
	  return - the same with curl_getinfo, see http://cn2.php.net/manual/en/function.curl-getinfo.php
	  important keys:
		url, content_type, http_code, download_content_length
	*/

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	if ($headers) {
		$http_headers = array();
		foreach ($headers as $k => $v) {
			$http_headers[] = "$k: $v";
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
	}
	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return $info;
} //}}}

function retrieve_remote_file_size($url, $headers=NULL) { //{{{
	$info = retrieve_url_info($url, $headers);
	if ($info and $info['http_code'] == '200') {
		$size = $info['download_content_length'];
	}
	else {
		$size = 0;
	}
	return $size;
} //}}}

function curl_get_contents($url, $headers, $bytes=0) { //{{{
	/**
	  bytes - the number of bytes to return. 0 means all
	  return given bytes of the url in binary
	 */

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	if ($headers) {
		$http_headers = array();
		foreach ($headers as $k => $v) {
			$http_headers[] = "$k: $v";
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
	}
	if ($bytes) {
		$data = '';
		$writefn = function($ch, $chunk) use (&$data, $bytes) {
			$len = strlen($data) + strlen($chunk);
			if ($len >= $bytes ) {
				$data .= substr($chunk, 0, $bytes-strlen($data));
				return -1;
			}

			$data .= $chunk;
			return strlen($chunk);
		};
		curl_setopt($ch, CURLOPT_WRITEFUNCTION, $writefn);
		curl_exec($ch);
	}
	else {
		$data = curl_exec($ch);
	}
	curl_close($ch);
	return $data;
} //}}}
