<?php
// settings menu
tpl_choose_start($lang['l_system_overview_title']);
foreach($nbl['system_settings'] as $value){
	tpl_choose_entry_a($value);
}
// navigation manager
tpl_choose_start($lang['l_navman_start_title']);
foreach($nbl['navman_select'] as $value){
	tpl_choose_entry_a($value);
}

// manage pages
tpl_choose_start($lang['l_site_content_title']);
foreach($nbl['pages_select'] as $value){
	tpl_choose_entry_a($value);
}

// files
tpl_choose_start($lang['l_files_managefiles']);
foreach($nbl['files_manager'] as $value){
	tpl_choose_entry_a($value);
}

// about adminsystems
tpl_choose_start($lang['l_about_asys_alltitle']);
foreach($nbl['about_icons'] as $value){
	tpl_choose_entry_a($value);
}
?>