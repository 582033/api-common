<?php

function get_app_icon_path($package, $label) {
	/**
	  return - <prefix>/package-label_md5[0:4].png
	  prefix = first 2 letter of the second part of package
	 */

	$lpackage = trim(strtolower($package));
	$parts = explode('.', $lpackage);
	$prefix = substr($parts[1], 0, 2);
	$llabel = trim(strtolower($label));
	$label_md5 = substr(md5($llabel), 0, 4);
	$icon_path = "$prefix/$lpackage-$label_md5.png";
	return $icon_path;
}