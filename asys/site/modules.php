<?php
include 'headerconfig.php';

// get the mod id... if there isn't any, use mod id 1
$MOD_ID = isset($_GET['mod_id'])?$db->escape($_GET['mod_id']):1; // get mod id

// now, assign the asys['cur_mod'] variable with the modules informations
$asys['cur_mod'] = $db->db_select_table($db_table['modules'], false, 'get_fields', "WHERE `ID`='$MOD_ID'");
$asys['cur_mod'] = isset($asys['cur_mod'][0])?$asys['cur_mod'][0]:false;

if($asys['cur_mod']['mod_allow_everyone'] == 1){
	$have_access = true;
}else{
	// at first, get the groups based on user id
	$user_groups = $db->asys_get_groups($asys_user['asys_u_userid']);

	// now, build an mysql string with the user ids
	$user_groups_string = '';
	for ($i = 0; $i < count($user_groups); $i++) {
		if($i == count($user_groups) - 1){
			$user_groups_string .= "`group_id` = '{$user_groups[$i]['group_id']}'";
		}else{
			$user_groups_string .= "`group_id` = '{$user_groups[$i]['group_id']}' OR ";
		}
	}

	if(count($user_groups) <= 0){
		$mods_permissions = array(array('mod_access_mode' => 'forbid'));
	}else{
		// now, get the mod permissions of this groups
		$mods_permissions = $db->db_select_table($db_table['modules_permissions'], false, 'get_fields', "WHERE `mod_id`='$MOD_ID' AND" . $user_groups_string);
		if($asys['DEBUG_MODE']) asys_debug_output($mods_permissions, 'Modules Permissions');
	}



	// if there is any group with access to this module, give the user access.
	$have_access = false;
	foreach($mods_permissions as $value){
		if($value['mod_access_mode'] == 'allow'){
			$have_access = true;
		}
	}

}

if(ckpm('access_administrator')){
	$have_access = true;
}

// if there is no group with access, stop here and tell the user that he has no access
if(!$have_access) tpl_error_msg($lang['e_access_denied'], $log_type = 'mod-access-denied', $log_msg = 'permission denied, try to access the module ID ' . $MOD_ID, true);

// include the modinfo file
$modinfo = array();
if(file_exists($dir['mod_files'] . $asys['cur_mod']['mod_file'] . '/modinfo.php')){
	include $dir['mod_files'] . $asys['cur_mod']['mod_file'] . '/modinfo.php';
}else{
	tpl_error_msg($lang['plugin_no_modinfo'], $log_type = 'modul-error', $log_msg = 'found no modinfo file for this plugin. Mod ID: ' . $asys['cur_mod']['ID'], true);
}

// load the modinfo variables into $asys['cur_mod']
if(isset($modinfo['MOD_VERSION'])){
$asys['cur_mod']['mod_version'] = isset($modinfo['MOD_VERSION'])?$modinfo['MOD_VERSION']:'3.6';
$asys['cur_mod']['mod_name'] = isset($modinfo['MOD_NAME'])?$modinfo['MOD_NAME']:'AsysModLoad';
$asys['cur_mod']['mod_for_asys'] = isset($modinfo['MOD_FOR_ASYS'])?$modinfo['MOD_FOR_ASYS']:$asys['asys_version'];
$asys['cur_mod']['mod_image'] = isset($modinfo['MOD_IMAGE'])?$modinfo['MOD_IMAGE']:'default';	
}else{
// for compatibility with the pre 2.4.1 (core) mod-vars - deprecated 
$asys['cur_mod']['mod_version'] = defined("MOD_VERSION")?MOD_VERSION:'3.6';
$asys['cur_mod']['mod_name'] = defined("MOD_NAME")?MOD_NAME:'AsysModLoad';
$asys['cur_mod']['mod_for_asys'] = defined("MOD_FOR_ASYS")?MOD_FOR_ASYS:$asys['asys_version'];
$asys['cur_mod']['mod_image'] = defined("MOD_IMAGE")?MOD_IMAGE:'default';	
}



// now, we use the mod name as page title
$tpl->assign("_ROOT.page_title", 'Mod: ' . $asys['cur_mod']['mod_name']);

// now, we check the version this module is for. if the version missmatch the current adminsystems version, send an warning.
$compatible_versions = array();

if(is_array($asys['cur_mod']['mod_for_asys'])){
	$compatible_versions = $asys['cur_mod']['mod_for_asys'];
}else{
	$compatible_versions[] = $asys['cur_mod']['mod_for_asys'];
}

if(!in_array($asys['asys_version'], $compatible_versions)){
	$tmp_strings   = array('{mod_for_asys}', '{asys_version}');
	$replace_with = array(implode(', ', $compatible_versions), $asys['asys_version']);
	$lang['plugin_compatibility'] = str_replace($tmp_strings, $replace_with, $lang['plugin_compatibility']);
	tpl_error_msg($lang['plugin_compatibility'], $log_type = 'modul-warning', $log_msg = 'this plugin may not compatible with the current adminsystems version! Mod ID: ' . $asys['cur_mod']['ID']);
}

// now, we include the main modules file
if(file_exists($dir['mod_files'] . $asys['cur_mod']['mod_file'] . '/modinfo.php')){
	include_once $dir['mod_files'] . $asys['cur_mod']['mod_file'] .'/' . $asys['cur_mod']['mod_file'] . '.php';
}else{
	tpl_error_msg($lang['plugin_not_found'], $log_type = 'modul-not-found', $log_msg = 'found no mainmod file for this plugin. Mod ID: ' . $asys['cur_mod']['ID'], true);
}

// now, show the modules version in the navbar
$tpl->assign("_ROOT.mod_info", ' | ' . ' ' . $asys['cur_mod']['mod_name'] . ' '. $asys['cur_mod']['mod_version']);


include 'footer.php';
?>