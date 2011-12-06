<?php
function current_url() {
	$CI =& get_instance();
	$request_uri = $CI->input->server('REQUEST_URI');
	$host = $CI->input->server('HTTP_HOST');
	$url = "http://$host" . $request_uri;
	return $url;
}

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
