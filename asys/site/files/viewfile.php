<?php

if(null !== LOAD){
	if(LOAD != true) exit('No LOAD set');
}

// save the current path in cur_path
$cur_path = asys_get('path');
$uploads_dir = '../../upload/files' . $cur_path;

// get the listed file
$cur_file = asys_get('file');

tpl_title($lang['l_files_view_title']);

// show back link
tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));

// check if the selected file exists
if(!is_file($uploads_dir . $cur_file)){
	display_error($lang['l_files_view_notexists'], false);
}else{
	// get file extension
	$file_ext = get_file_ext($cur_file);

	// show file informations
	tpl_content('<h4>' . $lang['l_files_view_informations'] . '</h4>' . $lang['l_files_view_size']. ': ' . asys_filesize($uploads_dir . $cur_file) . '<br />' . $lang['l_files_view_extension'] . ': .' . $file_ext);

	// show download link
	tpl_content('<h4>' . $lang['l_files_view_tasks'] . '</h4>' . url($uploads_dir . $cur_file, $lang['l_files_view_download']) . '<br />' . url(script_uri(false) . '?action=delete&amp;path=' . $cur_path . '&amp;file=' . $cur_file, $lang['l_files_view_delete']) );

	// check if file is an image
	if(in_array($file_ext, array('jpg', 'jpeg', 'png', 'gif'))){
		// file is an image, show it
		tpl_content('<h4>' . $lang['l_files_view_preview'] . '</h4>' . img($uploads_dir . $cur_file, '"width="400px'));
	}








}

?>
