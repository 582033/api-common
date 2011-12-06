<?php

function highlight_from_excerpt($excerpt) {
	$parts = preg_split('#<b>|</b>#', $excerpt, -1, PREG_SPLIT_OFFSET_CAPTURE);

	$highlights = array();
	$from = 0;
	foreach ($parts as $i => $part) {
		list($match, $pos) = $part;
		$len = mb_strlen($match, 'utf8');

		if ($i % 2 == 1) {
			$to = $from + $len;
			$highlights[] = "$from-$to";
		}
		$from += $len;
	}

	return implode(',', $highlights);
}
