<?php
/* Adminsystems File Manager
 * Includes all files in this directory
* (C) 2012 Aschauer Marvin
*
* Last major change: Version 3.7.0 (2012)
* Last change: Version 3.7.0
*/

if(null !== LOAD){
	if(LOAD != true) exit('No LOAD set');
}

// save the current path in cur_path
$cur_path = asys_get('path');
if(!$cur_path) $cur_path = '/';

// create the link to browse a directory back
$path_array = explode('/', $cur_path);
unset($path_array[0]);
unset($path_array[count($path_array)]);
unset($path_array[count($path_array)]);
$directory_back_path = '/';
foreach($path_array as $dirback){
	$directory_back_path .= $dirback . '/';
}
// save the current file in cur_file
$cur_file = asys_get('file');

// define the directory where the files are in - maybe changeable later
$upload_dir = '../../upload/files' . $cur_path;

// set the title
tpl_title($lang['l_files_managefiles']);

// show the current path in the frontend
tpl_content($lang['l_files_currentpath'] . ' ' . $cur_path);
// show the "directory back" and the "root directory" link in the frontend
if($cur_path != '/') tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $directory_back_path, '&larr; ' . $lang['l_files_dirback']) . ' | ' . url(script_uri(false) . '?action=manage&amp;path=' . '/', '&uarr; ' . $lang['l_files_dirroot']) . ' | ' . url(script_uri(false) . '?action=delete&amp;path=' . $cur_path, $lang['l_files_dirdel']) );

// list the found folders
tpl_choose_start($lang['l_files_folders']);
// read the directorys out
$directorys = read_dir($upload_dir, true);
// create an image for each folder with its name
tpl_choose_entry(script_uri(false) . '?action=create_dir&amp;path=' . $cur_path, $lang['l_files_createdir'], 'add_big.png');
foreach($directorys as $dirs){
	tpl_choose_entry(script_uri(false) . '?action=manage&amp;path=' . $cur_path . $dirs . '/', $dirs, 'asys_folder.png');
}

// read the files out
$directorys = read_dir($upload_dir, false);
// show the files in a list
tpl_choose_start($lang['l_files_files']);
tpl_choose_entry(script_uri(false) . '?action=upload&amp;path=' . $cur_path, $lang['l_files_addfile'], 'add_big.png');
foreach($directorys as $file){
	if(is_file($upload_dir . $file)){
		tpl_choose_entry(script_uri(false) . '?action=view&amp;path=' . $cur_path . '&amp;file=' . $file, $file, 'asys_file.png');
	}
}

?>
