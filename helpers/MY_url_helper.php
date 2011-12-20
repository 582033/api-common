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

function retrieve_remote_file_size($url) { //{{{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ch);
	curl_close($ch);
	$contentLength = 0;
	if ($data !== false) {
		if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
			$contentLength = (int) $matches[1];
		}
	}
	return $contentLength;
} //}}}
