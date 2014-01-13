<?php
function chk($int){
	global $lang;
	if($int == 1){
		return '<b>' . $lang['l_global_yes'] . '</b>';
	}else{
		return '<i>' . $lang['l_global_no'] . '</i>';
	}
}

foreach($asys['asys_permissions'] as $value){
	if(in_array(1, $asys_group['groups'])){
		tpl_content($value . ':' . chk(1));
	}else{
		if(isset($_SESSION[$value])){
			tpl_content($value . ':' . chk($_SESSION[$value]));
		}else{
			tpl_content($value . ':' . chk(0));
		}
	}
}
?>