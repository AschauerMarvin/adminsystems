<?php


$nbl = array();
// Header Links
$nbl['home'] = array('href' => 'index.php', 'navbar_name' => $lang['n_home_menu']);
$nbl['modules'] = array('href' => 'modules.php?action=modules&amp;id=1', 'navbar_name' => $lang['n_modules_menu']);
$nbl['menus'] = array('href' => 'navman.php', 'navbar_name' => $lang['n_menus_menu']);
	$nbl['add_entry'] = array('href' => 'navman.php?action=add-entry', 'navbar_name' => $lang['n_menus_addentry']);
	//$nbl['add_menus'] = array('href' => 'navman.php?action=add-navigation', 'navbar_name' => $lang['n_menus_add']);
	$nbl['man_menus'] = array('href' => 'navman.php?action=edit', 'navbar_name' => $lang['n_menus_manage']);  
$nbl['pages'] = array('href' => 'pages.php', 'navbar_name' => $lang['n_pages_menu']);
	$nbl['create'] = array('href' => 'pages.php?action=add', 'navbar_name' => $lang['n_pages_menu_create']);
	$nbl['edit'] = array('href' => 'pages.php', 'navbar_name' => $lang['n_pages_menu_edit']);
$nbl['files'] = array('href' => 'files.php', 'navbar_name' => $lang['n_files_menu']);
	$nbl['add_files'] = array('href' => 'files.php?action=upload&path=/', 'navbar_name' => $lang['n_files_add']);
	$nbl['man_files'] = array('href' => 'files.php?action=manage', 'navbar_name' => $lang['n_files_manage']);
$nbl['about'] = array('href' => 'about.php', 'navbar_name' => $lang['n_about_menu']);
$nbl['settings'] = array('href' => 'system.php', 'navbar_name' => $lang['n_settings_menu']);
$nbl['combined'] = array('href' => 'combined.php', 'navbar_name' => $lang['n_asys_menu']);

// Login / Logout
$nbl['logout'] = array('href' => 'logout.php', 'navbar_name' => $lang['n_logout_menu']);
$nbl['login'] = array('href' => 'login.php', 'navbar_name' => $lang['n_login_menu']);

// System Links
$nbl['personal'] = array('href' => 'system.php?action=personal', 'navbar_name' => $lang['n_settings_personal']);
$nbl['system'] = array('href' => 'system.php?action=system', 'navbar_name' => $lang['n_settings_sys']);
$nbl['mod_settings'] = array('href' => 'system.php?action=modules', 'navbar_name' => $lang['n_settings_mods']);
$nbl['users'] = array('href' => 'system.php?action=users', 'navbar_name' => $lang['n_settings_users']);
$nbl['languages'] = array('href' => 'system.php?action=languages', 'navbar_name' => $lang['n_settings_languages']);





// Choose Menus

// Pages
$nbl['pages_select'] = array();
$nbl['pages_select']['add'] = array('entry_url' => 'pages.php?action=add', 'entry_img' => $dir['layout_img'] . 'add_pages.png', 'entry_name' => $lang['l_site_create_title'] );
$nbl['pages_select']['edit'] = array('entry_url' => 'pages.php', 'entry_img' => $dir['layout_img'] . 'edit_pages.png', 'entry_name' => $lang['l_site_content_title'] );

// Content
$nbl['content_select'] = array();
$nbl['content_select']['add'] = array('entry_url' => 'pages.php?action=add-category', 'entry_img' => $dir['layout_img'] . 'create_category.png', 'entry_name' => $lang['l_site_create_category'] );

// Navman
$nbl['navman_select'] = array();
$nbl['navman_select']['add'] = array('entry_url' => 'navman.php?action=add-entry', 'entry_img' => $dir['layout_img'] . 'add_big.png', 'entry_name' => $lang['l_navman_start_add'] );
//$nbl['navman_select']['add_menu'] = array('entry_url' => 'navman.php?action=add-navigation', 'entry_img' => $dir['layout_img'] . 'add_big.png', 'entry_name' => $lang['l_navman_navigation_add'] );
$nbl['navman_select']['edit'] = array('entry_url' => 'navman.php?action=edit', 'entry_img' => $dir['layout_img'] . 'edit_navman.png', 'entry_name' => $lang['l_navman_start_edit'] );

// Files
$nbl['files_manager'] = array();
$nbl['files_manager']['add'] = array('entry_url' => 'files.php?action=upload&path=/', 'entry_img' => $dir['layout_img'] . 'add_big.png', 'entry_name' => $lang['l_files_addfile']);
$nbl['files_manager']['manage'] = array('entry_url' => 'files.php?action=manage', 'entry_img' => $dir['layout_img'] . 'file_forward.png', 'entry_name' => $lang['l_files_managefiles']);

// System
$nbl['system_settings'] = array();
$nbl['system_settings']['personal'] = array('entry_url' => 'system.php?action=personal', 'entry_img' => $dir['layout_img'] . 'system/' . 'personal_settings.png', 'entry_name' => $lang['l_system_personal_title']);
$nbl['system_settings']['system'] = array('entry_url' => 'system.php?action=system', 'entry_img' => $dir['layout_img'] . 'system/' . 'system_settings.png', 'entry_name' => $lang['l_system_settings_title']);
$nbl['system_settings']['users'] = array('entry_url' => 'system.php?action=users', 'entry_img' => $dir['layout_img'] . 'system/' . 'user_settings.png', 'entry_name' => $lang['l_system_users_choose_title']);
$nbl['system_settings']['languages'] = array('entry_url' => 'system.php?action=languages', 'entry_img' => $dir['layout_img'] . 'system/' . 'languages.png', 'entry_name' => $lang['l_system_language_title']);
$nbl['system_settings']['modules'] = array('entry_url' => 'system.php?action=modules', 'entry_img' => $dir['layout_img'] . 'system/' . 'modules.png', 'entry_name' => $lang['l_system_modules_title']);

$nbl['users_and_groups'] = array();
$nbl['users_and_groups']['users'] = array('entry_url' => 'system.php?action=users_users', 'entry_img' => $dir['layout_img'] . 'system/' . 'personal_settings.png', 'entry_name' => $lang['l_system_users_choose_users']);
$nbl['users_and_groups']['groups'] = array('entry_url' => 'system.php?action=users_groups', 'entry_img' => $dir['layout_img'] . 'system/' . 'user_settings.png', 'entry_name' => $lang['l_system_users_choose_groups']);

$nbl['about_icons'] = array();
$nbl['about_icons']['about_asys'] = array('entry_url' => 'about.php', 'entry_img' => $dir['layout_img'] . 'about.png', 'entry_name' => $lang['n_about_menu']);


?>