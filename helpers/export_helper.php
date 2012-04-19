<?php

function get_csv_field($field) {
	if (preg_match( '/\\r|\\n|,|"/', $field )) {
		$field = '"' . str_replace('"', '""', $field) . '"';
	}
	return $field;
}
/**
 * transform the input array as csv data maintaining consistency with most CSV implementations
 * uses double-quotes as enclosure when necessary
 * uses double double-quotes to escape double-quotes 
 * uses CRLF as a line separator
 */
function get_csv_line($fields) {
	$new_fields = array();
	foreach ($fields as $field) {
		$new_fields[] = get_csv_field($field);
	}
	$csv_line = implode(',', $new_fields) . "\r\n";
	return $csv_line;
}

function get_csv($rows) {
	$csv_lines = array();
	foreach ($rows as $fields) {
		$csv_lines[] = get_csv_line($fields);
	}
	$csv = implode('', $csv_lines);
	return $csv;
}

function export_csv($rows, $filename) {
	$csv = get_csv($rows);
	$csv_gbk = @iconv( "UTF-8", "gbk//IGNORE" , $csv);
	header('Content-Type: text/csv');
	header("Pragma: no-cache");
	header("Content-Disposition: attachment;filename=$filename" );
	echo $csv_gbk;
}
