<?php
function get_page_nav_info($page, $total, $page_size = null, $page_max_show = null) { //{{{
	global $config;
	if (!$page_size) $page_size = $config['page_size'];
	if (!$page_max_show) $page_max_show = $config['page_max_show'];

	$tpage = $total ? ceil($total / $page_size) : 1;
	$page = max(1, $page);
	$page = min($page, $tpage);

	$start = $page_size * ($page - 1);
	$end = min($start + $page_size, $total);

	return array(
		'cpage' => $page,
		'tpage' => $tpage,
		'maxshow' => $page_max_show,
		'total' => $total,
		'start' => $start,
		'size' => $page_size,
	);
} //}}}
?>
