<?php

/*
 * Here are the pages.php permission controls
 */
// check the access_pages_create permission
if(str_contains(script_uri(), 'pages.php?action=add')){
	if(!ckpm('access_pages_create')) acdn();
}

// check the access_pages_edit permission
if(str_contains(script_uri(), 'pages.php?action=edit')){
	if(!ckpm('access_pages_edit')) acdn();
}

// check the access_pages_delete permission
if(str_contains(script_uri(), 'pages.php?action=edit&mode=delete')){
	if(!ckpm('access_pages_delete')) acdn();
}
/*
 * --Here ENDS the pages.php permission controls
 */

/*
 * Here are the navman.php permission controls
 */

if(str_contains(script_uri(), 'navman.php?action=add-entry')){
	if(!ckpm('access_navman_change')) acdn();
}


if(str_contains(script_uri(), 'navman.php?action=add-navigation')){
	if(!ckpm('access_navman_change')) acdn();
}

if(str_contains(script_uri(), 'navman.php?action=edit')){
	if(!ckpm('access_navman_view')) acdn();
}

if(str_contains(script_uri(), 'navman.php?action=edit&mode=edit-entry')){
	if(!ckpm('access_navman_change')) acdn();
}

if(str_contains(script_uri(), 'navman.php?action=edit&mode=delete-entry')){
	if(!ckpm('access_navman_change')) acdn();
}

/*
 * --Here ENDS the navman.php permission controls
 */

/*
 * --Here STARTS the about.php permission controls
 */

if(str_contains(script_uri(), 'about.php')){
	if(!ckpm('access_about')) acdn();
}

/*
 * --Here ENDS the about.php permission controls
 */


/*
 * --Here STARTS the files.php permission controls
 */

if(str_contains(script_uri(), 'files.php')){
	if(!ckpm('access_upload')) acdn();
}

/*
 * --Here ENDS the files.php permission controls
 */

/*
 * --Here STARTS the system.php permission controls
 */

if(str_contains(script_uri(), 'system.php?action=system')){
	if(!ckpm('access_system_administrator')) acdn();
}

if(str_contains(script_uri(), 'system.php?action=users')){
	if(!ckpm('access_system_users')) acdn();
}

if(str_contains(script_uri(), 'system.php?action=languages')){
	if(!ckpm('access_system_administrator')) acdn();
}

if(str_contains(script_uri(), 'system.php?action=modules')){
	if(!ckpm('access_system_modules')) acdn();
}

/*
 * --Here ENDS the system.php permission controls
 */

?>