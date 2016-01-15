<?php
include 'headerconfig.php';
// choose overview
if(!$asys['var_action']){
	tpl_title($lang['l_system_overview_title']);

	$tpl->newBlock('choose_menu');
	foreach($nbl['system_settings'] as $value){
		$tpl->newBlock('choose_menu_entry');
		$tpl->assign($value);
	}
}

// personal settings
if($asys['var_action'] == 'personal'){

	// save personal settings
	if($varmode == 'save'){
		$db->asys_renew_usr_conf($asys_user['asys_u_userid'], 'user_tpl', $db->escape($_POST['tpl']));
		$db->asys_renew_usr_conf($asys_user['asys_u_userid'], 'user_lang', $db->escape($_POST['lang']));
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
		if($_POST['password'] != ''){
			if($_POST['password'] == $_POST['password2']){
				$new_password = asys_encrypt($_POST['password']);
				$db->db_update($db_table['users'], array($new_password), "WHERE `ID`='{$asys_user['asys_u_userid']}'", array('password'));
				$password_info = 'success';
				tpl_error_msg($lang['l_system_personal_pwchanged'], '', '', false, false);
			}else{
				$password_info = 'mismatch';
				tpl_error_msg($lang['l_system_personal_notmatch'], '', '', false, false);
			}
		}else{
			$password_info = 'none';
		}

	}


	tpl_title($lang['l_system_personal_title']);
	tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));
	tpl_content(img($dir['layout_system'] . 'personal_settings.png', ''));
	tpl_form_start(script_uri(false) . '?action=personal&amp;mode=save', 'post', $lang['l_global_action_save']);
	tpl_form_desc('<h4>' . $lang['l_system_personal_userinfo'] . ': ' . $asys_user['asys_u_user'] . '</h4>');
	tpl_form_text('password', '', $lang['l_login_form_password'], 'password');
	tpl_form_title($lang['l_system_personal_changepw']);
	tpl_form_text('password2', '', $lang['l_login_form_password2'], 'password');

	$user_config = $asys_user;
	$templates = asys_get_templates();
	tpl_selectform_start($lang['l_system_personal_tpl'], 'tpl', 1);
	tpl_form_desc('<h5>' . $lang['l_system_personal_configure'] . '</h5>', 'select');
	$selected = isset($user_config['user_tpl'])?$user_config['user_tpl']:'';

	tpl_selectform_option($lang['l_global_default']);
	foreach($templates as $value){
		if($selected == $value){
			$select = true;
		}else{
			$select = false;
		}
		tpl_selectform_option($value, $select);
	}

	$selected = isset($user_config['user_lang'])?$user_config['user_lang']:'';
	$languages = $db->asys_get_languages();
	tpl_selectform_start($lang['l_system_personal_lang'], 'lang', 1);
	tpl_selectform_option($lang['l_global_default']);
	foreach($languages as $value){
		if($selected == $value['language']){
			$select = true;
		}else{
			$select = false;
		}
		tpl_selectform_option($value['language'], $select);
	}
}


// system settings
if($varaction == 'system'){
	if($varmode == 'save'){
		foreach($_POST as $key => $value){
			$value = $db->escape($value);
			if($value == 'yes'){
				$value = 1;
			}
			$db->asys_renew_conf('system', $key, $value);
		}
		if(!isset($_POST['asys_hide_cms'])) $db->asys_renew_conf('system', 'asys_hide_cms', 0);
		if(!isset($_POST['asys_security'])) $db->asys_renew_conf('system', 'asys_security', 0);
		if(!isset($_POST['asys_hide_modules'])) $db->asys_renew_conf('system', 'asys_hide_modules', 0);
		if(!isset($_POST['asys_modules_menu'])) $db->asys_renew_conf('system', 'asys_modules_menu', 0);
		if(!isset($_POST['clean_pagenames'])) $db->asys_renew_conf('system', 'clean_pagenames', 0);
		if(!isset($_POST['startpage_dashboard'])) $db->asys_renew_conf('system', 'startpage_dashboard', 0);
		if(!isset($_POST['ldap_auth'])) $db->asys_renew_conf('system', 'ldap_auth', 0);
		$asys_conf = $db->asys_select_conf();
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	tpl_title($lang['l_system_settings_title']);
	tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));
	tpl_content(img($dir['layout_system'] . 'system_settings.png', ''));
	tpl_form_start(script_uri(false) . '?action=system&amp;mode=save', 'post', $lang['l_global_action_save']);
	tpl_form_desc('<h4>' . $lang['l_system_settings_title'] . '</h4>');

	$templates = asys_get_templates();
	tpl_selectform_start($lang['l_system_settings_tpl'], 'asys_tpl', 1);
	tpl_form_desc('<h5>' . $lang['l_system_settings_configure'] . '</h5>', 'select');
	$selected = isset($asys_conf['asys_tpl'])?$asys_conf['asys_tpl']:'';
	foreach($templates as $value){
		if($selected == $value){
			$select = true;
		}else{
			$select = false;
		}
		tpl_selectform_option($value, $select);
	}

	$languages = $db->asys_get_languages();
	tpl_selectform_start($lang['l_system_settings_lang'], 'asys_language', 1);
	$selected = isset($asys_conf['asys_language'])?$asys_conf['asys_language']:'';
	foreach($languages as $value){
		if($selected == $value['language']){
			$select = true;
		}else{
			$select = false;
		}
		tpl_selectform_option($value['language'], $select);
	}

	tpl_selectform_start($lang['l_system_settings_pagetpl'], 'page_tpl', 1);
	tpl_form_desc('<h5>' . $lang['l_system_settings_confpagetpl'] . '</h5>', 'select');
	$selected = isset($asys_conf['page_tpl'])?$asys_conf['page_tpl']:'';
	$templates = asys_get_templates(true);
	foreach($templates as $value){
		if($selected == $value){
			$select = true;
		}else{
			$select = false;
		}
		tpl_selectform_option($value, $select);
	}

	tpl_form_check(chk_checkbox($asys_conf['asys_security'], 2), 'asys_security', 'yes', overlib_str('#', $lang['l_system_settings_b_security_desc'], $lang['l_system_settings_b_security']));
	tpl_form_desc('<h5>' . $lang['l_system_settings_backend_configure'] . '</h5>', 'check');
	tpl_form_check(chk_checkbox($asys_conf['asys_hide_modules'], 2), 'asys_hide_modules', 'yes', overlib_str('#', $lang['l_system_settings_b_hidemods_desc'], $lang['l_system_settings_b_hidemods']));
	tpl_form_check(chk_checkbox($asys_conf['asys_modules_menu'], 2), 'asys_modules_menu', 'yes', overlib_str('#', $lang['l_system_settings_b_modmenu_desc'], $lang['l_system_settings_b_modmenu']));
	tpl_form_check(chk_checkbox($asys_conf['asys_hide_cms'], 2), 'asys_hide_cms', 'yes', overlib_str('#', $lang['l_system_settings_b_hidecms_desc'], $lang['l_system_settings_b_hidecms']));
	tpl_form_check(chk_checkbox($asys_conf['clean_pagenames'], 2), 'clean_pagenames', 'yes', overlib_str('#', $lang['l_system_settings_b_cleanpages_desc'], $lang['l_system_settings_b_cleanpages']));
	tpl_form_check(chk_checkbox($asys_conf['startpage_dashboard'], 2), 'startpage_dashboard', 'yes', overlib_str('#', $lang['l_system_settings_b_dashboard_desc'], $lang['l_system_settings_b_dashboard']));

	tpl_form_text('asys_sitetitle', $asys_conf['asys_sitetitle'], $lang['l_system_settings_conf_pagename']);
	tpl_form_desc('<h5>' . $lang['l_system_settings_conf'] . '</h5>', 'form');
	tpl_form_text('global_timezone', $asys_conf['global_timezone'], $lang['l_system_settings_conf_timezone']);
	tpl_form_text('page_home', $asys_conf['page_home'], overlib_str('#', $lang['l_system_settings_conf_homepage_desc'], $lang['l_system_settings_conf_homepage']));

	tpl_form_text('page_sitetitle', $asys_conf['page_sitetitle'], $lang['l_system_settings_conff_pagetitle']);
	tpl_form_desc('<h5>' . $lang['l_system_settings_conff_title'] . '</h5>', 'form');
	tpl_form_text('page_slogan', $asys_conf['page_slogan'], $lang['l_system_settings_conff_pageslogan']);

	if(ext_loaded('ldap')){
		if(!isset($asys_conf['ldap_auth'])) $asys_conf['ldap_auth'] = 0;
		tpl_form_check(chk_checkbox($asys_conf['ldap_auth'], 2), 'ldap_auth', 1, overlib_str('#', $lang['l_system_settings_conf_ldap_desc'], $lang['l_system_settings_conf_ldap']));

		if($asys_conf['ldap_auth'] == 1){
			if(!isset($asys_conf['ldap_accountSuffix'])) $asys_conf['ldap_accountSuffix'] = '@mydomain.local';
			tpl_form_text('ldap_accountSuffix', $asys_conf['ldap_accountSuffix'], 'accountSuffix');
			tpl_form_desc('<h5>' . $lang['l_system_settings_conf_ldap_title'] . '</h5>', 'form');
			if(!isset($asys_conf['ldap_baseDn'])) $asys_conf['ldap_baseDn'] = 'DC=mydomain,DC=local';
			tpl_form_text('ldap_baseDn', $asys_conf['ldap_baseDn'], 'baseDn');
			if(!isset($asys_conf['ldap_domainControllers'])) $asys_conf['ldap_domainControllers'] = 'dc01.mydomain.local';
			tpl_form_text('ldap_domainControllers', $asys_conf['ldap_domainControllers'], 'domainControllers');
			if(!isset($asys_conf['ldap_adminUsername'])) $asys_conf['ldap_adminUsername'] = '';
			tpl_form_text('ldap_adminUsername', $asys_conf['adminUsername'], 'adminUsername');
			if(!isset($asys_conf['ldap_adminPassword'])) $asys_conf['ldap_adminPassword'] = '';
			tpl_form_text('ldap_adminPassword', '', 'adminPassword');
		}
	}

}

// user manager choose menu
if($varaction == 'users'){
	tpl_title($lang['l_system_users_choose_title']);
	tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));
	$tpl->newBlock('choose_menu');
	foreach($nbl['users_and_groups'] as $value){
		$tpl->newBlock('choose_menu_entry');
		$tpl->assign($value);
	}
}
if($varaction == 'users_users'){
	if($varmode == 'delete'){
		$db->asys_del_usr_conf($asys['var_id'], '', true);
		$db->db_delete($db_table['users_groups'], "WHERE `user_id`='{$asys['var_id']}' AND NOT `user_id`='1'");
		$db->db_delete($db_table['users'], "WHERE `ID`='{$asys['var_id']}' AND NOT `ID`='1'");
		tpl_error_msg($lang['l_global_success_content'], 'delete-user', "user id {$asys['var_id']} deleted", false, true);
	}

	if($varmode == 'add'){
		tpl_title($lang['l_system_users_users_adduser']);

		tpl_form_start(script_uri(false) . '?action=users_users&amp;mode=create', 'post', $lang['l_button_create']);
		tpl_form_text('username', '', $lang['l_system_users_users_username']);
		tpl_form_text('password', '', $lang['l_login_form_password']);
	}

	if($varmode == 'create'){
		$added_user = $db->db_insert($db_table['users'], array($db->escape($_POST['username']), asys_encrypt($db->escape($_POST['password'])), '1'), array('username', 'password', 'enable_backend'));
		$db->db_insert($db_table['users_groups'] , array($added_user,'2'), array('user_id', 'group_id'));
		tpl_error_msg($lang['l_global_success_content'], 'add-user', "user id ", false, true);
	}
}

// manage users overview
if($varaction == 'users_users'){
	if(!$varmode OR $varmode == 'delete' OR $varmode == 'create' OR $varmode == 'add'){
		tpl_title($lang['l_system_users_choose_users']);
		tpl_content(url(script_uri(false) . '?action=users', '&larr; ' . $lang['l_global_action_back']));
		tpl_content($lang['l_system_users_users_content']);

		$users = $db->asys_get_users();
		tpl_table_start();
		tpl_table_header($lang['l_system_users_users_username']);
		//tpl_table_header($lang['l_system_users_users_mail']);
		tpl_table_header($lang['l_system_users_users_member']);
		tpl_table_header($lang['l_global_action_edit']);
		tpl_table_header($lang['l_global_action_delete']);
		foreach($users as $user){
			$user_groups = $db->db_select_rows($db_table['users_groups'], "WHERE `user_id`='{$user['ID']}'");
			$in_groups = array();
			foreach($user_groups as $value){
				$group = $db->db_select_rows($db_table['groups'], "WHERE `ID`='{$value['group_id']}'");
				if(isset($group[0]['group_name'])) $in_groups[] = $group[0]['group_name'];
			}
			$in_groups = implode($in_groups, ', ');
			tpl_table_row_start();
			tpl_table_row_content($user['username']);
			//tpl_table_row_content($user['mail']);
			tpl_table_row_content($in_groups);
			tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=users_users&amp;mode=edit&amp;id=' . $user['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
			tpl_table_row_content(dobutton('drop.png', script_uri(false) . '?action=users_users&amp;mode=delete&amp;id=' . $user['ID'], $lang['l_global_action_delete'], 'float-right', 'linked_table'));
		}
		tpl_choose_start();
		tpl_choose_entry(script_uri(false) . '?action=users_users&amp;mode=add', $lang['l_system_users_users_adduser'], 'add_big.png');
	}

	// save edit user
	if($varmode == 'save'){
		$change_password = false;
		if(isset($_POST['password']) AND $_POST['password'] != ''){
			if($_POST['password'] === $_POST['password2']){
				$change_password = true;
			}else{
				tpl_error_msg($lang['l_system_personal_notmatch'], '', '', false, false);
			}
		}
		$username = isset($_POST['username'])?$db->escape($_POST['username']):false;
		if(!$username) tpl_error_msg($lang['l_global_error'], '', '', true, false);
		$unlock_user = isset($_POST['unlock'])?$db->escape($_POST['unlock']):false;
		if($unlock_user == 1){
			$db->asys_add_usr_conf($asys['var_id'], 'wrong_logins', 0);
		}
		if($change_password){
			tpl_title($lang['l_global_success']);
			$db->db_update($db_table['users'], array($username, asys_encrypt($_POST['password'])), "WHERE `ID`='{$asys['var_id']}'", array('username', 'password'));

			$db->db_delete($db_table['users_groups'], "WHERE `user_id`='{$asys['var_id']}'");
			foreach($_POST as $key=>$value){
				if($value == 'yes'){
					$db->db_insert($db_table['users_groups'], array($asys['var_id'], $key));
				}
			}

			tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
			tpl_error_msg($lang['l_system_personal_pwchanged'], '', '', false, false);
		}else{
			tpl_title($lang['l_global_success']);
			$db->db_update($db_table['users'], array($username), "WHERE `ID`='{$asys['var_id']}'", array('username'));
			$db->db_delete($db_table['users_groups'], "WHERE `user_id`='{$asys['var_id']}'");
			foreach($_POST as $key=>$value){
				$i = 0;
				if($value == 'yes'){
					$db->db_insert($db_table['users_groups'], array($asys['var_id'], $key));
					$i++;
				}
			}
			if($i == 0){
				$db->db_insert($db_table['users_groups'], array($asys['var_id'], 3));
			}
			tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
		}
	}

	// edit user
	if($varmode == 'edit' OR $varmode == 'save'){
		tpl_title($lang['l_system_users_choose_users']);
		tpl_content(url(script_uri(false) . '?action=users_users', '&larr; ' . $lang['l_global_action_back']));

		$selected_user = $db->db_select_rows($db_table['users'], "WHERE `ID`='{$asys['var_id']}'");
		if(isset($selected_user[0])){
			$selected_user = $selected_user[0];
		}else{
			tpl_error_msg($lang['l_system_users_users_notfound'], '', '', true, false);
		}
		tpl_form_start(script_uri(false) . '?action=users_users&amp;mode=save&amp;id=' . $asys['var_id'], 'post', $lang['l_global_action_save']);

		tpl_form_desc('<h4>' . $lang['l_system_users_users_panel'] . ' ' . $selected_user['username'] . '</h4>');
		tpl_form_text('username', $selected_user['username'], $lang['l_system_users_users_username']);
		tpl_form_desc('<h5>' . $lang['l_system_users_users_chname'] . '</h5>', 'form');
		tpl_form_text('password', '', $lang['l_login_form_password'], 'password');
		tpl_form_desc('<h5>' . $lang['l_system_users_users_pw'] . '</h5>', 'form');
		tpl_form_text('password2', '', $lang['l_login_form_password2'], 'password');
		$user_conf = $db->asys_select_usr_conf($asys['var_id']);
		if($asys['var_id'] != 1 AND isset($user_conf['wrong_logins']) AND $user_conf['wrong_logins'] >= $asys_conf['asys_max_wrong_login_count']){
			tpl_form_check('', 'unlock', 1, $lang['l_system_users_users_unlock']);
		}
		$asys_groups = $db->db_select_rows($db_table['groups']);
		$user_groups = $db->db_select_rows($db_table['users_groups'], "WHERE `user_id`='{$selected_user['ID']}'");
		$in_groups = array();
		foreach($user_groups as $value){
			$in_groups[] = $value['group_id'];
		}
		if(count($in_groups) <= 0){
			$in_groups[] = '3';
		}
		$show_desc = true;
		foreach($asys_groups as $cur_group){
			if(in_array($cur_group['ID'], $in_groups)){
				$checked = 1;
			}else{
				$checked = false;
			}
			tpl_form_check(chk_checkbox($checked, 2), $cur_group['ID'], 'yes', overlib_str('#', $cur_group['group_description'], $cur_group['group_name']));
			if($show_desc) tpl_form_desc('<h5>' . $lang['l_system_users_users_groups'] . '</h5>', 'check');
			$show_desc = false;
		}
	}
}


// manage groups
if($varaction == 'users_groups'){

	// delete group
	if($varmode == 'delete'){
		$group = $db->db_select_rows($db_table['groups'], "WHERE `ID`='{$asys['var_id']}'");

		if(isset($group[0]) AND $group[0]['sys_group'] == 0){
			$db->db_delete($db_table['groups'], "WHERE `ID`='{$asys['var_id']}'");
			$db->db_delete($db_table['modules_permissions'], "WHERE `group_id`='{$asys['var_id']}'");
			$db->db_delete($db_table['users_groups'], "WHERE `group_id`='{$asys['var_id']}'");
			$db->asys_del_grp_conf($asys['var_id'], '', true);
			tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
		}else{
			tpl_error_msg($lang['l_global_noentries'], '', '', false, false);
		}
	}

	// add group
	if($varmode == 'add'){
		$varmode = 'edit';
		$create_mode = true;
	}else{
		$create_mode = false;
	}

	// create group
	if($varmode == 'create'){
		if(isset($_POST['name']) AND $_POST['name'] != ''){
			$group_id = $db->db_insert($db_table['groups'], array(0,$db->escape($_POST['name']), $db->escape($_POST['desc'])), array('sys_group', 'group_name', 'group_description'));
			tpl_error_msg($lang['l_system_users_groups_created'] . ': ' . url(script_uri(false) . '?action=users_groups&mode=edit&id=' . $group_id, $lang['l_global_action_go'] . '! &rarr;'), '', '', false, false);
		}
	}

	// manage groups overview
	if(!$varmode OR $varmode == 'delete' OR $varmode == 'create' OR $varmode == 'add'){
		tpl_title($lang['l_system_users_choose_groups']);
		tpl_content(url(script_uri(false) . '?action=users', '&larr; ' . $lang['l_global_action_back']));
		tpl_content($lang['l_system_users_groups_content']);

		$groups = $db->db_select_rows($db_table['groups']);
		tpl_table_start();
		tpl_table_header($lang['l_system_users_groups_name']);
		tpl_table_header($lang['l_system_users_groups_desc']);
		tpl_table_header($lang['l_global_action_edit']);
		tpl_table_header($lang['l_global_action_delete']);
		foreach($groups as $group){
			tpl_table_row_start();
			tpl_table_row_content($group['group_name']);
			tpl_table_row_content($group['group_description']);
			if($group['sys_group'] == 1){
				tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=users_groups&amp;mode=edit&amp;id=' . $group['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
				tpl_table_row_content(dobutton('drop_grey.png', '#', $lang['l_global_action_delete'], 'float-right', 'linked_table grey'));
			}else{
				tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=users_groups&amp;mode=edit&amp;id=' . $group['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
				tpl_table_row_content(dobutton('drop.png', script_uri(false) . '?action=users_groups&amp;mode=delete&amp;id=' . $group['ID'], $lang['l_global_action_delete'], 'float-right', 'linked_table'));
			}
		}
		tpl_choose_start();
		tpl_choose_entry(script_uri(false) . '?action=users_groups&amp;mode=add', $lang['l_system_users_groups_addgroup'], 'add_big.png');
	}
	// edit group save
	if($varmode == 'save'){
		$group_name = isset($_POST['name'])?$db->escape($_POST['name']):'group';
		$group_desc = isset($_POST['desc'])?$db->escape($_POST['desc']):'';
		$db->db_update($db_table['groups'], array($group_name, $group_desc), "WHERE `ID`='{$asys['var_id']}'", array('group_name', 'group_description'));


		foreach($asys['asys_permissions'] as $value){
			if(isset($_POST[$value]) AND $_POST[$value] == 'yes'){
				$set_value = 1;
			}else{
				$set_value = 0;
			}
			$db->asys_renew_grp_conf($asys['var_id'], $value, $set_value);
		}

		if($asys['var_id'] == 1){
			foreach($asys['asys_permissions'] as $value){
				$db->asys_renew_grp_conf(1, $value, 1);
			}
		}

		$db->db_delete($db_table['modules_permissions'], "WHERE `group_id`='{$asys['var_id']}'");
		if(isset($_POST['mod'])){
			foreach($_POST['mod'] as $key=>$value){
				if($value == 'yes'){
					$db->db_insert($db_table['modules_permissions'], array($asys['var_id'], $key, 'allow'));
				}

			}
		}
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	// edit group
	if($varmode == 'edit' OR $varmode == 'save'){
		tpl_title($lang['l_system_users_choose_groups']);
		tpl_content(url(script_uri(false) . '?action=users_groups', '&larr; ' . $lang['l_global_action_back']));

		$selected_group = $db->db_select_rows($db_table['groups'], "WHERE `ID`='{$asys['var_id']}'");
		if(isset($selected_group[0])){
			$selected_group = $selected_group[0];
		}else{
			//tpl_error_msg('Create Group Mode', '', '', false, false);
			tpl_title($lang['l_system_users_groups_addgroup']);
			unset($selected_group);
			$selected_group = array();
			$selected_group['ID'] = 0;
			$selected_group['group_name'] = $lang['l_system_users_groups_newgroup'];
			$selected_group['group_description'] = '';
			$create_mode = true;
		}
		if($create_mode){
			$target = 'create';
		}else{
			$target = 'save';
		}
		tpl_form_start(script_uri(false) . '?action=users_groups&amp;mode=' . $target . '&amp;id=' . $asys['var_id'], 'post', $lang['l_global_action_save']);

		tpl_form_desc('<h4>' . $lang['l_system_users_groups_panel'] . ': ' . $selected_group['group_name'] . '</h4>');

		tpl_form_text('name', $selected_group['group_name'], $lang['l_system_users_groups_name']);
		tpl_form_desc('<h5>' . $lang['l_system_users_groups_chname'] . '</h5>', 'form');
		tpl_form_text('desc', $selected_group['group_description'], $lang['l_system_users_groups_desc']);

		if(!$create_mode){
			$group_permissions = $db->asys_select_grp_conf($selected_group['ID']);
			$show_desc = true;
			foreach($asys['asys_permissions'] as $value){
				if(isset($group_permissions[$value])){
					if($group_permissions[$value] == 1){
						$checked = 1;
					}else{
						$checked = false;
					}
				}else{
					$checked = false;
				}
				$disabled = '';
				if($selected_group['ID'] == 1){
					$checked = 1;
					$disabled = ' DISABLED';
				}
				tpl_form_check(chk_checkbox($checked, 2) . $disabled, $value, 'yes', overlib_str('#', $lang['ac_' . $value], i_cant_read($checked) . $value));
				if($show_desc) tpl_form_desc('<h5>' . $lang['l_system_users_groups_permissions'] . '</h5>', 'check');
				$show_desc = false;
			}

			$installed_modules = $db->db_select_rows($db_table['modules']);
			$show_desc = true;
			$mod_permissions = $db->db_select_rows($db_table['modules_permissions'], "WHERE `group_id`='{$selected_group['ID']}'");
			foreach($mod_permissions as $value){
				$mod_permission[$value['mod_id']] = $value['mod_access_mode'];
			}

			foreach($installed_modules as $value){
				$disabled = '';
				if(isset($mod_permission[$value['ID']])){
					if($mod_permission[$value['ID']] == 'allow'){
						$checked = 1;
					}else{
						$checked = false;
					}
				}else{
					$checked = false;
				}
				if($value['mod_allow_everyone'] == 1 OR $selected_group['ID'] == 1){
					$checked = 1;
					$disabled = ' DISABLED';
				}


				tpl_form_check(chk_checkbox($checked, 2) . $disabled, 'mod[' . $value['ID'] . ']', 'yes', overlib_str('#', $value['mod_desc'], i_cant_read($checked) . $value['mod_name']));
				if($show_desc) tpl_form_desc('<h5>' . $lang['l_system_users_groups_modules'] . '</h5>', 'check');
				$show_desc = false;
			}
		}
	}
}

// language manager
if($varaction == 'languages'){
	if($varmode == 'edit-save'){
		$edit_value = array();
		$edit_value[] = isset($_POST['language'])?$db->escape($_POST['language']):false;
		$edit_value[] = isset($_POST['lang'])?$db->escape($_POST['lang']):false;
		$edit_value[] = isset($_POST['backend'])?$db->escape($_POST['backend']):0;
		$edit_value[] = isset($_POST['frontend'])?$db->escape($_POST['frontend']):0;
		$db->db_update($db_table['languages'], $edit_value, "WHERE `ID`='{$asys['var_id']}'", array('language', 'lang', 'backend', 'frontend'));
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	if($varmode == 'delete'){
		$db->db_delete($db_table['languages'], "WHERE `ID`='{$asys['var_id']}'");
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	if($varmode == 'add-save'){
		$edit_value = array();
		$edit_value[] = isset($_POST['language'])?$db->escape($_POST['language']):false;
		$edit_value[] = isset($_POST['lang'])?$db->escape($_POST['lang']):false;
		$edit_value[] = isset($_POST['backend'])?$db->escape($_POST['backend']):0;
		$edit_value[] = isset($_POST['frontend'])?$db->escape($_POST['frontend']):0;
		$db->db_insert($db_table['languages'], $edit_value, array('language', 'lang', 'backend', 'frontend'));
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	$installed_languages = $db->db_select_rows($db_table['languages']);
	tpl_title($lang['l_system_language_title']);
	tpl_content($lang['l_system_language_content']);

	tpl_table_start();
	tpl_table_header($lang['l_system_language_lang']);
	tpl_table_header($lang['l_system_language_short']);
	tpl_table_header($lang['l_system_language_backend']);
	tpl_table_header($lang['l_system_language_frontend']);
	tpl_table_header($lang['l_global_action_edit']);
	tpl_table_header($lang['l_global_action_delete']);

	foreach($installed_languages as $value){
		tpl_table_row_start();
		tpl_table_row_content($value['language']);
		tpl_table_row_content($value['lang']);
		tpl_table_row_content(i_cant_read($value['backend']));
		tpl_table_row_content(i_cant_read($value['frontend']));
		tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=languages&amp;mode=edit&amp;id=' . $value['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
		tpl_table_row_content(dobutton('drop.png', script_uri(false) . '?action=languages&amp;mode=delete&amp;id=' . $value['ID'], $lang['l_global_action_delete'], 'float-right', 'linked_table'));
	}
	tpl_choose_start();
	tpl_choose_entry(script_uri(false) . '?action=languages&amp;mode=add', $lang['l_global_action_add'], 'add_big.png');


	if($varmode == 'edit'){
		$edit_language = $db->db_select_rows($db_table['languages'], "WHERE `ID`='{$asys['var_id']}'");
		if(isset($edit_language[0])){
			$edit_language = $edit_language[0];

			tpl_form_start(script_uri(false) . '?action=languages&amp;mode=edit-save&amp;id=' . $asys['var_id'], 'post', $lang['l_global_action_save']);
			tpl_form_text('language', $edit_language['language'], $lang['l_system_language_lang']);
			tpl_form_text('lang', $edit_language['lang'], $lang['l_system_language_short']);
			tpl_form_check(chk_checkbox($edit_language['backend'], 2), 'backend', 1, $lang['l_system_language_backend']);
			tpl_form_check(chk_checkbox($edit_language['frontend'], 2), 'frontend', 1, $lang['l_system_language_frontend']);
		}
	}

	if($varmode == 'add'){
		tpl_form_start(script_uri(false) . '?action=languages&amp;mode=add-save', 'post', $lang['l_global_action_add']);
		tpl_form_text('language', '', $lang['l_system_language_lang']);
		tpl_form_text('lang', '', $lang['l_system_language_short']);
		tpl_form_check(chk_checkbox(0, 2), 'backend', 1, $lang['l_system_language_backend']);
		tpl_form_check(chk_checkbox(1, 2), 'frontend', 1, $lang['l_system_language_frontend']);
	}
}


// modules manager
if($varaction == 'modules'){
	if($varmode == 'edit-save'){
		$edit_value = array();
		$edit_value[] = isset($_POST['mod_name'])?$db->escape($_POST['mod_name']):false;
		$edit_value[] = isset($_POST['mod_file'])?$db->escape($_POST['mod_file']):false;
		$edit_value[] = isset($_POST['mod_allow_everyone'])?$db->escape($_POST['mod_allow_everyone']):0;
		$edit_value[] = isset($_POST['mod_desc'])?$db->escape($_POST['mod_desc']):false;
		$db->db_update($db_table['modules'], $edit_value, "WHERE `ID`='{$asys['var_id']}'", array('mod_name', 'mod_file', 'mod_allow_everyone', 'mod_desc'));
		tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
	}

	if($varmode == 'delete'){
		if($asys['var_id'] != 1){
			$db->db_delete($db_table['modules'], "WHERE `ID`='{$asys['var_id']}'");
			$db->db_delete($db_table['modules_permissions'], "WHERE `mod_id`='{$asys['var_id']}'");
			tpl_error_msg($lang['l_global_success_content'], '', '', false, false);
		}
	}

	if($varmode == 'load_mod' AND isset($asys['var_id'])){
		include '../modules/' . $asys['var_id'] . '/modinfo.php';

		if(isset($modinfo)){
			$mod_installed = $db->db_select_rows($db_table['modules'], "WHERE `mod_file`='{$asys['var_id']}'");
			if(!isset($mod_installed[0])){
				$load_array = array();
				$load_array[] = isset($modinfo['MOD_NAME'])?$modinfo['MOD_NAME']:$asys['var_id'];
				$load_array[] = $asys['var_id'];
				$load_array[] = isset($modinfo['MOD_DESC'])?$modinfo['MOD_DESC']:'None';

				$db->db_insert($db_table['modules'], $load_array, array('mod_name', 'mod_file', 'mod_desc'));
					
				tpl_error_msg('install successful', 'new-mod', 'a new mod was installed - ' . $asys['var_id'], false, true);
			}
		}
	}


	$installed_modules = $db->db_select_rows($db_table['modules']);
	tpl_title($lang['l_system_modules_title']);
	tpl_content($lang['l_system_modules_content']);

	tpl_table_start();
	tpl_table_header($lang['l_system_modules_name']);
	tpl_table_header($lang['l_system_modules_file']);
	tpl_table_header($lang['l_system_modules_afa']);
	tpl_table_header($lang['l_system_modules_desc']);
	tpl_table_header($lang['l_global_action_edit']);
	tpl_table_header($lang['l_global_action_unload']);

	$mod_names = array();
	foreach($installed_modules as $value){
		tpl_table_row_start();
		tpl_table_row_content($value['mod_name']);
		tpl_table_row_content($value['mod_file']);
		$mod_names[] = $value['mod_file'];
		tpl_table_row_content(i_cant_read($value['mod_allow_everyone']));
		tpl_table_row_content($value['mod_desc']);
		tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=modules&amp;mode=edit&amp;id=' . $value['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
		tpl_table_row_content(dobutton('mod_load.png', script_uri(false) . '?action=modules&amp;mode=delete&amp;id=' . $value['ID'], $lang['l_global_action_unload'], 'float-right', 'linked_table'));
	}

	if($varmode == 'edit'){
		$edit_mod = $db->db_select_rows($db_table['modules'], "WHERE `ID`='{$asys['var_id']}'");
		if(isset($edit_mod[0])){
			$edit_mod = $edit_mod[0];

			tpl_form_start(script_uri(false) . '?action=modules&amp;mode=edit-save&amp;id=' . $asys['var_id'], 'post', $lang['l_global_action_save']);
			tpl_form_text('mod_name', $edit_mod['mod_name'], $lang['l_system_modules_name']);
			tpl_form_text('mod_file', $edit_mod['mod_file'], $lang['l_system_modules_file']);
			tpl_form_text('mod_desc', $edit_mod['mod_desc'], $lang['l_system_modules_desc']);
			tpl_form_check(chk_checkbox($edit_mod['mod_allow_everyone'], 2), 'mod_allow_everyone', 1, $lang['l_system_modules_afa']);
		}
	}

	$available_mods = read_dir('../modules/', true);

	tpl_table_start('asys_table', 'tablesorter', '<br /><h3>' . $lang['l_system_modules_available'] . '</h3>' . $lang['l_system_modules_available_desc']);
	tpl_table_header($lang['l_system_modules_name']);
	tpl_table_header($lang['l_about_asys_version']);
	tpl_table_header($lang['l_system_modules_desc']);
	tpl_table_header($lang['l_system_modules_load']);
	$i = 0;
	foreach($available_mods as $mod){
		include '../modules/' . $mod . '/modinfo.php';
		if(isset($modinfo) AND !in_array($mod, $mod_names)){
			tpl_table_row_start();
			$tmp_val = isset($modinfo['MOD_NAME'])?$modinfo['MOD_NAME']:$mod;
			tpl_table_row_content($tmp_val);
			$tmp_val = isset($modinfo['MOD_VERSION'])?$modinfo['MOD_VERSION']:'unknown';
			tpl_table_row_content($tmp_val);
			$tmp_val = isset($modinfo['MOD_DESC'])?$modinfo['MOD_DESC']:'None';
			tpl_table_row_content($tmp_val);
			tpl_table_row_content(dobutton('mod_loaded.png', script_uri(false) . '?action=modules&amp;mode=load_mod&amp;id=' . $mod, $lang['l_global_action_load'], 'float-right', 'linked_table'));
			$i++;
		}
	}
	if($i == 0){
		tpl_table_row_start();
		tpl_table_row_content('No Modules available');
		tpl_table_row_content('');
		tpl_table_row_content('');
		tpl_table_row_content('');
		tpl_table_row_content('');
	}
}


include 'footer.php';
?>
