<?php 

function script_uri($get_vars = true){
	if($get_vars == false){
		$break = explode('/', $_SERVER["SCRIPT_NAME"]);
		return $break[count($break) - 1];
	}else{
		$break = explode('/', $_SERVER['REQUEST_URI']);
		return $break[count($break) - 1];
	}
}


// contains the title of the current page
function the_title($display = true){
	if($display){
		echo asys_get_content('title');
	}else{
		return asys_get_content('title');
	}
}

// contains the content of the current page
function the_content($display = true){
	global $asys;
	if(!$asys['content']){
		if($display){
			echo asys_get_content('content', $asys['content']);
		}else{
			return asys_get_content('content', $asys['content']);
		}
	}else{
		if($display){
			echo asys_get_content('content');
		}else{
			return asys_get_content('content');
		}
	}


}
// contains the current used template file
function the_template(){
	return asys_get_content('tpl');
}

// internal function to get the page content
function asys_get_content($return = 'content'){
	global $db;
	global $db_table;
	global $asys;
	global $asys_conf;
	global $tpl_inf;

	$error = false;
	$content_mode = $asys['content'];
	if(!$content_mode){
		$nav_entry = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `entry_alias`='{$asys['page']}'");
		$nav_entry = isset($nav_entry[0])?$nav_entry[0]:false;
	}else{
		$page_id = $db->db_select_rows($db_table['pages'], "WHERE `page_name`='$content_mode'");
		if(isset($page_id[0])){
			$nav_entry['asys_parameter'] = $page_id[0]['ID'];
		}else{
			$error = true;
			$error_info = '404 - Not Found';
		}
	}
	if(!$nav_entry){
		$error = true;
		$error_info = '404 - Not Found';
	}else{
		$page_info = $db->db_select_rows($db_table['pages'], "WHERE `ID`='{$nav_entry['asys_parameter']}'");
		$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys['lang']}'");
		$language = isset($language[0])?$language[0]:false;
		if(!$language){
			$language = $db->db_select_rows($db_table['languages'], "WHERE `language`='{$asys['lang']}'");
			$language = isset($language[0])?$language[0]:false;
		}
			
		if(!$language){
			$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys_conf['asys_language']}'");
			$language = isset($language[0])?$language[0]:false;
		}

		if(!$language){
			$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$nav_entry['asys_parameter']}'");
			if(isset($the_content[0])){
				$the_content = $the_content[0];
			}else{
				$error = true;
				$error_info = 'a_nolang';
			}
		}else{
			$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$nav_entry['asys_parameter']}' AND `page_lang`={$language['ID']}");
			if(isset($the_content[0])){
				$the_content = $the_content[0];
			}else{
				$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$nav_entry['asys_parameter']}'");
				if(isset($the_content[0])){
					$the_content = $the_content[0];
				}else{
					$error = true;
					$error_info = '404 - Not Found';
				}
			}
		}
		if(!$error){
			if($return == 'content'){
				return $the_content['page_content'];
			}elseif($return == 'title'){
				return $the_content['page_name'];
			}elseif($return == 'tpl'){
				if($nav_entry['entry_tpl'] == ''){
					$nav_entry['entry_tpl'] = $tpl_inf['template']['files']['filename'][0];
				}
				return $nav_entry['entry_tpl'];
			}
		}

	}
	if($error){
		return $error_info;
	}else{
		return FALSE;
	}
}


/* Displays a single content page
 * Expects: $params [array]
* Values:
* * page: the name of the page to show
* * page-id: the id of the page to show
* * display: if this parameter is set, the content will be displayed automatically
* * heading: set the header of the page title to show. h1, h2, h3, h4, h5, ...
* * category: the id of the category
*
*/
function get_content($params){
	global $asys;
	global $asys_conf;
	global $db;
	global $db_table;
	$page_name = isset($params['page'])?$params['page']:false;
	$page_id = isset($params['page-id'])?$params['page-id']:false;
	$display = isset($params['display'])?true:false;
	$heading = isset($params['heading'])?$params['heading']:'h1';
	$category = isset($params['category'])?$params['category']:false;

	$error = false;
	$category_mode = false;

	if($page_name != false){
		$selected_page = $db->db_select_rows($db_table['pages'], "WHERE `page_name`='{$page_name}'");
	}elseif($page_id != false){
		$selected_page = $db->db_select_rows($db_table['pages'], "WHERE `ID`='{$page_id}'");
	}elseif($category != false){
		$selected_page = $db->db_select_rows($db_table['pages'], "WHERE `page_category`='{$category}'");
		$category_mode = true;
	}else{
		return false;
	}
	if(!$category_mode){
		$selected_page = isset($selected_page[0])?$selected_page[0]:false;
		if(!$selected_page){
			$error = true;
		}
	}
	$the_content = '';
	$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys['lang']}'");
	$language = isset($language[0])?$language[0]:false;
	if(!$language){
		$language = $db->db_select_rows($db_table['languages'], "WHERE `language`='{$asys_conf['asys_language']}'");
		$language = isset($language[0])?$language[0]:false;
	}

	if(!$language){
		$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys_conf['asys_language']}'");
		$language = isset($language[0])?$language[0]:false;
	}

	if(!$category_mode){
		if(!$language){
			$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$selected_page['ID']}'");
			if(isset($the_content[0])){
				$the_content = $the_content[0];
			}else{
				$error = true;
				$error_info = 'a_nolang';
			}
		}else{
			$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$selected_page['ID']}' AND `page_lang`={$language['ID']}");
			if(isset($the_content[0])){
				$the_content = $the_content[0];
			}else{
				$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$selected_page['ID']}'");
				if(isset($the_content[0])){
					$the_content = $the_content[0];
				}else{
					$error = true;
					$error_info = '404';
				}
			}

		}

		if($display == TRUE AND $error == FALSE){

			//$the_content = $the_content['page_content'];
			echo '<' .$heading . '>' . $the_content['page_name'] . '</' .$heading . '>';
			echo $the_content['page_content'];
		}
		if(!$error AND $display == false){
			//$the_content = $the_content['page_content'];
			return array('page_title' => $the_content['page_name'], 'page_content' => $the_content['page_content']);
		}else{
			return $error_info;
		}
	}else{
		// category mode
		$the_content = array();
		foreach($selected_page as $entry){
			unset($cur_content);
			$cur_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_id`='{$entry['ID']}' AND `page_lang`={$language['ID']}");
				
			if(isset($cur_content[0])){
				$the_content[] = $cur_content[0];
			}
				

		}
		return $the_content;
	}
}

/* Returns all Entrys to a specific position or displays the first entry
 * Expects: $params[array()]
* Values:
*
*/
function get_position($params){
	global $asys;
	global $asys_conf;
	global $db;
	global $db_table;
	$position = isset($params['position'])?$params['position']:$tpl_inf['template']['positions']['position'][0];
	$display = isset($params['display'])?true:false;
	$heading = isset($params['heading'])?$params['heading']:'h1';

	$the_content = '';
	$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys['lang']}'");
	$language = isset($language[0])?$language[0]:false;
	if(!$language){
		$language = $db->db_select_rows($db_table['languages'], "WHERE `language`='{$asys_conf['asys_language']}'");
		$language = isset($language[0])?$language[0]:false;
	}

	if(!$language){
		$language = $db->db_select_rows($db_table['languages'], "WHERE `lang`='{$asys_conf['asys_language']}'");
		$language = isset($language[0])?$language[0]:false;
	}

	if(!$language){
		$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_position`='$position'");
		if(!isset($the_content[0])){
			$error = true;
			$error_info = 'a_nolang';
		}
	}else{
		$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_position`='$position' AND `page_lang`='{$language['ID']}'");
		if(!isset($the_content[0])){
			$the_content = $db->db_select_rows($db_table['pages_content'], "WHERE `page_position`='$position'");
			if(!isset($the_content[0])){
				$error = true;
				$error_info = '404';
			}
		}
	}

	if($display == true AND $error == false){
		$the_content = $the_content[0]['page_content'];
		echo $the_content;
	}
	if(!$error AND $display == false){
		return $the_content;
	}else{
		return $error_info;
	}
}



function display_navigation($params){
	global $db;
	global $asys;
	global $tpl_inf;
	global $db_table;
	$error = false;

	if(isset($params['nav'])){
		$get_mode = 'NAME';
	}elseif(isset($params['nav_id'])){
		$get_mode = 'ID';
	}else{
		$error = true;
	}

	// get language id
	$language_id = $db->db_select_rows($db_table['languages'], "WHERE `language`='{$asys['lang']}' OR `lang`='{$asys['lang']}'");
	if(isset($language_id[0])){
		$language_id = $language_id[0]['ID'];
	}else{
		$language_id = $db->db_select_rows($db_table['languages']);
		$language_id = $language_id[0]['ID'];
	}
	if($get_mode == 'NAME'){
		$error = true;
		$nav_entrys = array();

		$do_navs = $tpl_inf['template']['navigations']['navigation'];
		if(count($do_navs) <= 1){
			$do_navs = $tpl_inf['template']['navigations'];
		}
		foreach($do_navs as $key=>$entry){
			if(strtolower($entry) == strtolower($params['nav'])){
				$error = false;
				$nav_entrys = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `menu_id`='$key' ORDER BY `sort_value` ASC");
			}
		}
	}elseif($get_mode == 'ID'){
		$nav_entrys = $db->db_select_rows($db_table['navigation_entrys'], "WHERE `menu_id`='{$params['nav_id']}' ORDER BY `sort_value` ASC");
	}

	$selected = isset($params['selected'])?$params['selected']:' active';
	$between = isset($params['between'])?$params['between']:'';
	$first = isset($params['first'])?$params['first']:'';
	$last = isset($params['last'])?$params['last']:'';
	$target = isset($params['target'])?$params['target']:'target="_blank"';
	$style = isset($params['style'])?$params['style']:'<li class="{first}{last}{active}"><a {target} href="{entry_url}">{entry_name}</a>{dropdown_entrys}</li> {between}';
	$page = $asys['page'];
	$get_id = '';
	$i = 0;
	$navigation_menu = array();
	foreach($nav_entrys as $entry){
		$between_var = '';
		if(count($nav_entrys) != $i + 1){
			$between_var = $between;
		}else{
			$between_var = '';
		}
		if($entry['entry_alias'] == $page){
			$get_class = $selected;
			if (!empty($selected_id)) $get_id = $selected_id;
		}else{
			$get_class = '';
			$get_id = '';
		}
		$entry_link = $style;

		$entry['entry_name'] = unserialize($entry['entry_name']);
		if(isset($entry['entry_name'][$language_id])){
			$entry['entry_name'] = $entry['entry_name'][$language_id];
		}else{
			$entry['entry_name'] = @current($entry['entry_name']);
			if($entry['entry_name'] == '') $entry['entry_name'] = 'Entry';
		}

		$navigation_menu[$entry['ID']] =
		array(
				'entry_link' => $entry_link,
				'entry_sub'	=> $entry['entry_sub'],
				'entry_alias' => $entry['entry_alias'],
				'entry_name' => $entry['entry_name'],
				'entry_mode' => $entry['entry_mode'],
				'asys_parameter' => $entry['asys_parameter'],
				'between_var' => $between_var
		);
		$i++;
	}
	$dropdowns = array();

	foreach($navigation_menu as $key=>$entry){
		if($entry['entry_sub'] != ''){
			$navigation_menu[$entry['entry_sub']]['dropdowns'] = '1';
			$dropdowns[$entry['entry_sub']][] = $entry;
			unset($navigation_menu[$key]);
		}
	}
	foreach($navigation_menu as $key=>$entry){
		if($entry['entry_alias'] == $page){
			$get_class = $selected;
			if (!empty($selected_id)) $get_id = $selected_id;
		}else{
			$get_class = '';
			$get_id = '';
		}
		if(isset($dropdowns[$key])){
			foreach($dropdowns[$key] as $dr=>$dropdown){
				if($dropdown['entry_alias'] == $page){
					$get_class = $selected;
					if (!empty($selected_id)) $get_id = $selected_id;
				}else{
					$get_class = '';
					$get_id = '';
				}
				if($dropdown['entry_mode'] == 'external_url'){
					$set_target = $target;
				}else{
					$set_target = '';
				}
				if($dropdown['entry_mode'] == 'external_url'){
					$set_link = $dropdown['asys_parameter'];
				}else{
					$set_link = 'index.php?page=' . $dropdown['entry_alias'] . '&amp;lang=' . $asys['lang'];
				}
				$find=array('{id}', '{active}', '{entry_url}', '{entry_name}', '{target}', '{between}', '{dropdown_entrys}');
				$replace = array($get_id, $get_class, $set_link, $dropdown['entry_name'], $set_target, $entry['between_var'], '');
				$dropdowns[$key][$dr] = str_replace($find, $replace, $style);
			}

				
			foreach($dropdowns[$key] as $drop){
				$cur_dropdowns[$key] .= $drop;
			}
			if($entry['entry_alias'] == $page){
				$get_class = $selected;
				if (!empty($selected_id)) $get_id = $selected_id;
			}else{
				$get_class = '';
				$get_id = '';
			}
			if($entry['entry_mode'] == 'external_url'){
				$set_target = $target;
			}else{
				$set_target = '';
			}
			if($entry['entry_mode'] == 'external_url'){
				$set_link = $entry['asys_parameter'];
			}else{
				$set_link = 'index.php?page=' . $entry['entry_alias'] . '&amp;lang=' . $asys['lang'];
			}
				
			$find=array('{id}', '{active}', '{entry_url}', '{entry_name}', '{target}', '{between}', '{dropdown_entrys}');
			$replace = array($get_id, $get_class, $set_link, $entry['entry_name'], $set_target, $entry['between_var'], '<ul>' . $cur_dropdowns[$key] . '</ul>');
			$navigation_menu[$key] = str_replace($find, $replace, $style);
		}else{
			if($entry['entry_alias'] == $page){
				$get_class = $selected;
				if (!empty($selected_id)) $get_id = $selected_id;
			}else{
				$get_class = '';
				$get_id = '';
			}
			if($entry['entry_mode'] == 'external_url'){
				$set_target = $target;
			}else{
				$set_target = '';
			}
			if($entry['entry_mode'] == 'external_url'){
				$set_link = $entry['asys_parameter'];
			}else{
				$set_link = 'index.php?page=' . $entry['entry_alias'] . '&amp;lang=' . $asys['lang'];
			}
			$find=array('{id}', '{active}', '{entry_url}', '{entry_name}', '{target}', '{between}', '{dropdown_entrys}');
			$replace = array($get_id, $get_class, $set_link, $entry['entry_name'], $set_target, $entry['between_var'], '');
			$navigation_menu[$key] = str_replace($find, $replace, $style);
		}

	}

	if($first != '' OR $last != ''){
		$count_naventrys = count($navigation_menu);
		$i = 1;
		foreach($navigation_menu as $key=>$entry){
			if($i == 1){
				$navigation_menu[$key] = preg_replace('/{first}/', $first, $navigation_menu[$key], 1);
			}
			if($i == $count_naventrys){
				$navigation_menu[$key] = preg_replace('/{last}/', $last, $navigation_menu[$key], 1);
			}
			$i++;
			$navigation_menu[$key] = str_replace(array('{first}', '{last}'), array('', ''), $navigation_menu[$key]);
		}
	}else{
		$navigation_menu = str_replace(array('{first}', '{last}'), array('', ''), $navigation_menu);
	}
	foreach ($navigation_menu as $entry){
		echo $entry;
	}
	if($error){
		return false;
	}else{
		return $navigation_menu;
	}


}

/*
 * Returns or displays all language links
*/
function display_languages($params = ''){
	global $db;
	global $asys;
	global $db_table;
	$display = isset($params['display'])?true:false;
	$style = isset($params['style'])?$params['style']:'<a href="{lang_link}">{u_lang_name}</a>{between}';
	$between = isset($params['between'])?$params['between']:' ';

	$languages = $db->db_select_rows($db_table['languages'], "WHERE `frontend`='1'");
	$language_links = array();
	$i=0;
	foreach($languages as $lang){
		$between_var = '';
		if(count($languages) != $i + 1){
			$between_var = $between;
		}
		$find=array('{lang_link}', '{lang_name}', '{u_lang_name}', '{f_lang_name}', '{between}');
		$replace = array(script_uri(false) . '?page=' . $asys['page'] . '&amp;lang=' . $lang['lang'], $lang['lang'], strtoupper($lang['lang']), $lang['language'], $between_var);
		$lang_link = str_replace($find, $replace, $style);

		$language_links[] = $lang_link;

		$i++;
	}

	if($display){
		echo implode('', $language_links);
	}else{
		return $language_links;
	}

}
?>