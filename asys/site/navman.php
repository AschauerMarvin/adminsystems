<?php
include 'headerconfig.php';

// choose overview
if(!$asys['var_action']){
	// show title and content
	tpl_title($lang['l_navman_start_title']);
	tpl_content($lang['l_navman_start_content']);

	// create an choose list - add and manage
	$tpl->newBlock('choose_menu');
	foreach($nbl['navman_select'] as $value){
		$tpl->newBlock('choose_menu_entry');
		$tpl->assign($value);
	}
}

// create navigation entry
$entry_type = isset($_GET['entry_type'])?$_GET['entry_type']:false;

if($asys['var_action'] == 'add-entry' AND !$asys['var_mode']){

	if(!$entry_type){
		tpl_title($lang['l_navman_start_add']);
		tpl_content('<h4>' . $lang['l_navman_naventry_create'] . '</h4>');

		tpl_choose_start();
		tpl_choose_entry(script_uri() . '&amp;entry_type=internal_link', $lang['l_navman_naventry_create_internal'], 'edit_pages.png', short_overlib_str($lang['l_navman_naventry_create_internal_desc']));
		tpl_choose_entry(script_uri() . '&amp;entry_type=external_url', $lang['l_navman_naventry_create_external'], 'external_url.png', short_overlib_str($lang['l_navman_naventry_create_external_desc']));
		//tpl_choose_entry(script_uri() . '&amp;entry_type=file_forward', $lang['l_navman_naventry_create_fileforward'], 'file_forward.png', short_overlib_str($lang['l_navman_naventry_create_fileforward_desc']));
	}
	if(!$entry_type){
	}else{
		$last_order_num = $db->db_select_table($db_table['navigation_entrys'], false, 'get_fields', "WHERE `menu_id` = '{$asys['var_id']}' ORDER BY `sort_value` DESC", 1);
		if(isset($last_order_num[0]['sort_value'])){
			$last_order_num = $last_order_num[0]['sort_value'];
		}else{
			$last_order_num = 0;
		}

		$last_inserted_entry = $db->db_insert($db_table['navigation_entrys'], array($entry_type, $asys['var_id'], $last_order_num + 1, 'none'), array('entry_mode', 'menu_id', 'sort_value', 'asys_parameter'));
		$db->db_update($db_table['navigation_entrys'], array('a:1:{i:1;s:4:"Page";}', 'page-' . $last_inserted_entry), "WHERE `ID`='$last_inserted_entry'", array('entry_name', 'entry_alias'));

		tpl_error_msg($lang['l_navman_naventry_create_success'] . ' ' . url(script_uri(false) . '?action=edit&amp;mode=edit-entry&amp;entry_id=' . $last_inserted_entry, $lang['l_global_action_go'] . '! &rarr;'), '', '', false, false);

		$asys['var_action'] = 'edit';
		$asys['var_mode'] = 'show-navigation';
	}
}

// edit overview - navigation menus
if($asys['var_action'] == 'edit' AND !$asys['var_mode']){
	// show title and content
	tpl_title($lang['l_navman_start_title']);
	tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));
	tpl_content($lang['l_navman_navigation_edit']);
	// check if only one navigation menu entry is available and make it work
	$nav_array = $template_informations['template']['navigations']['navigation'];
	if(count($nav_array) <= 1){
		$nav_array = $template_informations['template']['navigations'];
	}
	tpl_choose_start();
	foreach($nav_array as $key=>$entry){
		tpl_choose_entry(script_uri(false) . '?action=edit&amp;mode=show-navigation&amp;id=' . $key, $entry, 'edit_navman.png');
	}
}

$sort_values = array();
$sort_values['navman_option'] = isset($_GET['navman_option'])?$_GET['navman_option']:false;
$sort_values['entry_option_id'] = isset($_GET['entry_option_id'])?$db->escape($_GET['entry_option_id']):false;
$sort_values['cur_sortval'] = isset($_GET['cur_sortval'])?$db->escape($_GET['cur_sortval']):false;
$sort_values['cur_sortval_s1'] = $sort_values['cur_sortval'] - 1;
$sort_values['cur_sortval_a1'] = $sort_values['cur_sortval'] + 1;


// sort up
if($sort_values['navman_option'] == 'up'){

	// no negative values
	if($sort_values['cur_sortval_s1'] <= 0){
		$sort_values['cur_sortval_s1'] = 1;
	}

	$db->db_update($db_table['navigation_entrys'], array($sort_values['cur_sortval']), "WHERE `sort_value`='{$sort_values['cur_sortval_s1']}' AND `menu_id`='{$asys['var_id']}'", array('sort_value'));
	$db->db_update($db_table['navigation_entrys'], array($sort_values['cur_sortval_s1']), "WHERE `ID`='{$sort_values['entry_option_id']}'", array('sort_value'));
}

// sort down
if($sort_values['navman_option'] == 'down'){
	$db->db_update($db_table['navigation_entrys'], array($sort_values['cur_sortval']), "WHERE `sort_value`='{$sort_values['cur_sortval_a1']}' AND `menu_id`='{$asys['var_id']}'", array('sort_value'));
	$db->db_update($db_table['navigation_entrys'], array($sort_values['cur_sortval'] + 1), "WHERE `ID`='{$sort_values['entry_option_id']}'", array('sort_value'));
}


$save_entry = asys_get('save');

// save nav entry
if($asys['var_action'] == 'edit' AND $asys['var_mode'] == 'edit-entry' AND $save_entry == 'true'){
	$entry_id = asys_get('entry_id');
	$entry = $db->db_select_table($db_table['navigation_entrys'], false, 'get_fields', "WHERE `ID` = '$entry_id'");
	$entry = $entry[0];
	$asys_var = asys_post_nonreplace('asys_var');
	if($entry['entry_mode'] == 'internal_link'){
		$linked_page = $db->db_select_table($db_table['pages'], true, array('ID'), "WHERE `page_name` = '$asys_var'");
		$linked_page = $linked_page[0];
		$asys_var = $linked_page['ID'];
	}

	$entry_sub = asys_post_nonreplace('entry_sub');
	$sub_id = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `entry_alias`='$entry_sub'");
	if(isset($sub_id[0])){
		$sub_id = $sub_id[0]['ID'];
	}else{
		$sub_id = '';
	}
	// get page name array
	$page_name = $_POST['page_name'];
	// exit if it's not an aray
	if(!is_array($page_name)) exit;
	// escape all array entrys
	foreach($page_name as $key=>$value){
		$page_name[$key] = $db->escape(mod_str_for_post($value));
	}
	// serialize the array for db save
	$page_name = serialize($page_name);

	// format the page alias
	$page_alias = $db->escape(strtolower(remove_all($_POST['alias'])));

	// check if the page alias is already used
	$used_alias = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `entry_alias`='$page_alias'");

	// get the page tpl
	$page_tpl = asys_post('entry_tpl');

	// if the alias is already in use, show an error message
	if(isset($used_alias[0]) AND $used_alias[0]['ID'] != $entry_id){
		display_error($lang['l_navman_alias_inuse']);
	}else{
		// else, write the changes and display a success message
		$db->db_update($db_table['navigation_entrys'], array($page_name, $page_alias, $sub_id, $asys_var, $page_tpl), "WHERE `ID`='$entry_id'", array('entry_name', 'entry_alias', 'entry_sub', 'asys_parameter', 'entry_tpl'));
		display_success($lang['l_global_success_content']);
	}
}

// delete nav entry
if($asys['var_action'] == 'edit' AND $asys['var_mode'] == 'delete-entry'){
	$entry_id = isset($_GET['entry_id'])?$db->escape($_GET['entry_id']):false;
	$cur_entry = $db->db_select_table($db_table['navigation_entrys'], false, 'get_fields', "WHERE `ID`='$entry_id'");
	$cur_entry = isset($cur_entry[0])?$cur_entry[0]:false;

	$db->db_delete($db_table['navigation_entrys'], "WHERE `ID`='$entry_id'");

	$nav_entrys = $db->db_select_table($db_table['navigation_entrys'], false, array('ID'), "WHERE `menu_id`='{$cur_entry['menu_id']}'");
	if(count($nav_entrys) <= 0){
		$db->db_delete($db_table['navigation_menus'], "WHERE `ID`='{$cur_entry['menu_id']}'");
	}


	// show a success message
	tpl_error_msg($lang['l_global_success_content'], '', '', false, false);

}

// edit overview - navigation menus
if($asys['var_action'] == 'edit' AND $asys['var_mode'] == 'show-navigation' OR $asys['var_mode'] == 'delete-entry'){
	tpl_title($lang['l_navman_start_edit']);
	tpl_content(url(script_uri(false) . '?action=edit', '&larr; ' . $lang['l_global_action_back']));

	// create an choose list - add and manage
	$tpl->newBlock('choose_menu');
	$add_link = $nbl['navman_select']['add'];
	$add_link['entry_url'] = $add_link['entry_url'] . '&amp;id=' .$asys['var_id'];
	$tpl->newBlock('choose_menu_entry');
	$tpl->assign($add_link);

	$navigation_menu_entrys = $db->db_select_table($db_table['navigation_entrys'], false, 'get_fields', "WHERE `menu_ID` = '{$asys['var_id']}'");

	if(isset($navigation_menu_entrys[0]['ID'])){
		tpl_table_start();
		tpl_table_header($lang['l_navman_naventrys_sort']);
		tpl_table_header($lang['l_navman_naventrys_name']);
		tpl_table_header($lang['l_navman_naventrys_alias']);
		tpl_table_header('Parent');
		tpl_table_header($lang['l_navman_navtarget']);
		tpl_table_header($lang['l_navman_naventrys_mode']);
		tpl_table_header($lang['l_navman_naventrys_options']);
		tpl_table_header($lang['l_global_action_edit']);
		tpl_table_header($lang['l_global_action_delete']);
		$nav_by_id = array();
		foreach($navigation_menu_entrys as $entry){
			$nav_by_id[$entry['ID']] = $entry;
		}
		foreach($navigation_menu_entrys as $cur_entry){
			if($cur_entry['entry_mode'] == 'internal_link' AND $cur_entry['asys_parameter'] != 'none'){
				$page_id = $cur_entry['asys_parameter'];
				$cur_entry['asys_parameter'] = $db->db_select_table($db_table['pages'], true, array('ID', 'page_name'), "WHERE `ID` = '$page_id'");
				if(isset($cur_entry['asys_parameter'][0])){
					$page_name = url('pages.php?action=edit&amp;mode=edit&amp;id=' . $page_id, $cur_entry['asys_parameter'][0]['page_name']);
				}else{
					$page_name = 'none';
				}
				$cur_entry['asys_parameter'] = $lang['l_navman_internaltarget'] . ': ' . $page_name;
			}
			$parent_page = '';
			if($cur_entry['entry_sub'] != ''){
				$parent_page = $nav_by_id[$cur_entry['entry_sub']]['entry_alias'];
			}

			$cur_entry['entry_name'] = unserialize($cur_entry['entry_name']);
			$cur_entry['entry_name'] = current($cur_entry['entry_name']);
			$arrow = '';
			if($parent_page != '') $arrow = '&rArr;';
			tpl_table_row_start();
			tpl_table_row_content($cur_entry['sort_value']);
			tpl_table_row_content($arrow . ' ' . $cur_entry['entry_name']);
			tpl_table_row_content($cur_entry['entry_alias']);
			tpl_table_row_content($parent_page);
			tpl_table_row_content($cur_entry['asys_parameter']);
			tpl_table_row_content($cur_entry['entry_mode']);
			tpl_table_row_content(
			dobutton('order_up.png', script_uri(false) . '?action=edit&amp;mode=show-navigation&amp;id=' . $asys['var_id'] . '&amp;navman_option=up&amp;cur_sortval=' . $cur_entry['sort_value'] . '&amp;entry_option_id=' . $cur_entry['ID'], $lang['l_global_action_up']) .
			dobutton('order_down.png', script_uri(false) . '?action=edit&amp;mode=show-navigation&amp;id=' . $asys['var_id'] . '&amp;cur_sortval=' . $cur_entry['sort_value'] . '&amp;navman_option=down&amp;entry_option_id=' . $cur_entry['ID'], $lang['l_global_action_down'])
			);
			tpl_table_row_content(dobutton('edit.png', script_uri(false) . '?action=edit&amp;mode=edit-entry&amp;entry_id=' . $cur_entry['ID'] . '&amp;id=' . $asys['var_id'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
			tpl_table_row_content(dobutton('drop.png', script_uri(false) . '?action=edit&amp;mode=delete-entry&amp;entry_id=' . $cur_entry['ID'] . '&amp;id=' . $asys['var_id'], $lang['l_global_action_delete'], 'float-right', 'linked_table'));
		}
	}else{
		tpl_error_msg($lang['l_global_noentries'], '', '', false, false);
	}
}

// edit nav entry
if($asys['var_action'] == 'edit' AND $asys['var_mode'] == 'edit-entry'){
	$entry_id = asys_get('entry_id');

	$entry = $db->db_select_table($db_table['navigation_entrys'], false, 'get_fields', "WHERE `ID` = '$entry_id'");
	$entry = $entry[0];

	tpl_title($lang['l_navman_start_edit']);
	tpl_content(url(script_uri(false) . '?action=edit&amp;mode=show-navigation&amp;id=' . $entry['menu_id'], '&larr; ' . $lang['l_global_action_back']));

	// load all installed languages
	$frontend_languages = $db->db_select_rows($db_table['languages'], "WHERE `frontend`='1'");

	tpl_form_start(script_uri(true) . '&amp;save=true', 'post', $lang['l_global_action_save']);
	tpl_form_desc('<h4>' . $lang['l_navman_start_edit'] . '</h4>');

	// get the array for the entry name
	$entry_name = unserialize($entry['entry_name']);

	// display a form for each language in frontend
	$i = 0;
	foreach($frontend_languages as $cur_lang){
		tpl_form_text('page_name[' . $cur_lang['ID'] . ']', $entry_name[$cur_lang['ID']], $cur_lang['language']);
		if($i == 0) tpl_form_desc('<h5>' .  $lang['l_navman_navname'] . '</h5>', 'form');
		$i++;
	}
	tpl_form_text('alias', $entry['entry_alias'], $lang['l_navman_navalias']);
	tpl_form_desc('<h5>' .  $lang['l_navman_navalias'] . '</h5>', 'form');

	$nav_entrys = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `menu_id`='{$asys['var_id']}'");

	// display the select parent page dropdown
	tpl_selectform_start($lang['l_navman_sparenta'], 'entry_sub', 1);
	tpl_form_desc('<h5>' .  $lang['l_navman_sparent'] . '</h5>', 'select');
	tpl_selectform_option('');
	foreach($nav_entrys as $value){
		if($value['entry_sub'] == '' AND $entry['ID'] != $value['ID']){
			if($value['ID'] == $entry['entry_sub']){
				tpl_selectform_option($value['entry_alias'], true);
			}else{
				tpl_selectform_option($value['entry_alias']);
			}
		}
	}
	// display the site template file dropdown
	tpl_selectform_start($lang['l_navman_template'], 'entry_tpl', 1);
	tpl_form_desc('<h5>' .  $lang['l_navman_template_file'] . '</h5>', 'select');
	foreach($template_informations['template']['files']['filename'] as $tpl_file){
		if($tpl_file == $entry['entry_tpl']){
			tpl_selectform_option($tpl_file, true);
		}else{
			tpl_selectform_option($tpl_file);
		}
	}
	// only on internal links - display the content dropdown
	if($entry['entry_mode'] == 'internal_link'){
		$pages = $db->db_select_rows($db_table['pages'], "ORDER BY `page_name`");
		if(count($pages) > 80){
			tpl_form_text('asys_var', '', $lang['l_navman_pageid']);
		}else{
			tpl_selectform_start($lang['l_navman_page'], 'asys_var', 1);
			tpl_form_desc('<h5>' .  $lang['l_site_pages_table_name'] . '</h5>', 'select');
			foreach($pages as $value){
				if($value['ID'] == $entry['asys_parameter']){
					tpl_selectform_option($value['page_name'], true);
				}else{
					tpl_selectform_option($value['page_name']);
				}
			}
		}
	}else{
		tpl_form_text('asys_var', $entry['asys_parameter'], 'URL');
		tpl_form_desc('<h5>' .  'URL' . '</h5>', 'form');

	}
}

// create navigation menu
if($asys['var_action'] == 'add-navigation' AND !$asys['var_mode']){
	tpl_title($lang['l_navman_navigation_add']);
	tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));
	tpl_content($lang['l_navman_navmenu_addmenu']);
	tpl_form_start(script_uri() . '&amp;mode=save', 'post', $lang['l_button_create']);
	tpl_form_text('nav_name', '', overlib_str('#', $lang['l_navman_navmenu_name_desc'], $lang['l_navman_naventrys_name']));
	tpl_form_text('nav_position', '', overlib_str('#', $lang['l_navman_navmenu_position_desc'], $lang['l_navman_navmenu_position']));
}

// create navigation menu save
if($asys['var_action'] == 'add-navigation' AND $asys['var_mode'] == 'save'){
	$nav_name = isset($_POST['nav_name'])?$_POST['nav_name']:false;
	$nav_position = isset($_POST['nav_position'])?$_POST['nav_position']:false;

	if(!$nav_name OR $nav_name == '' OR !$nav_position OR $nav_position == ''){
		tpl_content(url(script_uri(false) . '?action=add-navigation', '&larr; ' . $lang['l_global_action_back']));
		tpl_error_msg($lang['l_navman_navmenu_create_err'], '', '', true, false);
	}

	$check_if_exists = $db->db_select_table($db_table['navigation_menus'], false, 'get_fields', "WHERE `nav_position`='$nav_position'");

	if(isset($check_if_exists[0])){
		tpl_content(url(script_uri(false) . '?action=add-navigation', '&larr; ' . $lang['l_global_action_back']));
		tpl_error_msg($lang['l_navman_navmenu_create_err2'], '', '', true, false);
	}

	$navmenu_id = $db->db_insert($db_table['navigation_menus'], array($nav_name, $nav_position), array('nav_name', 'nav_position'));
	tpl_error_msg($lang['l_global_success_content'], '', '', false, false);

	tpl_content($lang['l_navman_navmenu_create_success']);

	tpl_content(url(script_uri(false) . '?action=add-entry&amp;id=' . $navmenu_id, $lang['l_global_action_go'] . '! &rarr;'));
}
include 'footer.php'
?>