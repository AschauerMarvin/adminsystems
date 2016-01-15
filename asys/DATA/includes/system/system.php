<?php

/*
 * TEMPLATE FUNCTIONS
* Send the Output to the Template System
*/

// Send simple content (HTML or Text) to the Template
// function tpl_content($output){
// 	global $tpl;
// 	$tpl->newBlock("content");
// 	$tpl->assign("content", $output);
// }

// public adminsystems sites
$conf['allow_without_login'] = array(
		'index.php',
		'login.php',
		'login.php?action=login'
);


// 3.6 legacy
// will be removed in 4.1
function tpl_error_msg($err_msg, $log_type = 'default', $log_msg = 'generic', $exit = false, $logging = true){
	global $tpl;
	global $lang;
	global $db;
	$tpl->newBlock("errormsg");
	if(!$exit){
		$err_or_inf = 'info';
		$err_title = $lang['e_asys_error'];
		if($logging) $db->asys_log_push($log_type, $log_msg);
	}else{
		$err_or_inf = 'error';
		$err_title = $lang['e_asys_critical_error'];
		if($logging) $db->asys_log_push($log_type, $log_msg);
	}
	$tpl->assign(array('err_msg' => $err_msg, 'lang_asys_error' => $err_title, 'err_or_inf' => $err_or_inf));
	if($exit){
		$tpl->printToScreen();
		exit();
	}
}


function asys_inf_display($msg, $type){
	global $tpl;
	$tpl->newBlock('information');
	$tpl->assign(array('err_msg' => $msg, 'type' => $type));
}

// Display an error message
function display_error($msg, $exit = false){
	global $tpl;
	asys_inf_display($msg, 'error');
	if($exit){
		$tpl->printToScreen();
		exit();
	}
}

// Display an information
function display_information($msg){
	asys_inf_display($msg, 'info');
}

// Display an warning
function display_warning($msg){
	asys_inf_display($msg, 'warning');
}

// Display an success message
function display_success($msg){
	asys_inf_display($msg, 'success');
}

// log function
// New in: Adminsystems 3.7
function asys_log($log_type = 'generic', $log = 'empty'){
	global $db;
	$db->asys_log_push($log_type, $log);
}

// starts a formarea - example see below
function tpl_form_start($form_action, $form_method, $form_submit){
	global $tpl;
	if($form_method == ''){
		$form_method = 'post';
	}
	$tpl->newBlock("form");
	$tpl->assign(array('form_action' => $form_action, 'form_method' => $form_method, 'form_submit' => $form_submit));
}

function tpl_form_title($title, $top = false){
	global $tpl;
	if($top){
		$block = 'form_top_title';
	}else{
		$block = 'form_title';
	}
	$tpl->newBlock($block);
	$tpl->assign('form_title', $title);

}

function tpl_form_desc($text, $position = 'top'){
	global $tpl;
	$tpl->newBlock('form_text_' . $position);
	$tpl->assign('text', $text);
}

// text-formarea
function tpl_form_text($form_name, $form_value, $form_desc, $form_type = 'text'){
	global $tpl;
	$tpl->newBlock("formtext");
	$tpl->assign(array('form_name' => $form_name, 'form_value' => $form_value, 'form_desc' => $form_desc, 'form_type' => $form_type));
}

function tpl_form_check($form_checked, $form_name, $form_value, $form_desc){
	global $tpl;
	$tpl->newBlock("formcheck");
	$tpl->assign(array('form_checked' => $form_checked ,'form_type' => 'checkbox', 'form_name' => $form_name, 'form_value' => $form_value, 'form_desc' => $form_desc));
	$tpl->assign('br', '<br />');
}

function tpl_selectform_start($desc, $form_name, $form_size){
	global $tpl;
	$tpl->newBlock("selectform");
	$tpl->assign(array('form_desc' => $desc, 'form_name' => $form_name, 'form_size' => $form_size));
}

function tpl_selectform_option($value, $selected = false){
	global $tpl;
	$tpl->newBlock("selectformoption");
	$tpl->assign('form_value', $value);
	if($selected) $tpl->assign('selected', 'selected="selected"');
}

function tpl_form_textarea($form_desc, $form_name, $textarea_class = 'editor', $formarea_id = 'editor'){
	global $tpl;
	if($textarea_class == ''){
		$textarea_class = ckeditor;
	}
	if($formarea_id == '') $formarea_id = rand();
	$tpl->newBlock("textarea");
	$tpl->assign(array('form_name' => $form_name, 'textarea_class' => $textarea_class, 'form_desc' => $form_desc, 'textarea_id' => $formarea_id));
}

// starts a Table - example see below
function tpl_table_start($table_id = 'asys_table', $table_class = 'tablesorter', $table_text = ''){
	global $tpl;
	$tpl->newBlock("table");
	$tpl->assign(array('table_id' => $table_id, 'table_class' => $table_class, 'table_text' => $table_text));
}

// Table Header - Needs tpl_table_start() before
function tpl_table_header($output){
	global $tpl;
	$tpl->newBlock("tablehead");
	$tpl->assign("table_header", $output);
}

// Table Row - Needs tpl_table_start() before
function tpl_table_row_start(){
	global $tpl;
	$tpl->newBlock("tablerow");
}

function tpl_table_row_content($output, $colspan = ''){
	global $tpl;
	$tpl->newBlock("tablecontent");
	$tpl->assign("table_content", $output);
	$tpl->assign("colspan", $colspan);
}

// IMG (link) as a Table Row
// Needs; $dobutton_href; The target-link
// Needs; $dobutton_alt; The alt-img description
// Needs; $dobutton_src; The source of the img
function tpl_table_button($dobutton_href, $dobutton_alt, $button_file){
	global $tpl;
	$tpl->newBlock("dobutton");
	$tpl->assign(array('dobutton_href' => $dobutton_href, 'dobutton_alt' => $dobutton_alt, 'button_file' => $button_file . '.png', 'doobutton_desc' => $dobutton_alt));
}

function tpl_content($content, $br = true){
	global $tpl;
	if(!$br){
		$begin_content = 'nobr';
	}else{
		$begin_content = '';
	}
	$tpl->newBlock($begin_content . 'content');
	$tpl->assign('content', $content);
}

function tpl_title($title){
	global $tpl;
	$tpl->assign("_ROOT.page_title", $title);

}

function tpl_choose_start($title = false){
	global $tpl;
	$tpl->newBlock('choose_menu');
	if($title != false){
		$tpl->newBlock('choose_title');
		$tpl->assign('choose_title', $title);
	}
}

function tpl_newblock($blockname = false, $assign_indexes = false, $assign_values = false){
	global $tpl;
	if($blockname != false){
		$tpl->newBlock($blockname);
	}
	if($assign_indexes != false){
		$tpl->assign($assign_indexes, $assign_values);
	}
}

function tpl_choose_entry($target_url, $name, $image = 'default.png', $overlib = ''){
	global $dir;
	global $tpl;
	$tpl->newBlock('choose_menu_entry');
	$tpl->assign(array('entry_url' => $target_url, 'entry_name' => $name, 'entry_img' => $dir['layout_img'] . $image, 'overlib' => $overlib));
}

function tpl_choose_entry_a($params){
	global $dir;
	global $tpl;
	$tpl->newBlock('choose_menu_entry');
	$tpl->assign($params);
}

// Example Table with tpl_table Functions

//// Start the Table..
//tpl_table_start();
//// Set 2 Table Headers
//tpl_table_header("Header 1");
//tpl_table_header("Header 2");
//// 2 Table Rows
//tpl_table_row_start();
//tpl_table_row_content("Row 1");
//tpl_table_row_content("Row 2");

function nvbar($url){
	global $tpl;
	$tpl->newBlock("navbar");
	$tpl->assign($url);
}

function subnav($url, $begin = false){
	global $tpl;
	if($begin){
		$tpl->newBlock("subnav");
	}else{
	}
	$tpl->newBlock("subnavelement");
	$tpl->assign($url);
}

function nvbar_mods($modules_menu = false){
	global $db;
	global $asys_user;
	global $db_table;
	global $asys;
	// generate modules dropdown
	$installed_mods = $db->asys_get_modules();
	$showed_mods = array();

	foreach($installed_mods as $value){
		$have_access = false;
		if($value['mod_allow_everyone'] == 1){
			$have_access = true;
		}else{
			// at first, get the groups based on user id
			$user_groups = $db->asys_get_groups($asys_user['asys_u_userid']);

			// now, build an mysql string with the user ids
			$user_groups_string = array();
			for ($i = 0; $i < count($user_groups); $i++) {
				$user_groups_string[] = $user_groups[$i]['group_id'];
			}
			$user_groups_string = implode(', ', $user_groups_string);

			if(count($user_groups) <= 0){
				$mods_permissions = array(array('mod_access_mode' => 'forbid'));
			}else{
				// now, get the mod permissions of this groups
				$mods_permissions = $db->db_select_table($db_table['modules_permissions'], false, 'get_fields', "WHERE `mod_id`='{$value['ID']}' AND `group_id` IN (". $user_groups_string .")");
				if($asys['DEBUG_MODE']) asys_debug_output($mods_permissions, 'Modules Permissions');
			}

			// if there is any group with access to this module, give the user access.
			foreach($mods_permissions as $mod_pm){
				if($mod_pm['mod_access_mode'] == 'allow'){
					$have_access = true;

				}
			}

		}

		if(ckpm('access_administrator')){
			$have_access = true;
		}

		if($have_access){
			$showed_mods[] = $value;
		}
	}
	if($modules_menu){
		$i = 1;
		$var_start = 1;
	}else{
		$i = 0;
		$var_start = 0;
	}
	for($i;$i < count($showed_mods);$i++){
		if($i == $var_start){
			if($modules_menu == false){
				subnav(array('href' => "modules.php?mod_id={$showed_mods[$i]['ID']}&amp;action=modules", 'navbar_name' => $showed_mods[$i]['mod_name'], 'overlib' => mouseover_headerlink($showed_mods[$i]['mod_desc'])), true);
			}else{
				nvbar(array('href' => "modules.php?mod_id={$showed_mods[$i]['ID']}&amp;action=modules", 'navbar_name' => $showed_mods[$i]['mod_name'], 'overlib' => mouseover_headerlink($showed_mods[$i]['mod_desc'])));
			}
		}else{
			if($modules_menu == false){
				subnav(array('href' => "modules.php?mod_id={$showed_mods[$i]['ID']}&amp;action=modules", 'navbar_name' => $showed_mods[$i]['mod_name'], 'overlib' => mouseover_headerlink($showed_mods[$i]['mod_desc'])));
			}else{
				nvbar(array('href' => "modules.php?mod_id={$showed_mods[$i]['ID']}&amp;action=modules", 'navbar_name' => $showed_mods[$i]['mod_name'], 'overlib' => mouseover_headerlink($showed_mods[$i]['mod_desc'])));
			}
		}
	}
}

function load_javascript(){
	$load_scripts = '';
	$load_scripts .= '<script type="text/javascript" src="../DATA/ckeditor/ckeditor.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/asys_mini.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/lib/jquery/jquery.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/lib/plupload/plupload.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/lib/plupload/plupload.html4.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/lib/plupload/plupload.html5.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript" src="../DATA/lib/jquery/tablesorter.js"></script>' . "\n";
	$load_scripts .= '<script type="text/javascript">' . "\n" . '$(document).ready(function()
	{
			$("#asys_table").tablesorter({sortList: [[0,0]]});
}
			);' . "\n" . '</script>' . "\n";


	tpl_newblock(false, 'load_javascript', $load_scripts);
	return $load_scripts;
}

function run_js($javascript){
	return '<script type="text/javascript">' . "\n" . $javascript . '</script>' . "\n";
}

/*
 * Checks a directory
* If not exists, this function create it and change chmod
*/

function asys_chk_dir($dir, $chmod){
	if (!is_dir($dir))
	{
		mkdir ($dir);
	}
	// disabled
	//chmod ($dir, 0 . $chmod);

}

/* Asys write cache
 * Stores a simple File (e.g. Variable, array, db entry, etc.) in system_cache directory
* $content; the content to cache
* $name; the file-name
* $mode; fopen mode
*/

function asys_write_cache($content, $name, $mode){
	global $dir;
	$handle=fopen($dir['system_cache'] . $name, $mode);
	fwrite($handle, $content);
	fclose($handle);
}

/* Asys read cache
 * return a simple cache-file from system_cache directory
* $name; the file-name
*/
function asys_read_cache($name){
	global $dir;
	$handle=fopen($dir['system_cache'] . $name, "r");
	$content = fgets($handle);
	fclose($handle);
	return $content;
}

function asys_del_cache($name){
	global $dir;
	return unlink($dir['system_cache'] . $name);
}

function asys_clean_cache(){
	global $dir;
	$handle=opendir($dir['system_cache']);
	while($data=readdir($handle))
	{
		if(!is_dir($data) && $data!="." && $data!="..") unlink($dir['system_cache'] . $data);
	}
	closedir($handle);
}
/* Asys read cache
 * return a simple cache-file from system_cache directory
* $name; the file-name
*/
function asys_cache_filetime($name, $date){
	global $dir;
	if ($date == '') {
		$date = "d.m H:i";
	}
	return date ($date, filemtime($dir['system_cache'] . $name));
}

/*
 * Gets the current page URL
*/
function asys_get_url($hide_uri = false) {
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	if($hide_uri == true){
		$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"] .$port;
	}else{
		$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"] .$port.$_SERVER["REQUEST_URI"];
	}

	return $url;
}

function script_uri($get_vars = true){
	if($get_vars == false){
		$break = explode('/', $_SERVER["SCRIPT_NAME"]);
		return $break[count($break) - 1];
	}else{
		$break = explode('/', $_SERVER['REQUEST_URI']);
		return $break[count($break) - 1];
	}
}

function page_uri() {
	return 'http://' . $_SERVER['SERVER_NAME'] . '/';
}

function ckpm($permission){
	if(!isset($_SESSION[$permission])){
		$permission = false;
	}else{
		if($_SESSION[$permission] == 1){
			$permission = true;
		}else{
			$permission = false;
		}
	}
	global $asys_user;
	if($asys_user['asys_u_userid'] == 1){
		$permission = true;
	}
	global $asys_group;
	if(in_array(1, $asys_group['groups'])){
		$permission = true;
	}
	if(isset($_SESSION['access_system_administrator']) AND $_SESSION['access_system_administrator'] == 1){
		$permission = true;
	}
	return $permission;
}

function acdn(){
	global $tpl;
	global $lang;
	tpl_title($lang['e_access_denied_title']);
	tpl_error_msg($lang['e_access_denied'], 'access-denied', '', true, false);
}

function str_contains($string, $search){
	if(strpos($string,$search)!==false){
		return true;
	}else{
		return false;
	}
}

function show_phpinfo(){
	global $dir;
	$info_name = sha1(base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36));
	asys_write_cache('<?php phpinfo(); ?>', $info_name . '.php', 'a');
	asys_debug_output('<iframe src="' . $dir['system_cache'] . $info_name . '.php' . '" frameborder="no" name="phpinfo" width="900" height="900"></iframe>');
}

/*
 * Remove Code from a string, used for overlib-popups
* Needs; A String and a count (how long?)
* Returns; the new string
*/

function prepare_overlib($str, $count = 500){
	$allowed = "/[^a-z ><,%&\/;?:.! 0-9\\040]/i";
	$str = strip_tags($str);
	$str = substr($str, 0, $count);
	$str = preg_replace($allowed," ",$str);

	return $str;
}

/*
 * Removes HTML-Code from a string
* Returns the new string
*/

function no_html($str){
	$plattern   = array('<p>', '</p>', '<br />', '<br>');
	$replace_with = array('&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;');
	$str = str_replace($plattern, $replace_with, $str);
	$str = strip_tags($str);
	return $str;
}

/*
 * Removes some critical Strings in Formareas
* Returns the new string
*/

function prepare_formarea($str){
	$patterns[0] = '/ä/';
	$patterns[1] = '/Ä/';
	$patterns[2] = '/ö/';
	$patterns[3] = '/Ö/';
	$patterns[4] = '/ü/';
	$patterns[5] = '/Ü/';
	$patterns[6] = '/ß/';
	$patterns[7] = '/[^a-zA-Z0-9 \/;%&?_:.!-]/u';
	$replacements[0] = "&auml;";
	$replacements[1] = "&Auml;";
	$replacements[2] = "&ouml;";
	$replacements[3] = "&Ouml;";
	$replacements[4] = "&uuml;";
	$replacements[5] = "&Uuml;";
	$replacements[6] = "&szlig;";
	$replacements[7] = "";
	$str = preg_replace($patterns, $replacements, utf8_decode($str));
	return $str;
}

function remove_all($str){
	return preg_replace('/[^a-z0-9_-]/isU', '', $str);
}

/*
 * Generate a yes or no image in userlist
* Used in; System Userlist
* Needs; 1 or 0
* Returns; HTML of the IMG
*/

function i_cant_read($str){
	global $dir;
	global $lang;
	if ($str == 0){
		$image = '<span style="display:none">0</span><img alt="'. $lang['l_global_no'] .'" title="'. $lang['l_global_no'] .'" src="'. $dir['layout_icon'] . 'no.png" /> ';
	}elseif($str == 1){
		$image = '<span style="display:none">1</span><img alt="'. $lang['l_global_yes'] .'" title="'. $lang['l_global_yes'] .'" src="'. $dir['layout_icon'] . 'yes.png" /> ';
	}else{
		$image = '<span style="display:none">0</span><img alt="'. $lang['l_global_no'] .'" title="'. $lang['l_global_no'] .'" src="'. $dir['layout_icon'] . 'no.png" /> ';
	}
	return $image;
}


/*
 * Removes some keys (Adminsystems Syntax) with Q'n'D commands
*/

function str_to_asys($str){
	$patterns = array();
	$patterns[0] = '/{unix}/';
	$patterns[1] = '/{date}/';
	$patterns[2] = '/{time}/';
	$replacements = array();
	$replacements[0] = time();
	$replacements[1] = date("d.m.Y");
	$replacements[2] = date("H:i:s");
	$asys_txt = preg_replace($patterns, $replacements, $str);
	$umlaute   = array('Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß');
	$replace_with = array('&Auml;', '&Ouml;', '&Uuml;', '&auml;', '&ouml;', '&uuml;', '&szlig;');
	$asys_txt = str_replace($umlaute, $replace_with, $asys_txt);
	return $asys_txt;
}

/*
 * Removes some keys and escape the string.
* Used in some modules
* More in 3.6
*/

function mod_str_for_post($str){
	$str = prepare_formarea($str);
	$umlaute   = array('Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß');
	$replace_with = array('&Auml;', '&Ouml;', '&Uuml;', '&auml;', '&ouml;', '&uuml;', '&szlig;');
	$str = str_replace($umlaute, $replace_with, $str);
	return $str;
}

function asys_get($var, $else = false){
	global $db;
	if(isset($_GET[$var])){
		return $db->escape(mod_str_for_post($_GET[$var]));
	}else{
		return $else;
	}
}

function asys_post($var, $else = false){
	global $db;
	if(isset($_POST[$var])){
		return $db->escape(mod_str_for_post($_POST[$var]));
	}else{
		return $else;
	}
}

function asys_post_nonreplace($var, $else = false){
	global $db;
	if(isset($_POST[$var])){
		return $db->escape($_POST[$var]);
	}else{
		return $else;
	}
}

// Simple HTML-IMG Tag

function img($src, $alt = 'img', $class = 'img'){
	return "<img src=\"$src\" alt=\"$alt\" class=\"$class\" />";
}

function url($href, $name, $div = ''){
	if($div != ''){
		$start_tag = '<div class="'. $div . '">';
		$close_tag = '</div>';
	}else{
		$start_tag = '';
		$close_tag = '';
	}
	return  $start_tag . "<a href=\"$href\">$name</a>" . $close_tag;
}

function dobutton($do, $href, $desc = '', $class = '', $class2 = ''){
	global $dir;
	global $lang;
	if($do == 'drop.png' OR $do == 'drop_old.png'){
		$href = $href . '" onClick="return confirm(\'' . $lang['l_global_deletemsg'] . '\')';
	}
	return url($href, img($dir['layout_icon'] . $do, $desc, $class) . $desc, $class2);
}

/*
 * Checkbox Handling
* chk; 1 or 0/blank
* Mode 1: Insert into DB
*    Returns 1 if the checkbox is checked
*    Returns 0 if the checkbox is not checked
* Mode 2: From DB to Checkbox
*    Returns FALSE if the checkbox is not checked
*    Returns TRUE if the checkbox is checked
*/

function chk_checkbox($chk, $mode){
	if ($mode == 1){
		if ($chk != ''){
			$checked = 1;
		}else{
			$checked = 0;
		}
	}elseif($mode == 2){
		if ($chk == 1){
			$checked = 'checked="checked"';
		}else{
			$checked = FALSE;
		}
	}elseif($mode == 3){
		$checked = 'checked="checked"';
	}
	return $checked;
}

/*
 * Function to get the Extension of a File
* Needs; File-Name
* Returns; Extension
*/
function get_file_ext($name){
	$pathinfo = pathinfo($name);
	$extension = pathinfo($name, PATHINFO_EXTENSION);
	return $extension;
}

/*
 * Overlib-Text / Link
* Use function prepare_overlib, so no HTML-Input in Text allowed
* Generate an HTML-Link with OverLib
* Needs; $link - The href (target) of the link, use # for no target.
* Needs; $str - The OverLib-String
* Needs; $name - The Showed Text
*/
function overlib_str($link, $str, $name){
	$str = prepare_overlib($str, 999);
	$overlib_link = "<span class=\"linked\" onmouseover=\"return overlib('$str');\" onmouseout=\"return nd();\">$name</span>";

	return $overlib_link;
}

function short_overlib_str($str){
	$str = prepare_overlib($str, 999);
	$overlib_link = "onmouseover=\"return overlib('$str');\" onmouseout=\"return nd();\"";

	return $overlib_link;
}

function mouseover_headerlink($desc){
	$overlib = 'onmouseover="return overlib(\'' . $desc . '\');" onmouseout="return nd();"';
	return $overlib;
}

/*
 * Debug Information Output
*/

function asys_debug_output($value, $name = ''){
	global $tpl;
	$tpl->newBlock('debug_element');
	$tpl->assign('debug_element', print_r($value, true));
	$tpl->assign('debug_id', $name);

}

function asys_upload_area($name, $target, $submit){
	return "<form action=\"$target\" method=\"post\" enctype=\"multipart/form-data\"> \n <input name=\"$name\" type=\"file\" /><br /> \n <input type=\"submit\" value=\"$submit\" />";
}

function asys_upload($file, $url = FALSE, $target_dir = FALSE, $nameset = FALSE){
	$file_name = '';
	$file_extension = '';

	$file_array = explode('.', $file['name']);

	$file_array_c = count($file_array);

	foreach($file_array as $key => $value){
		if ($key == $file_array_c - 1){
			$file_extension .= $value;
		}else{
			$file_name .= $value;
		}
	}

	global $conf;
	if(!in_array($file_extension, $conf['upload_allowed_extensions'])){
		global $lang;
		tpl_error_msg($lang['error_upload_failed_notallowed'], '', '', true, false);
	}

	if(!$target_dir){
		$target_dir = '../../' . $conf['upload_dir'] . '/';
	}

	// set new dynamic file-name
	$file_names = array();
	foreach (glob("{$target_dir}{$file_name}*") as $saved_files) {
		$file_names[] = $saved_files;
	}

	$name_success = 0;
	$file_count = $conf['upload_start_count'];
	if(count($file_names) == 0){
		$used_filename = $target_dir . $file_name  . '.' . $file_extension;
		$name_success = 1;
	}

	while($name_success < 1){
		$used_filename = $target_dir . $file_name . '_' . $file_count . '.' . $file_extension;
		if(in_array($used_filename, $file_names)){
			$file_count++;
		}else{
			$name_success++;
			$used_filename = str_replace($target_dir, '', $used_filename);
		}
	}

	// set target path
	$target_dir = $target_dir . basename($used_filename);

	// so then - upload file
	if(move_uploaded_file($file['tmp_name'], $target_dir)) {
		if($conf['overwrite_url']){
			$file_url = $conf['base_url'] . $conf['upload_dir'] . '/';
		}else{
			$file_url = page_uri() . $conf['upload_dir'] . '/';
		}
		$return = $file_url . basename($used_filename);
	} else{
		tpl_error_msg('error', '', '', true, false);
	}

	return $return;
}


function read_dir($dir, $only_dirs = false){
	// create an array to hold directory list
	$results = array();
	// create a handler for the directory
	$handler = opendir($dir);
	// open directory and walk through the filenames
	while ($file = readdir($handler)) {
		// if file isn't this directory or its parent, add it to the results
		if ($file != "." && $file != "..") {
			if($only_dirs){
				if(is_dir($dir . $file)){
					$results[] = $file;
				}
			}else{
				$results[] = $file;
			}
		}
	}
	// tidy up: close the handler
	closedir($handler);
	// done!
	return $results;
}

function format_bytes($bytes, $round = 2) {
	if ($bytes < 1024) return $bytes.' B';
	elseif ($bytes < 1048576) return round($bytes / 1024, $round).' KiB';
	elseif ($bytes < 1073741824) return round($bytes / 1048576, $round).' MiB';
	elseif ($bytes < 1099511627776) return round($bytes / 1073741824, $round).' GiB';
	else return round($bytes / 1099511627776, $round).' TiB';
}

function asys_filesize($file, $round = 2)
{
	$filesize = ($file && @is_file($file)) ? filesize($file) : NULL;

	return format_bytes($filesize, $round);
}

function asys_deldir($dirPath) {
	if (! is_dir($dirPath)) {
		asys_debug_output('error');
	}else{
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				asys_deldir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
}

function asys_get_templates($frontend = false){
	global $dir;
	if($frontend){
		$read_dir = $dir['layout_page'];
	}else{
		$read_dir = $dir['layout'];
	}
	return read_dir($read_dir, true);
}

function asys_get_languages(){
	global $dir;
	return read_dir($dir['lang_dir']);
}

function ext_loaded($name){
	if (extension_loaded($name)) {
		return true;
	}else{
		return false;
	}
}

/*
 * The Adminsystems PW Encryption
* Version: 2
* Added in Adminsystems 4.0.0
*/
function asys_encrypt($password, $check = false, $version = '2'){
	$string_1 = '$eJ&2Nz*3-rh{FWMQ~sQ.2NW5htempBat!#\qs(}n*$#AMF{AXxxq!]\[c$-rs+J#';
	$string_2 = '~uge,$t#)ktsR,Rev)v+Ye(3-8[n4teSLnWKb)C}%U,GTQM)w7!/@PN8nw\}N$z%b';

	if(!$check){
		$salt='$1$';
		$base64_alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'abcdefghijklmnopqrstuvwxyz0123456789+/';

		for($i=0; $i<25; $i++){
			$salt.=$base64_alphabet[rand(0,63)];
		}
		$salt.='$';
	}else{
		$salt = $check;
	}

	$password = crypt($string_2 . $password . $string_2 . $string_1, $salt);

	return $password;
}



define('test', 'This is a test output from adminsystems core');

?>