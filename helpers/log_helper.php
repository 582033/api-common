<?php
/**
 * Usage: debug('detail:', array('name'=>'ping'))
 */

function debug($message) {
	$args = func_get_args();
    _show_log("DEBUG", $args);
}
function info($message) {
	$args = func_get_args();
    _show_log("INFO", $args);
}
function warn($message) {
	$args = func_get_args();
    _show_log("ERROR", $args);
}
function error($message) {
	$args = func_get_args();
    _show_log("ERROR", $args);
}

function _show_log($level, $msgobjs) {
	$msg = _get_log_msg($msgobjs);
	log_message($level, $msg);
}

function _get_log_msg($msgobjs) {
	$config =& get_config();
	$msgs = array();
	foreach ($msgobjs as $msgobj) {
		$msgs[] = is_string($msgobj) ? $msgobj : print_r($msgobj, true);
	}
	$msg = implode('', $msgs);

	if ($config['log_encoding']) {
		$msg0 = $msg;
		$msg = mb_convert_encoding($msg0, $config['log_encoding'], 'UTF-8,GBK');
		if ($msg0 != $msg) {
			$msg_enc = $config['log_encoding'] == 'UTF-8' ? 'GBK' : 'UTF-8';
			$msg = "[$msg_enc]$msg";
		}
	}

	return $msg;
}
function dump_log($obj) { //{{{
	require_once APPPATH . 'libs/spyc.php';
	$msg = Spyc::YAMLDump($obj);
	echo "<pre>$msg</pre>";
} //}}}
