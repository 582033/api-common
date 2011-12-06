<?php
function show_error_json($errcode, $errmsg) {
	$data = array(
		'errcode' => $errcode, 
		'errmsg' => $errmsg,
	);
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}
