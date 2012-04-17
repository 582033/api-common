<?php

if ( ! function_exists('get_app_icon_path')) {
	function get_app_icon_path($package, $label, $version_code) { //{{{
		/**
		  return - <prefix>/package-label_md5[0:4]-version_code.png
		  prefix = first 2 letter of the second part of package
		 */

		$lpackage = trim(strtolower($package));
		$parts = explode('.', $lpackage);
		$prefix = substr($parts[1], 0, 2);
		$llabel = trim(strtolower($label));
		$label_md5 = substr(md5($llabel), 0, 4);
		$icon_path = "$prefix/$lpackage-$label_md5-$version_code.png";
		return $icon_path;
	} //}}}
}

if ( ! function_exists('get_app_dir_name')) {
	function get_app_dir_name($package, $label, $version_code) { //{{{
		/**
		  return - package-label_md5[0:4]-vcode
		 */
		$lpackage = trim(strtolower($package));
		$parts = explode('.', $lpackage);
		$prefix = substr($parts[1], 0, 2);
		$llabel = trim(strtolower($label));
		$label_md5 = substr(md5($llabel), 0, 4);

		return "$lpackage-$label_md5-$version_code";
	} //}}}
}

if ( ! function_exists('get_app_reskey')) {
	function get_app_reskey($package, $label) { # {{{
		if (!$package || !$label) {
			return NULL;
		}
		else {
			$lpackage = trim(strtolower($package));
			$llabel = trim(strtolower($label));
			return "$lpackage-$llabel";
		}
	} # }}}
}

if ( ! function_exists('get_am_select')) {
	function get_am_select($ntype, $p='') { // {{{
		/**
		 * ntype - video/app/picture
		 * p - R./I.
		 */

		$keys = array(
				'app' => array('a_category', 'a_tag', 'a_alias', 'a_name', 'a_intro', 'a_screenshots'),
				'video' => array('a_category', 'a_tag', 'a_alias', 'a_area'),
				'picture' => array('m_name', 'm_category', 'm_intro'),
				);
		$selects = array();
		foreach (element($ntype, $keys, array()) as $k) {
			list ($am, $k) = explode('_', $k, 2);
			if ($am == 'a') {
				$selects[] = "IF(length({$p}$k) > 0,{$p}$k,{$p}a_$k) AS $k";
			}
			else {
				$selects[] = "IF(length({$p}manual_$k) > 0,{$p}manual_$k,{$p}$k) AS $k";
			}
		}
		$select = implode(',', $selects);
		return "," . $select;
	} //}}}
}

if ( ! function_exists('normalize_type')) {
	function normalize_type($type) { //{{{
		$typemap = array(
				'soft' => 'app',
				'game' => 'app',
				);
		$ntype = $type;
		if (array_key_exists($type, $typemap)) {
			$ntype = $typemap[$type];
		}
		return $ntype;
	} //}}}
}

if ( ! function_exists('is_app_type')) {
	function is_app_type($type) { //{{{
		$ntype = normalize_type($type);
		return $ntype == 'app';
	} //}}}
}

if ( ! function_exists('get_enabled_name')) {
	function get_enabled_name($type) { //{{{
		return is_app_type($type) ? 'enabled' : 'visible';
	} //}}}
}

if (! function_exists('apkd_to_service_url')) {
	function apkd_to_service_url($url) { //{{{
		return str_replace('http://apkd.wowpad.cn/', 'http://service.wowpad.cn/download/apkd/', $url);
	} //}}
}

if (! function_exists('get_thumb_url')) {
	function get_thumb_url($img_url){//{{{
		return "http://thumb.wowpad.cn/thumb?" . http_build_query(array('src' => $img_url));
	}//}}}
}
