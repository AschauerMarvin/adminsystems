<?php
include 'headerconfig.php';

if(!ckpm('access_upload')) acdn();

// choose overview
if(!$asys['var_action']){
	tpl_title($lang['l_files_managefiles']);

	tpl_choose_start();
	foreach($nbl['files_manager'] as $value){
		tpl_choose_entry_a($value);
	}
}


$loadFileman = true; 

// manage files
if($varaction == 'manage'){
	// load the filemanager to manage directorys and files
	include 'files/filemanager.php';
}

if($varaction == 'view'){
	// load the file viewer
	include 'files/viewfile.php';
}

if($varaction == 'create_dir'){
	// load the directory create utility
	include 'files/newfile.php';
	// if there will a directory created
	
	// load the filemanager to manage directorys and files
	include 'files/filemanager.php';
}

if($varaction == 'upload'){
	// load the upload utility
	include 'files/newfile.php';
}

if($varaction == 'delete'){
	// load the file delete utility
	include 'files/deletefile.php';
}


include 'footer.php';
?>
