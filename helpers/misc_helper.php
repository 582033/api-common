<?php
function is_inner_ip() {
	$CI =& get_instance();
	$is_inner_ip = $CI->config->item('inner_ip');
	if ($is_inner_ip === 'auto') {
		$rip = $_SERVER['REMOTE_ADDR'];
		$is_inner_ip = ($rip == '211.103.252.242' || $rip == '114.242.12.23' ||
				preg_match('/^192.168\.\d+\.\d+/', $rip));
	}
	return $is_inner_ip;
}
function normalize_filename($name) {
	$normalized_name = preg_replace('#[:/"|><*?]#', '-', $name);
	return $normalized_name;
}

function human_readable_size($num) { // {{{
	$units = array('B','KB','MB','GB','TB');
    foreach ($units as $x) {
        if ($num < 1024.0) {
            return sprintf("%3.1f $x", $num);
		}
        $num /= 1024.0;
	}
} //}}}

function normalize_size($size) {
	if (preg_match('#^[0-9]+$#', $size)) {
		return human_readable_size(intval($size));
	}
	else {
		return $size;
	}
}

function escape($url) {
	// use rawurlencode instead of urlencode to encode ' ' as '%20' instead of '+'
	return str_replace("%2F", "/", rawurlencode($url));
}

function normalize_url($url) {
	$parts = explode('://', $url, 2);
	if (count($parts) == 2) {
		$protocal = strtolower($parts[0]);
		$url = "$protocal://$parts[1]";
	}
	$url = str_replace(' ', '%20', $url);
	return $url;
}

function real_empty($s) {
	return $s === '' || $s === FALSE;
}

function get_res_url($res_type, $path) {
	$CI =& get_instance();
	$url = $CI->config->item('res_url_prefix') . "/$res_type/$path";
	return $url;
}

function wget_contents($url, $proxy=FALSE) {
	/**
	 * proxy - ip:port
	 * file_get_contents may hang, so use wget in this case
	 * example hang url: http://minterface.tudou.com/api/video?key=GEYDEMZSGE2TIMJTGAYDAMJQGI2DCMRTHA&itemid=70096215&ip=211.103.252.242
	 * see bug: http://bugs.php.net/bug.php?id=51330&thanks=6
	 */
	if ($proxy) {
		$extra = "-e http-proxy=$proxy --proxy=on";
	}
	else {
		$extra = "";
	}

    $content = `wget $extra -o /dev/null -O - "$url"`;
    return $content;
}
