<?php 
// save the current path in cur_path
$cur_path = asys_get('path');
$uploads_dir = '../../upload/files' . $cur_path;
$cur_file = asys_get('file');
$do = asys_get('do');
$var_name = asys_post('name');

// show create dir form area
if($varaction == 'create_dir' AND !$do){
	tpl_title($lang['l_files_createdir']);

	// show back link
	tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));

	// show create directory form
	tpl_content($lang['l_files_createdirin'] . ' ' . $cur_path);
	tpl_form_start(script_uri(false) . '?action=create_dir&amp;do=create&amp;path=' . $cur_path, 'post', $lang['l_button_create']);
	tpl_form_text('name', '', $lang['l_files_dirname']);
}

// create directory
if($varaction == 'create_dir' AND $do == 'create'){
	tpl_title($lang['l_files_createdir']);

	// show back link
	tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));

	// create directory
	// check if directory already exists
	if(!is_dir($uploads_dir . $var_name)){
		// if not, create the directory and show an success message
		mkdir($uploads_dir . $var_name);
		display_success($lang['l_files_dir'] . ' ' . $var_name . ' ' . $lang['l_files_dircreatesuc']);
		asys_log('file_dir_create', 'directory ' . $var_name . ' created');
	}else{
		// if not, show an error message
		display_warning($lang['l_files_tdir'] . ' ' . $var_name . ' ' . $lang['l_files_diralreadyex']);
	}
	// log created dir
}


// show upload files form area
if($varaction == 'upload' AND !$do){
	tpl_title($lang['l_files_addfile']);
	// show back link
	tpl_content(url(script_uri(false) . '?action=manage&amp;path=' . $cur_path, '&larr; ' . $lang['l_global_action_go'] . ' '. $lang['l_global_action_back']));
	
	tpl_content($lang['l_files_upldir'] . ' ' . $cur_path);
	

	tpl_content('<div id="container"><div id="filelist">No runtime found.</div><br /><a id="pickfiles" href="javascript:;">[' . $lang['l_files_selfiles'] . ']</a> <a id="uploadfiles" href="javascript:;">[' . $lang['l_files_addfile'] . ']</a></div>');

tpl_content(run_js("
function $(id) {
	return document.getElementById(id);	
}


var uploader = new plupload.Uploader({
	runtimes : 'html5, html4',
	browse_button : 'pickfiles',
	container: 'container',
	max_file_size : '10mb',
	url : 'files/upload.php?path=$cur_path'		
});

uploader.bind('Init', function(up, params) {
	$('filelist').innerHTML = \"<div>{$lang['l_files_runtime']} \" + params.runtime + \"</div>\";
});

uploader.bind('FilesAdded', function(up, files) {
	for (var i in files) {
		$('filelist').innerHTML += '<div id=\"' + files[i].id + '\">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
	}
});

uploader.bind('UploadProgress', function(up, file) {
	$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + \"%</span>\";
});

$('uploadfiles').onclick = function() {
	uploader.start();
	return false;
};

uploader.init();		
"));
}

?>