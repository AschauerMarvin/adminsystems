<?php
include 'db_conf.php';

// page settings
$conf['asys_session'] = 'adminsystems';

// public adminsystems sites
$conf['allow_without_login'] = array(
'index.php', 
'login.php', 
'login.php?action=login'
); 


// global variables
$asys = array(); // system variables
$asys_user = array();// user variables
$asys_group = array(); // group variables

$asys['db_type'] = $conf['db_type'];

$asys['asys_permissions'] = array(
'access_pages_edit',
'access_pages_create',
'access_pages_delete',
'access_navman_view',
'access_navman_change',
'access_upload',
'access_about',
'access_system_administrator',
'access_system_modules',
//'access_system_plugins',
'access_system_users'
);

$asys['DEBUG_MODE'] = false; // enable debug mode (much much more informations...)

$asys['all_must_null'] = true; // all permissions in group must be 0 to forbit access.

// load database tables into array...
$db_table = array();
// database tables
$db_table['config'] = $conf['db_prefix'] . '_config';
$db_table['groups'] = $conf['db_prefix'] . '_groups';
$db_table['groups_variables'] = $conf['db_prefix'] . '_groups_variables';
$db_table['languages'] = $conf['db_prefix'] . '_languages';
$db_table['logs'] = $conf['db_prefix'] . '_logs';
$db_table['modules'] = $conf['db_prefix'] . '_modules';
$db_table['modules_permissions'] = $conf['db_prefix'] . '_modules_permissions';
$db_table['navigation_entrys'] = $conf['db_prefix'] . '_navigation_entrys';
//$db_table['navigation_menus'] = $conf['db_prefix'] . '_navigation_menus';
$db_table['pages'] = $conf['db_prefix'] . '_pages';
$db_table['pages_content'] = $conf['db_prefix'] . '_pages_content';
$db_table['pages_categories'] = $conf['db_prefix'] . '_pages_categories';
$db_table['users'] = $conf['db_prefix'] . '_users';
$db_table['users_groups'] = $conf['db_prefix'] . '_users_groups';
$db_table['users_variables'] = $conf['db_prefix'] . '_users_variables';

?>
