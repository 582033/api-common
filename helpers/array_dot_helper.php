<?php

function array_dot_key_exists($arr, $dot_key) { //{{{
	/**
	 * dot_key - 'a.b'
	 * return - whether $arr['a']['b'] exists
	 */

	$keys = explode('.', $dot_key);
	$v = $arr;
	foreach ($keys as $k) {
		if (!array_key_exists($k, $v)) return FALSE;
		$v = $v[$k];
	}
	return TRUE;
} //}}}

function array_dot_get($arr, $dot_key) { //{{{
	/**
	 * dot_key - 'a.b.c'
	 * return - $arr['a']['b'] or NULL if key doesn't exist
	 */

	$keys = explode('.', $dot_key);
	$v = $arr;
	foreach ($keys as $k) {
		if (!array_key_exists($k, $v)) return NULL;
		$v = $v[$k];
	}
	return $v;
} //}}}

function array_dot_unset(&$arr, $dot_key) { //{{{
	/**
	  dot_key - a, a.b, a.b.c
	  support at most two dots
	  unset($arr['a']['b'])
	  */
	$keys = explode('.', $dot_key);
	$depth = count($keys);
	switch ($depth) {
		case 1:
			unset($arr[$keys[0]]);
			break;
		case 2:
			unset($arr[$keys[0]][$keys[1]]);
			break;
		case 3:
			unset($arr[$keys[0]][$keys[1]][$keys[2]]);
			break;
	}
} //}}}

function array_dot_set(&$arr, $dot_key, $v) { //{{{
	/**
	  dot_key - a, a.b, a.b.c
	  support at most two dots
	  $arr['a']['b'] = $v
	  */
	$keys = explode('.', $dot_key);
	$depth = count($keys);
	switch ($depth) {
		case 1:
			$arr[$keys[0]] = $v;
			break;
		case 2:
			$arr[$keys[0]][$keys[1]] = $v;
			break;
		case 3:
			$arr[$keys[0]][$keys[1]][$keys[2]] = $v;
			break;
	}
} //}}}

function array_dot_key_move(&$array, $to_dot_key, $src_dot_key) { //{{{
	/**
	 to_dot_key - c.d
	 src_dot_key - a.b
	 move a.b to c.d if $arr['a']['b'] exists, that is:
	   $arr['c']['d'] = $arr['a']['b']; unset($arr['a']['b'])

	 support at most two dots

	 return - whether key is moved
	 */

	if (!array_dot_key_exists($array, $src_dot_key)) {
		return FALSE;
	}
	array_dot_set($array, $to_dot_key, array_dot_get($array, $src_dot_key));
	array_dot_unset($array, $src_dot_key);
} //}}}

function array_dot_keys_move(&$array, $to_dot_parent_key, $src_dot_keys) { //{{{
	/**
	  to_dot_parent_key - a.b
	  src_dot_keys - ['c.d', 'e.f']
	  move c.d -> a.b.d, e.f -> a.b.f for $array
	  see array_dot_key_move for more info
	 */
	foreach ($src_dot_keys as $src_dot_key) {
		$name = preg_replace('#^.*\.#', '', $src_dot_key); # a.b.c => c
		array_dot_key_move($array, "$to_dot_parent_key.$name", $src_dot_key);
	}
} //}}}
