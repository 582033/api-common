<?php
function array_copy(&$array, $keys) {
	$new_array = array();
	foreach ($keys as $k) {
		if (array_key_exists($k, $array)) {
			$new_array[$k] = $array[$k];
		}
	}
	return $new_array;
}

function array_copy_to(&$to, $from, $keys = NULL) {
	if ($keys === NULL) {
		$keys = array_keys($from);
	}
	foreach ($keys as $k) {
		if (array_key_exists($k, $from)) {
			$to[$k] = $from[$k];
		}
	}
}
