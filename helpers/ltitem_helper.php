<?php

function get_app_icon_path($package, $label, $version_code) {
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
}

function get_app_dir_name($package, $label, $version_code) {
	/**
	  return - package-label_md5[0:4]-vcode
	 */
	$lpackage = trim(strtolower($package));
	$parts = explode('.', $lpackage);
	$prefix = substr($parts[1], 0, 2);
	$llabel = trim(strtolower($label));
	$label_md5 = substr(md5($llabel), 0, 4);

	return "$lpackage-$label_md5-$version_code";
}

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
