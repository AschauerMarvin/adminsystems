<?php
$tpl->assign("_ROOT.page_title", $lang['l_modules_overview_title']);



if($asys['DEBUG_MODE']) asys_debug_output($db->asys_get_modules(), 'Modules');

$all_modules = $db->asys_get_modules();
// if mod id=1 (view mods) or user is administrator, set access to true
if($asys_user['asys_u_userid'] == 1){
	$have_access = true;
}

$tpl->newBlock('choose_menu');
foreach($all_modules as $value){

	$have_access = false;
	if($value['mod_allow_everyone'] == 1){
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
			$mods_permissions = $db->db_select_table($db_table['modules_permissions'], false, 'get_fields', "WHERE `mod_id`='{$value['ID']}' AND" . $user_groups_string);
			if($asys['DEBUG_MODE']) asys_debug_output($mods_permissions, 'Modules Permissions');
		}



		// if there is any group with access to this module, give the user access.
		foreach($mods_permissions as $mod_pm){
			if($mod_pm['mod_access_mode'] == 'allow'){
				$have_access = true;
			}
		}

		if(ckpm('access_administrator')){
			$have_access = true;
		}
	}

	if($have_access){
		
		if(is_file('../modules/' . $value['mod_file'] . '/' . 'image.png')){
			$value['mod_image'] = '../' . $value['mod_file'] . '/' . 'image.png';
			
		}else{
			$value['mod_image'] = '../' . $asys['cur_mod']['mod_file'] . '/' . 'default.png';
		}
		//echo '../' . $value['mod_file'] . '/' . 'image.png';
		$tpl->newBlock('choose_menu_entry');
		$tpl->assign(array('entry_url' => "modules.php?mod_id={$value['ID']}&action=modules", 'entry_img' => $dir['mod_files'] . $value['mod_file'] . '/' . $value['mod_image'], 'entry_name' => $value['mod_name'], 'overlib' => short_overlib_str($value['mod_desc'])));
	}
}
?>