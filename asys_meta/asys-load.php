<?php
define('asys_dir', 'asys');
define('asys_DATA_path', 'DATA');

// load adminsystems overall configuration
require_once 'asys_conf/config.php';

if(!is_file('asys_conf/installed')){
	header('Location: ' . asys_dir . '/install');
}

if(is_file('asys_conf/upgrade')){
	header('Location: ' . asys_dir . '/install');
}
// load adminsystems version information file
require_once asys_dir . '/' . asys_DATA_path . '/includes/system/aboutversion.php';

// load database class
require_once asys_dir . '/' . asys_DATA_path . '/lib/db/asys_db_mysql_ext.php';

// load xml class
require_once asys_dir . '/' . asys_DATA_path . '/lib/xml/xml.php';


// connect to the database using the adminsystems database class
$db = new asys_db();

// load system configs into variable
$asys_conf = $db->asys_select_conf();

define('tpl_path', 'tpl/' . $asys_conf['page_tpl'] . '/');
define('site_title', $asys_conf['page_sitetitle']);
define('slogan', $asys_conf['page_slogan']);
define('version', $asys['asys_version']);

// load template informations
if(is_file(tpl_path . 'template.xml')){
	$tpl_inf = XML2Array::createArray(file_get_contents(tpl_path . 'template.xml'));
}else{
	exit('Fatal error - No template.xml found');
}


// load frontend functions
require_once asys_dir . '/' . asys_DATA_path . '/lib/frontend/frontend.php';

// get page
if(isset($_GET['page'])){
	$asys['page'] = $db->escape(remove_all($_GET['page']));
}else{
	$asys['page'] = $asys_conf['page_home'];
}

// get content
if(isset($_GET['content'])){
	$asys['content'] = $db->escape(remove_all($_GET['content']));
}else{
	$asys['content'] = false;
}


$asys['page_description'] = $asys_conf['page_slogan'];

if(isset($asys['page'])){
	$nav_entry = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `entry_alias`='{$asys['page']}'");
	if(isset($nav_entry[0])){
		$nav_entry = $nav_entry[0];
		$get_pagetitle = true;
	}else{
		$get_pagetitle = false;
	}
	if($get_pagetitle){
		$asys['page_title'] = $nav_entry['entry_name'];
	}
}

// get language
if(isset($_GET['lang'])){
	$asys['lang'] = $db->escape(remove_all($_GET['lang']));
}else{
	if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && $_SERVER["HTTP_ACCEPT_LANGUAGE"] != "")
	{
		$requested_language = preg_split("/[\W]+/",$_SERVER["HTTP_ACCEPT_LANGUAGE"], 2);
		$requested_language[0] = strtolower($requested_language[0]);
	}
	if (preg_match('/^[\w]{2,3}$/', $requested_language[0]))
	{
		$asys['lang'] = $requested_language[0];
	}
	else
	{
		$asys['lang'] = $asys_conf['asys_language'];
	}

}

// check if the template file exists and load it
if(is_file(tpl_path . the_template())){
	include tpl_path . the_template();
}else{
	if(is_file(tpl_path . '404.php')){
		include tpl_path . '404.php';
	}elseif(is_file(tpl_path . $tpl_inf['template']['files']['filename'][0])){
		include tpl_path . $tpl_inf['template']['files']['filename'][0];
	}else{
		exit('Adminsystems ' . $asys['asys_version'] . ' critical error: <br /><br />Template Error - Default Template file not found and no template set!');
	}
}


?>