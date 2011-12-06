<?php
function now_str($time=NULL) {
	if ($time) {
		return date('Y-m-d H:i:s', $time);
	}
	else {
		return date('Y-m-d H:i:s');
	}
}

function time_diff($time1, $time2) {
	/**
	 * @return seconds
	 */
	return strtotime($time2) - strtotime($time1);	
}


