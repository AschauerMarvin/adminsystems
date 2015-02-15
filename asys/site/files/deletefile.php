<?php
if(empty($loadFileman)){
	die('No LOAD set');
}
// save the current path in cur_path
$cur_path = asys_get('path');
$uploads_dir = '../../upload/files' . $cur_path;
$cur_file = asys_get('file');

$do = asys_get('do');

// directory delete ask screen
if(!$cur_file AND $do == false){
	if(is_dir($uploads_dir)){
		tpl_title($lang['l_files_deldir']);
		// show back link
		tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' . $lang['l_files_nodel']));
		// show a link to delete the directory
		tpl_content($lang['l_files_askdel'] . ' ' . $cur_path . '?' . '<br />' . url(script_uri(false) . '?action=delete&path=' . $cur_path . '&amp;do=deldir', $lang['l_files_yesdel']));
	}
}

// direcoty delete success
if(!$cur_file AND $do == 'deldir'){
	if(is_dir($uploads_dir)){
		tpl_title($lang['l_files_delsuc']);
		// show back link
		tpl_content(url(script_uri(false) . '?action=manage&amp;path=/', '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));
		// show success message
		display_success($lang['l_files_tdir'] . ' ' . $cur_path . ' ' . $lang['l_files_delsucmsg']);
		asys_deldir($uploads_dir);
		asys_log('file_dir_delete', 'directory ' . $cur_path . ' deleted');
	}
}


// file delete ask screen
if($cur_file != false AND $do == false){
	if(is_file($uploads_dir . $cur_file)){
		tpl_title($lang['l_files_delfile']);
		// show back link
		tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' .  $lang['l_files_nodel']));
		// show a link to delete the directory
		tpl_content($lang['l_files_askdelf'] . ' ' . $cur_file . '?' . '<br />' . url(script_uri(false) . '?action=delete&path=' . $cur_path . '&amp;file=' . $cur_file .'&amp;do=delfile', $lang['l_files_yesdelf']));
	}
}

// file delete success
if($cur_file != false AND $do == 'delfile'){
	if(is_dir($uploads_dir)){
		tpl_title($lang['l_files_delsuc']);
		// show back link
		tpl_content(url(script_uri(false) . '?action=manage&amp;path=/', '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));
		// show success message
		display_success($lang['l_files_tfile'] . ' ' . $cur_file . ' ' . $lang['l_files_delsucmsg']);
		@unlink($uploads_dir . $cur_file);
		asys_log('file_delete', 'file ' . $cur_file . ' deleted');
	}
}


?>
