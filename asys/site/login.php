<?php

include ('headerconfig.php');

// simple login form function
function loginform(){
	global $tpl;
	global $lang;
	global $conf;
	global $asys_conf;
	$tpl->newBlock("form");
	$tpl->assign(array('form_submit' => $lang['l_button_login'], 'form_action' => 'login.php?action=login', 'form_method' => 'post'));
	$tpl->newBlock("formtext");
	$tpl->assign(array('form_name' => 'username', 'form_desc' => $lang['l_login_form_username']));
	$tpl->newBlock("formtext");
	$tpl->assign(array('form_name' => 'password', 'form_desc' => $lang['l_login_form_password'], 'form_type' => 'password'));
	if(isset($asys_conf['ldap_auth']) AND $asys_conf['ldap_auth'] == 1){
	 tpl_form_check(chk_checkbox(1, 2), 'LDAP', '1', 'LDAP Login');
	}


}

$tpl->assign("_ROOT.page_title", $lang['l_login_title']);

// login overview
if ($asys['var_action'] != 'login'){
	$tpl->newBlock("content");
	$tpl->assign("content", $lang['l_login_content']);

	// login form
	loginform();
}

if ($asys['var_action'] == 'login'){
	// set variables for username and password
	$login_username = isset($_POST['username'])?$db->escape($_POST['username']):'guest';
	$login_password = isset($_POST['password'])?$db->escape($_POST['password']):'guestpwd';
	$ldap_login = isset($_POST['LDAP'])?$db->escape($_POST['LDAP']):false;
	if(isset($asys_conf['ldap_auth']) AND $asys_conf['ldap_auth'] == 1 AND $ldap_login == 1 AND ext_loaded('ldap')){
		include '../DATA/lib/ldap/adLDAP.php';
		$ldap = new adLDAP(array('baseDn'=>$asys_conf['baseDn']));
		$ldap->close();
		if(isset($asys_conf['ldap_accountSuffix'])) $ldap->setAccountSuffix($asys_conf['ldap_accountSuffix']);
		if(isset($asys_conf['ldap_domainControllers'])) $ldap->setDomainControllers(array($asys_conf['ldap_domainControllers']));
		if(isset($asys_conf['ldap_adminUsername'])) $ldap->setAdminUsername($asys_conf['ldap_adminUsername']);
		if(isset($asys_conf['ldap_adminPassword'])) $ldap->setAdminPassword($asys_conf['ldap_adminPassword']);
		$ldap->connect();
		$authUser = $ldap->user()->authenticate($login_username, $login_password);
		if ($authUser == true) {
			$selected_users = $db->db_select_table($db_table['users'], $noid = false, $fields = array('ID', 'username', 'password'), $where = "WHERE (`username` like '$login_username')", $limit = 1);
			if(isset($selected_users[0])){
				$db->db_update($db_table['users'], array(asys_encrypt($login_password)), "WHERE `username`='$login_username'", array('password'));
			}else{
				$db->db_insert($db_table['users'], array($login_username, asys_encrypt($login_password), 1), array('username', 'password', 'enable_backend'));
			}
		}
		else {
		}
	}
	// try to get the user
	$selected_username = $db->db_select_table($db_table['users'], $noid = false, $fields = array('ID', 'username', 'password'), $where = "WHERE `username` LIKE '$login_username' AND `enable_backend`='1'", $limit = 1);
	if(isset($selected_username[0])){
		$selected_users = $db->db_select_table($db_table['users'], $noid = false, $fields = array('ID', 'username', 'password'), $where = "WHERE (`username` like '$login_username') AND (`password` = '" . asys_encrypt($login_password, $selected_username[0]['password']) . "') AND (`enable_backend`='1')", $limit = 1);
	}	
	//

	/*
	 * login successful
	 */
	if(isset($selected_users[0]['username']) AND $selected_users[0]['username'] != ''){
		// set the wrong login count to 0
		if($security){
			$login_user_conf = $db->asys_select_usr_conf($selected_users[0]['ID']);
			if(!isset($login_user_conf['wrong_logins'])){
				$login_user_conf['wrong_logins'] = 0;
			}
			if($selected_users[0]['ID'] != 1 AND $login_user_conf['wrong_logins'] >= $asys_conf['asys_max_wrong_login_count']){
				asys_log('locked-login', 'a login with the locked user ' . $login_username);
				display_error($lang['l_login_locked'], true);
			}else{
				$db->asys_add_usr_conf($selected_users[0]['ID'], 'wrong_logins', 0);
				$db->asys_log_push('good-login', 'user ' . $login_username . ' now logged in');
			}
		}
		// write the user data to the session
		$_SESSION['user_id'] = $selected_users[0]['ID']; // user-id
		$_SESSION['user'] = $selected_users[0]['username']; // username
		$_SESSION["ip_adress"] = $_SERVER['REMOTE_ADDR']; // ip
		$_SESSION["useragent"] = $_SERVER['HTTP_USER_AGENT']; // useragent

		// get permissions for this user
		$users_permissions = $db->asys_load_permissions($selected_users[0]['ID']);

		// write permissions in session
		foreach($users_permissions as $key=>$value){
			$_SESSION[$key] = $value;
		}

		// login success message
		display_success($lang['l_login_success']);


		// set javascript forward
		tpl_content('<script type="text/javascript">setTimeout("self.location.href=\'index.php\'",1000);</script>', false);
		
		/*
		 * login failed
		 */
	}else{
		// set variable for username
		$login_username = isset($_POST['username'])?$db->escape($_POST['username']):'guest';

		// try to find a user based on the filled-in username
		$good_username = $db->asys_get_users($login_username);

		// if asys found a user, +1 the wrong login count
		if(isset($good_username[0]['username'])){
			$good_username_usrconf = $db->asys_select_usr_conf($good_username[0]['ID']);
			if(!isset($good_username_usrconf['wrong_logins'])){
				$good_username_usrconf['wrong_logins'] = 0;
			}
			$db->asys_renew_usr_conf($good_username[0]['ID'], 'wrong_logins', 1 + $good_username_usrconf['wrong_logins']);
			$db->asys_log_push('wrong-login', 'a wrong login with the existing username ' . $login_username);
		}else{
			$db->asys_log_push('non-user-login', 'a login with the not existing username ' . $login_username);
		}

		// tell the user, that the login was incorrect
		display_error($lang['l_login_failed']);

		// show the login form again
		loginform();
	}
}

include('footer.php');
?>