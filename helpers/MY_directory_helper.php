<?php
function dir_list($source_dir, $depth) { //{{{
	/**
	  list all directories with only given depth
	  depth - 0: current dir
	 */

	$fp = opendir($source_dir);
	if (!$fp) return array();
	if ($depth == 0) return array($source_dir);

	$dirs = array();
	while (FALSE != ($file = readdir($fp))) {
		// Remove '.', '..'
		if ($file == '.' OR $file == '..') {
			continue;
		}
		$fullpath = "$source_dir/$file";
		if (!is_dir($fullpath)) { // skip non-dir
			continue;
		}

		$dirs = array_merge($dirs, dir_list($fullpath, $depth - 1));
	}
	closedir($fp);
	return $dirs;
} //}}}
