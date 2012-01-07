<?php

function generate_serial_number($n) {
	$seed = str_repeat("0123456789", $n);
	$arr = preg_split('//', $seed, -1, PREG_SPLIT_NO_EMPTY);
	shuffle($arr);
	$rand_keys = array_rand($arr, $n);
	$values = array();
	foreach ($rand_keys as $i) {
		$values[] = $arr[$i];
	}

	return implode('', $values);
}

#echo generate_serial_number(12);


