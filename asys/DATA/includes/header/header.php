<?php
ob_start ();
// Includes
require_once 'asys_path.php';
require_once asys_path . 'asys_conf' . '/config.php';
session_name ($conf['asys_session']);
session_start ();
ob_end_flush ();
require_once '../' . asys_DATA_path . '/lib/db/asys_db_mysql_ext.php';
require_once '../' . asys_DATA_path . '/lib/xml/xml.php';


// connect to the database using the adminsystems database class
$db = new asys_db();

// load system configs into variable
$asys_conf = $db->asys_select_conf();

date_default_timezone_set($asys_conf['global_timezone']);

// get security variable and check its on or off
$security = $asys_conf['asys_security'];
if ($security == '1'){
	$security = TRUE;
}else{
	$security = FALSE;
}

// Setting the Charset to utf8
$db->db_q("SET NAMES 'utf8'");
$db->db_q("SET CHARACTER SET 'utf8'");

if(isset($_SESSION['user_id'])){
	$asys_user['asys_u_userid'] = $_SESSION['user_id']; // user ID
	$asys_user['asys_u_user'] = $_SESSION['user']; // username
	$asys_user['asys_u_ip'] = $_SESSION["ip_adress"]; // ip
	$asys_user['asys_u_useragent'] = $_SESSION["useragent"]; // useragent

	$asys_groups = $db->asys_get_groups($asys_user['asys_u_userid']);
	$asys_group['groups'] = array();
	foreach($asys_groups as $group){
		$asys_group['groups'][] = $group['group_id'];
	}
	$user_mode = true;
	$user_vars = $db->asys_select_usr_conf($asys_user['asys_u_userid']);
	$asys_user = array_merge($asys_user, $user_vars);
	@include_once $dir['lang_dir'] . $asys_user['user_asys_lang'] . '.php';
}else{
	$asys_user['asys_u_user'] = 'guest';
	$user_mode = false;
}

// get template - first time
if(isset($asys_user['user_tpl']) AND is_dir('../tpl/' . $asys_user['user_tpl'])){
	$used_tpl = $asys_user['user_tpl'];
}else{
	$used_tpl = $asys_conf['asys_tpl'];
}
if(!is_dir("../tpl/$used_tpl/")){
	$used_tpl = 'asys';
}
$asys['used_tpl'] = $used_tpl;
require_once '../' . asys_DATA_path . '/dir.php';
require_once $dir['system_dir'] . 'aboutversion.php';
require_once $dir['system_dir'] . 'system.php';
require_once $dir['system_dir'] . 'template.php';
$tpl = new TemplatePower("../tpl/$used_tpl/main.tpl");
$tpl->assignInclude('formarea',"../tpl/$used_tpl/formarea.tpl");
$tpl->assignInclude('table',"../tpl/$used_tpl/table.tpl");
$tpl->assignInclude('custom',"../tpl/$used_tpl/custom.tpl");
$tpl->assignInclude('site_about',"../tpl/$used_tpl/about.tpl");
$tpl->assignInclude('choose_menu',"../tpl/$used_tpl/choose.tpl");

$tpl->prepare();

$sitetitle = $asys_conf['asys_sitetitle'];

// generate global variables
$asys['var_action'] = isset($_GET['action'])?mod_str_for_post($_GET['action']):false; // adminsystems ?action variable
$varaction = $asys['var_action'];
$asys['var_mode'] = isset($_GET['mode'])?mod_str_for_post($_GET['mode']):false; // adminsystems ?mode variable
$varmode = $asys['var_mode']; // 3.5 varmode
$asys['var_id'] = isset($_GET['id'])?$db->escape(mod_str_for_post($_GET['id'])):false;


require_once $dir['lang_dir'] . 'en.php';

$languages = $db->asys_get_languages("WHERE `backend`='1' AND `language`='{$asys_conf['asys_language']}'");
if(isset($languages[0])){
	$used_lang = $languages[0]['lang'];
}else{
	$used_lang = 'en';
}

if(isset($asys_user['user_lang']) AND $asys_user['user_lang'] != $lang['l_global_default']){
	$languages = $db->asys_get_languages("WHERE `backend`='1' AND `language`='{$asys_user['user_lang']}'");
	if(isset($languages[0])){
		$used_lang = $languages[0]['lang'];
	}
}
@include_once $dir['lang_dir'] . $used_lang . '.php';

@include_once asys_path . 'asys_conf' . '/custom.php';

// Formarea global assigns
$tpl->assignGlobal(array('textarea_class' => 'ckeditor', 'textarea_colls' => 80, 'textarea_rows' => '10', 'form_type' => 'text', 'form_type' => 'text', 'lang_asys_error' => 'Asys error:'));

// load frontend template informations from xml file
if(is_file(asys_path . 'tpl/' . $asys_conf['page_tpl'] . '/template.xml')){
	$template_informations = XML2Array::createArray(file_get_contents(asys_path . 'tpl/' . $asys_conf['page_tpl'] . '/template.xml'));
}else{
	display_error($lang['e_nofrontendtpl']);
}

// load javascript
load_javascript();

// get mem-usage from php and save it into variable
$asys['asys_memory'] = memory_get_usage() / 1024;
$asys['asys_memory'] = floor($asys['asys_memory']);

// assign the variables for the template
$tpl->assignGlobal($asys);
$tpl->assignGlobal($asys_user);
$tpl->assignGlobal($asys_group);
$tpl->assignGlobal($dir);
$tpl->assignGlobal('sitetitle', $sitetitle);

// language variables
$tpl->assignGlobal($lang);

//  **Security Things**
if($user_mode AND $security){
	/*
	 *  Here Adminsystems check, if the security config active, the current user_agent and the ip adress
	*  and compare them with the current ip and user_agent. If they changed, Adminsystems will destroy the Session.
	*/

	if ($asys_user['asys_u_useragent'] != $_SERVER['HTTP_USER_AGENT'] OR $asys_user['asys_u_ip'] != $_SERVER['REMOTE_ADDR']){
		$db->asys_add_usr_conf($asys_user['asys_u_userid'], 'change_password', '1');
		session_unset ();
		session_destroy ();
		exit('Session destroyed. Security Alert.');
	}

}
if($asys['DEBUG_MODE']) asys_debug_output($asys, 'Asys global variables');
if($asys['DEBUG_MODE']) asys_debug_output($asys_conf, 'Asys config variables');

/*
 * Navigation Bar
*/

require_once $dir['system_dir'] . 'links.php';

// if the user is not logged-in
if(!$user_mode){
	nvbar($nbl['home']);
	nvbar($nbl['login']); // show login link
}else{
	// adminsystems modules-enabled menu
	if($asys_conf['asys_modules_menu'] == 1){
		nvbar($nbl['home']); // home
		nvbar_mods(true);
		nvbar($nbl['personal'], true);
		nvbar($nbl['combined']);
		subnav($nbl['personal'], true);
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_navman_view')) subnav($nbl['menus']); // menus
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_pages_edit') OR ckpm('access_pages_create') OR ckpm('access_pages_delete')) subnav($nbl['pages']); // pages
		if(ckpm('access_upload')) subnav($nbl['files']); // files
		if(ckpm('access_about')) subnav($nbl['about']); // about
		subnav($nbl['settings']);
		nvbar($nbl['logout']); // logout
	}else{
		// adminsystems default menu
		nvbar($nbl['home']); // home
		if($asys_conf['asys_hide_modules'] == 0) nvbar($nbl['modules']); // modules
		if($asys_conf['asys_hide_modules'] == 0) nvbar_mods();
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_navman_view')) nvbar($nbl['menus']); // menus
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_navman_view')) subnav($nbl['add_entry'], true);
		//if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_navman_view')) subnav($nbl['add_menus']);
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_navman_view')) subnav($nbl['man_menus']);
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_pages_edit') OR ckpm('access_pages_create') OR ckpm('access_pages_delete')) nvbar($nbl['pages']); // pages
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_pages_edit') OR ckpm('access_pages_create') OR ckpm('access_pages_delete')) subnav($nbl['create'], true);
		if($asys_conf['asys_hide_cms'] == 0) if(ckpm('access_pages_edit') OR ckpm('access_pages_create') OR ckpm('access_pages_delete')) subnav($nbl['edit']);
		if(ckpm('access_upload')) nvbar($nbl['files']); // files
		if(ckpm('access_upload')) subnav($nbl['add_files'], true);
		if(ckpm('access_upload')) subnav($nbl['man_files']);
		nvbar($nbl['settings']); // settings
		subnav($nbl['personal'], true);
		if(ckpm('access_system_administrator')) subnav($nbl['system']);
		if(ckpm('access_system_users')) subnav($nbl['users']);
		if(ckpm('access_system_administrator')) subnav($nbl['languages']);
		if(ckpm('access_system_administrator')) subnav($nbl['mod_settings']);
		if(ckpm('access_about')) nvbar($nbl['about']); // about
		nvbar($nbl['logout']); // logout
	}
}

// exit if no user is logged in
if (!$user_mode){
	if(!in_array(script_uri(),$conf['allow_without_login'])){
		tpl_title($lang['l_index_title']);
		display_error($lang['e_no_login'], true);
	}
}

require_once $dir['system_dir'] . 'permission_control.php';
?>
