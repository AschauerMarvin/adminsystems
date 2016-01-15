<?php
include 'headerconfig.php';

// overview
if(!$asys['var_action']){
	// show the title
	tpl_title($lang['l_site_content_title']);
	// show the content
	tpl_content($lang['l_site_content_content']);

	// display a list of all categories
	// get all categories from the database
	$page_categories = $db->db_select_table($db_table['pages_categories']);
	// start the choose list
	tpl_choose_start($lang['l_site_categories']);
	// display the "add category" button
	foreach($nbl['content_select'] as $value){
		tpl_choose_entry_a($value);
	}
	// display an icon for each category
	foreach($page_categories as $category){
		tpl_choose_entry(script_uri() . '?action=edit&amp;category=' . $category['ID'], $category['category_name'], 'page_categories.png');
	}
}
$cur_category = asys_get('category', 1);

// create a new category
if($asys['var_action'] == 'add-category'){
	tpl_title($lang['l_site_create_category']);
	
	// if the form was sent, process
	if($asys['var_mode'] == 'create-category'){
		// get the category name
		$add_category = asys_post('category_name');
	
		// if no category name selected, show error message and show the form again
		if($add_category == '' OR $add_category == false){
			display_error($lang['l_site_create_noname_cat']);
			$asys['var_mode'] = false;
		}else{
			// else, create the category - multiple names are allowed here
			$newcat_id = $db->db_insert($db_table['pages_categories'], array($add_category));
			
			display_success($lang['l_site_create_success_cat']);
				
			$asys['var_action'] = 'add';
			$asys['var_mode'] = false;
			$cur_category = $newcat_id;
		}
	
	}
	
	// show the form
	if(!$asys['var_mode'] AND $asys['var_action'] != 'add'){
		tpl_form_start(script_uri() . '&amp;mode=create-category', 'post', $lang['l_button_continue']);
		tpl_form_desc($lang['l_site_create_category']);
		tpl_form_text('category_name', '', $lang['l_site_create_category_name']);
	}	
}

// create a new page
if($asys['var_action'] == 'add'){
	// on add page start-page
	if(!$asys['var_mode']){
		tpl_form_start(script_uri(false) . '?action=add&amp;mode=create&amp;category=' . $cur_category, 'post', $lang['l_button_continue']);
		tpl_form_desc($lang['l_site_create_title']);
		tpl_form_text('page_name', '', $lang['l_site_create_form']);
	}

	if($asys['var_mode'] == 'create'){
		// first of all, get the selected page-name
		$add_pagename = asys_post('page_name');
		if($asys_conf['clean_pagenames'] == 1){
			$add_pagename = remove_all($add_pagename);
		}
		// if no pagename selected, show error message and end script here
		if($_POST['page_name'] == ''){
			tpl_form_start(script_uri() . '&amp;mode=create', 'post', $lang['l_button_continue']);
			tpl_form_text('page_name', '', $lang['l_site_create_form']);
			tpl_error_msg($lang['l_site_create_noname'], 'no-page-name', '', true, false);
		}

		// if the pagename is currently in use, show error message and end script here.
		$exist_site = $db->db_select_table($db_table['pages'], true, array('page_name'), "WHERE `page_name` = '$add_pagename'");
		if(isset($exist_site[0]['page_name'])){
			tpl_form_start(script_uri() . '&amp;mode=create', 'post', $lang['l_button_continue']);
			tpl_form_text('page_name', $add_pagename, $lang['l_site_create_form']);
			tpl_error_msg($lang['l_site_create_usedname'], 'used-pagename', '', true, false);
		}

		// if no error occured, now we create the page
		$newpage_id = $db->db_insert($db_table['pages'], array($add_pagename, $cur_category, time(), time()));

		display_success($lang['l_site_create_success']);

		$_GET['page_id'] = $newpage_id;		
		$asys['var_mode']  = 'add-content';
	}
	$asys['var_action'] = 'edit';
}


// edit pages
if($asys['var_action'] == 'edit'){

	// delete page
	if($asys['var_mode'] == 'delete'){
		$page_id = asys_get('id');

		$db->db_delete($db_table['pages'], "WHERE `ID`='$page_id'");
		$db->db_delete($db_table['pages_content'], "WHERE `page_id`='$page_id'");

		// show a success message
		display_information($lang['l_global_success_content']);
	}
	
	// delete category
	if($asys['var_mode'] == 'delete-category'){
		// category 1 could not be deleted! 
		if($cur_category != 1){
			$db->db_delete($db_table['pages_categories'], "WHERE `ID`='$cur_category'");
			$db->db_update($db_table['pages'], array(1), "WHERE `page_category`='$cur_category'", array('page_category'));
			// show a success message
			display_information($lang['l_global_success_content']);
			$asys['var_mode'] = false;
			$cur_category = 1;	
		}
			

	
	}
		

	// edit overview
	if($asys['var_mode'] == 'delete' OR !$asys['var_mode']){
		
		$cur_category_name = $db->db_select_rows($db_table['pages_categories'], "WHERE `ID`='$cur_category'");
		if(isset($cur_category_name[0])){
			$cur_category_name = $cur_category_name[0]['category_name'];
		}else{
			$cur_category_name = 'Default';
		}
		
		// show the title
		tpl_title($lang['l_site_content_title'] . ' - ' . $lang['l_site_category'] . ': ' . $cur_category_name);


		tpl_content(url(script_uri(false), '&larr; ' . $lang['l_global_action_back']));

		if($cur_category != 1) tpl_content(url(script_uri(false) . '?action=edit&amp;mode=delete-category&amp;category=' . $cur_category, $lang['l_site_categories_delete']));


		$all_pages = $db->db_select_rows($db_table['pages'], "WHERE `page_category`='$cur_category'");
		tpl_table_start();
		tpl_table_header($lang['l_site_pages_table_name']);
		tpl_table_header('Created on');
		tpl_table_header($lang['l_global_action_edit']);
		tpl_table_header($lang['l_global_action_delete']);
		foreach($all_pages as $cur_page){
			tpl_table_row_start();
			tpl_table_row_content($cur_page['page_name']);
			tpl_table_row_content(date('d.m.Y, H:i' ,$cur_page['time_created']));
			tpl_table_row_content(dobutton('edit.png', script_uri(true) . '&amp;mode=edit&amp;id=' . $cur_page['ID'], $lang['l_global_action_edit'], 'float-right', 'linked_table'));
			tpl_table_row_content(dobutton('drop.png', script_uri(true) . '&amp;mode=delete&amp;id=' . $cur_page['ID'], $lang['l_global_action_delete'], 'float-right', 'linked_table'));
		}

		tpl_choose_start();
		tpl_choose_entry(script_uri(false) . '?action=add&amp;category=' . $cur_category, $lang['l_site_pages_add_content'], 'add_pages.png');
	}

	// edit page
	if($asys['var_mode'] == 'edit'){
		


		tpl_content(url(script_uri(false) . '?action=edit&amp;category=' . $cur_category, '&larr; ' . $lang['l_global_action_back']));

		// get the page id for current page
		$page_id = asys_get('id');

		// now, get the current page details from database
		$cur_page = $db->db_select_table($db_table['pages'], false, 'get_fields', "WHERE `ID`='$page_id'");
		$cur_page = $cur_page[0];

		// show a text form with the page name and a save button
		tpl_form_start(script_uri(false) . '?action=edit&amp;mode=edit-save&amp;category=' . $cur_category .'&amp;id=' . $page_id , 'post', $lang['l_button_save']);
		tpl_form_text('page_name', $cur_page['page_name'], $lang['l_site_create_form']);

		$page_categories = $db->db_select_rows($db_table['pages_categories']);
		tpl_selectform_start('Category', 'page_category', 1);
		foreach($page_categories as $category){
			if($cur_page['page_category'] == $category['ID']){
				$selected = true;
				$show_category = $category['category_name']; 
			}else{
				$selected = false;
			}
			tpl_selectform_option($category['category_name'], $selected);
		}
		tpl_title($lang['l_site_edit_title'] . '- ' . $lang['l_site_category'] . ': ' . $show_category);
		

		// get the available languages
		$languages = $db->db_select_table($db_table['languages'], false, 'get_fields', "WHERE `frontend`='1'");

		// and now, save the lang-ids into an array
		$allowed_languages = array();
		foreach($languages as $value){
			$allowed_languages[] = $value['ID'];
		}

		// get the content entrys for the current page
		$page_content = $db->db_select_table($db_table['pages_content'], false, 'get_fields', "WHERE `page_id`='$page_id'");

		// now, show an 'add content' link
		tpl_choose_start();
		tpl_choose_entry(script_uri(false) . '?action=edit&amp;mode=add-content&amp;category=' . $cur_category . '&amp;page_id=' . $page_id, $lang['l_site_pages_add_content'], 'add_big.png');

		// for all installed languages with content, show an lang flag stored in /img/flags to edit the language
		foreach($page_content as $value){
			if(in_array($value['page_lang'], $allowed_languages) AND $value['page_content'] != ''){
				$page_language = $value['page_lang'];
				$cur_lang = $db->db_select_table($db_table['languages'], false, 'get_fields', "WHERE `frontend`='1' AND `ID`='$page_language'");
				$showed_flag = 'flags/' . $cur_lang[0]['lang'] . '.png';
				if(!is_file($dir['layout_img'] . $showed_flag)){
					$showed_flag = 'flags/' . 'others' . '.png';
				}	
				tpl_choose_entry(script_uri(false) . '?action=edit&amp;mode=edit-content&amp;id=' . $value['ID'] . '&amp;goback=' . $page_id . '&amp;category=' . $cur_category, $cur_lang[0]['language'], $showed_flag, short_overlib_str(prepare_overlib($value['page_content'], 500)));
			}
			if($value['page_content'] == ''){
				$db->db_delete($db_table['pages_content'], "WHERE `ID`='" . $value['ID'] . "'");
			}
		}
	}

	// edit save
	if($asys['var_mode'] == 'edit-save'){
		// get page id and escape
		$page_id = asys_get('id');
		$page_category = asys_post('page_category');
		// get page name and format it (all lowercase, etc)
		$page_name = asys_post('page_name');
		if($asys_conf['clean_pagenames'] == 1){
			$page_name = remove_all($page_name);
		}
		$to_category = $db->db_select_rows($db_table['pages_categories'], "WHERE `category_name`='$page_category'");
		if(isset($to_category[0])){
			$category_id = $to_category[0]['ID'];
		}else{
			$category_id = 1;
		}

		// now, write the change to the database
		$db->db_update($db_table['pages'], array($page_name, $category_id, time()), "WHERE `ID`='$page_id'", array('page_name', 'page_category', 'time_edited'));

		// show a success message
		tpl_title($lang['l_global_success']);
		tpl_content(url(script_uri(false) . '?action=edit&amp;&amp;category=' . $category_id . 'mode=edit&amp;id=' . $page_id, '&larr; ' . $lang['l_global_action_back']));
		tpl_content($lang['l_global_success_content']);
	}


	// save content
	if($asys['var_mode'] == 'edit-content-save'){
		// get the page id for current page
		$content_id = asys_get('id');
		$page_content = asys_post_nonreplace('page_content');
		$page_name = asys_post('page_name');
		$page_position = asys_post('page_position');

		// now, write the change to the database
		$db->db_update($db_table['pages_content'], array($page_name, $page_content, $page_position), "WHERE `ID`='$content_id'", array('page_name', 'page_content', 'page_position'));

		// show a success message
		display_success($lang['l_global_success_content']);
	}


	// edit content
	if($asys['var_mode'] == 'edit-content' OR $asys['var_mode'] == 'edit-content-save'){
		tpl_title($lang['l_site_edit_content_title']);
		// get the page id for current page

		$content_id = asys_get('id');
		$goback = $_GET['goback'];
		tpl_content(url(script_uri(false) . '?action=edit&amp;mode=edit&amp;id=' . $goback . '&amp;category=' . asys_get('category'), '&larr; ' . $lang['l_global_action_back']));

		// get the page content
		$page_content = $db->db_select_table($db_table['pages_content'], false, 'get_fields', "WHERE `ID`='$content_id'");
		$page_content = $page_content[0];
			
		// create form with page content
		tpl_form_start(script_uri(false) . '?action=edit&amp;mode=edit-content-save&amp;id=' . $content_id . '&amp;goback=' . $goback . '&amp;category=' . $cur_category, 'post', $lang['l_button_save']);
		tpl_form_textarea($page_content['page_content'], 'page_content', 'editor', 'editor');
		tpl_form_text('page_name', $page_content['page_name'], $lang['l_site_create_form']);
		tpl_selectform_start($lang['l_site_pages_position'], 'page_position', 1);
		tpl_selectform_option('');
		foreach($template_informations['template']['positions']['position'] as $entry){
			if($entry == $page_content['page_position']){
				$entry_selected = true;
			}else{
				$entry_selected = false;
			}
			tpl_selectform_option($entry, $entry_selected);
		}

	}

	// add content
	if($asys['var_mode'] == 'add-content'){
		// get page id and escape
		$page_id = isset($_GET['page_id'])?$db->escape($_GET['page_id']):false;

		tpl_title($lang['l_site_pages_add_content']);
		tpl_content(url(script_uri(false) . '?action=edit&amp;mode=edit&amp;category=' . asys_get('category') . '&amp;id=' . $page_id, '&larr; ' . $lang['l_global_action_back']));
		tpl_content($lang['l_site_pages_add_content_text']);

		// get the content entrys for the current page
		$page_content = $db->db_select_table($db_table['pages_content'], false, 'get_fields', "WHERE `page_id`='$page_id'");

		// get the installed languages
		$installed_languages = $db->db_select_table($db_table['languages'], false, 'get_fields', "WHERE `frontend`='1'");

		$languages_ids = array();
		foreach($page_content as $value){
			$languages_ids[] = $value['page_lang'];
		}
		tpl_form_start(script_uri(false) . '?action=edit&amp;mode=add-content-save&amp;category=' . asys_get('category') . '&amp;page_id=' . $page_id, 'post', $lang['l_button_save']);
		tpl_selectform_start($lang['l_site_pages_add_content_languages'], 'language', 1);
		$langs_usable = false;
		foreach($installed_languages as $value){
			if(!in_array($value['ID'], $languages_ids)){
				tpl_selectform_option($value['language']);
				$langs_usable = true;
			}
		}

		if(!$langs_usable) tpl_selectform_option($lang['l_site_pages_add_content_nolanguages']);
		if(!$langs_usable) tpl_error_msg($lang['l_site_pages_add_content_nolanguages_err'], '', '', true, false);

		tpl_form_textarea('', 'page_content', 'editor', 'editor', 'editor');

		tpl_form_text('page_name', $page_content['page_name'], $lang['l_site_create_form']);
		tpl_selectform_start($lang['l_site_pages_position'], 'page_position', 1);
		tpl_selectform_option('');
		foreach($template_informations['template']['positions']['position'] as $entry){
			if($entry == $page_content['page_position']){
				$entry_selected = true;
			}else{
				$entry_selected = false;
			}
			tpl_selectform_option($entry, $entry_selected);
		}

	}

	if($asys['var_mode'] == 'add-content-save'){
		// get the page id for current page
		$page_id = isset($_GET['page_id'])?$db->escape($_GET['page_id']):false;
		// get the page content
		$page_content = isset($_POST['page_content'])?$db->escape($_POST['page_content']):false;
		$page_content = str_to_asys($page_content);
		$page_name = asys_post('page_name');
		$page_position = asys_post('page_position');
		// get the language
		$language = isset($_POST['language'])?$db->escape($_POST['language']):false;

		if($language == $lang['l_site_pages_add_content_nolanguages']) tpl_error_msg($lang['l_site_pages_add_content_nolanguages'], '', '', true, false);
		// now, get the language id
		$lang_id = $db->db_select_table($db_table['languages'], false, 'get_fields', "WHERE `language`='$language'");
		$lang_id = $lang_id[0]['ID'];

		// show a "back" link
		tpl_content(url(script_uri(false) . '?action=edit&amp;mode=edit&amp;category=' . asys_get('category') . '&amp;id=' . $page_id, '&larr; ' . $lang['l_global_action_back']));

		// now, write the change to the database
		$db->db_insert($db_table['pages_content'], array($page_id, $page_name, $page_content, $lang_id, $page_position), array('page_id', 'page_name', 'page_content', 'page_lang', 'page_position'));

		// show a success message
		tpl_title($lang['l_global_success']);
		tpl_content($lang['l_global_success_content']);
	}
}

include 'footer.php';
?>