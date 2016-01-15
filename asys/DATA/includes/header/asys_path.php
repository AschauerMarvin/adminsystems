<?php
define('asys_DATA_path', 'DATA');
function str_replace_last($search, $replace, $str) {
	$str_rev     = strrev($str);
	$search_rev  = strrev($search);
	$replace_rev = strrev($replace);
	$pos = strpos($str_rev, $search_rev);
	if($pos !== FALSE) {
		return strrev(substr_replace($str_rev, $replace_rev, $pos, strlen($search)));
	} else {
		return $str;
	}
}
$asys_path = dirname($_SERVER["SCRIPT_FILENAME"]);
$asys_path_array = explode('/', $asys_path);
$asys_path_count = count($asys_path_array);
$asys_path = str_replace_last($asys_path_array[$asys_path_count - 1], '', $asys_path);
$asys_path = str_replace_last($asys_path_array[$asys_path_count - 2], '', $asys_path);
define('asys_path', $asys_path);
?>