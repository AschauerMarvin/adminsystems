<?php
include ("asys_db_{$conf['db_type']}.php");

class asys_db extends asys_database {
	/*
	 * Get configuration values
	 */
	public function asys_select_conf($application = 'system'){
		global $db_table;
		$table = $db_table['config'];
		if ($application == ''){
			$conf_app = 'sys_generic_app';
		}
		$result = $this->db_select_table($table, $noid = true, $fields = 'get_fields', $where = "WHERE `application` = '$application' AND `config_enabled`='1'");

		$conf_values = array();
		$conf_values['application'] = $application;
		foreach($result as $value){
			$conf_values[$value['config_name']] = $value['config_value'];
		}

		return $conf_values;
	}

	/*
	 * write configuration values
	 */
	public function asys_add_conf($conf_app, $conf_name, $conf_value, $conf_desc = ''){
		global $db_table;
		$table = $db_table['config'];
		if ($conf_app == ''){
			$conf_app = 'sys_generic_app';
		}
    $this->asys_del_conf($conf_app, $conf_name);
		$insert_values = array($conf_app, $conf_name, $conf_value, 1, $conf_desc);
		$result = $this->db_insert($table, $insert_values);
	}

	/*
	 * delete configuration values
	 */
	public function asys_del_conf($conf_app, $conf_name, $uninstall = false){
		// db_delete($table, $where)
		global $db_table;
		$table = $db_table['config'];
		if ($conf_app == ''){
			$conf_app = 'sys_generic_app';
		}
		if ($uninstall == TRUE){
			$where = "WHERE `application` = '$conf_app' AND NOT `application`='system'";
		}else{
			$where = "WHERE `application` = '$conf_app' AND `config_name` = '$conf_name'";
		}
		$result = $this->db_delete($table, $where);
	}

	public function asys_renew_conf($conf_app, $conf_name, $new_value){
		$this->asys_del_conf($conf_app, $conf_name, FALSE);
		$this->asys_add_conf($conf_app, $conf_name, $new_value);
	}


	/*
	 * ---- Users
	 */

	/*
	 * Get configuration values
	 */
	public function asys_select_usr_conf($uid = 1){
		global $db_table;
		$table = $db_table['users_variables'];

		$result = $this->db_select_table($table, $noid = true, $fields = 'get_fields', $where = "WHERE `uid` = '$uid'");

		$conf_values = array();
		$conf_values['uid'] = $uid;
		foreach($result as $value){
			$conf_values[$value['conf_name']] = $value['conf_value'];
		}

		return $conf_values;
	}

	/*
	 * get users
	 */
	public function asys_get_users($name = 'all'){
		global $db_table;
		$table = $db_table['users'];
		if($name == 'all') $name = '%';

		$result = $this->db_select_table($table, $noid = false, $fields = 'get_fields', $where = "WHERE `username` LIKE '$name'");

		return $result;
	}

	/*
	 * write configuration values
	 */
	public function asys_add_usr_conf($uid, $conf_name, $conf_value){
		global $db_table;
		$table = $db_table['users_variables'];
		$this->asys_del_usr_conf($uid, $conf_name);
		$insert_values = array($uid, $conf_name, $conf_value);
		$result = $this->db_insert($table, $insert_values);
	}

	/*
	 * delete configuration values
	 */
	public function asys_del_usr_conf($uid, $conf_name, $uninstall = false){
		global $db_table;
		$table = $db_table['users_variables'];

		if ($uninstall){
			$where = "WHERE `uid` = '$uid' AND NOT `uid`='1'";
		}else{
			$where = "WHERE `uid` = '$uid' AND `conf_name` = '$conf_name'";
		}
		$result = $this->db_delete($table, $where);
	}

	public function asys_renew_usr_conf($uid, $conf_name, $new_value){
		$this->asys_del_usr_conf($uid, $conf_name);
		$this->asys_add_usr_conf($uid, $conf_name, $new_value);
	}


	/*
	 * ---- Groups
	 */
	public function asys_get_groups($uid){
		global $db_table;
		$table = $db_table['users_groups'];

		$result = $this->db_select_table($table, $noid = true, $fields = array('group_id'), $where = "WHERE `user_id` = '$uid'");

		return $result;
	}

	/*
	 * Get configuration values
	 */
	public function asys_select_grp_conf($gid = 1){
		global $db_table;
		$table = $db_table['groups_variables'];

		$result = $this->db_select_table($table, $noid = true, $fields = 'get_fields', $where = "WHERE `gid` = '$gid'");

		$conf_values = array();
		$conf_values['gid'] = $gid;
		foreach($result as $value){
			$conf_values[$value['conf_name']] = $value['conf_value'];
		}

		return $conf_values;
	}


	/*
	 * write configuration values
	 */
	public function asys_add_grp_conf($gid, $conf_name, $conf_value){
		global $db_table;
		$table = $db_table['groups_variables'];

		$this->asys_del_grp_conf($gid, $conf_name);
		$insert_values = array($gid, $conf_name, $conf_value);
		$result = $this->db_insert($table, $insert_values);
	}

	/*
	 * delete configuration values
	 */
	public function asys_del_grp_conf($gid, $conf_name, $uninstall = false){
		global $db_table;
		$table = $db_table['groups_variables'];

		if ($uninstall){
			$where = "WHERE `gid` = '$gid' AND NOT `gid`='1'";
		}else{
			$where = "WHERE `gid` = '$gid' AND `conf_name` = '$conf_name'";
		}
		$result = $this->db_delete($table, $where);
	}

	public function asys_renew_grp_conf($gid, $conf_name, $new_value){
		$this->asys_del_grp_conf($gid, $conf_name);
		$this->asys_add_grp_conf($gid, $conf_name, $new_value);
	}

	/*
	 * Gets all Modules
	 */

	public function asys_get_modules(){
		global $db_table;
		return $this->db_select_table($db_table['modules']);
	}

	/*
	 * Loads the permissions from all groups into an array
	 * Permission mode: allow wins
	 * 1|0|0 = allow
	 * 0|0|0 = forbit
	 */

	public function asys_load_permissions($uid = 'get'){

		// try to get the user from $asys_user variable
		if ($uid == 'get'){
			global $asys_user;

			$user_id = $asys_user['asys_u_userid'];
		}else{
			// or select the $uid variable
			$user_id = $uid;
		}
		// get the groups from the selected user
		$groups = $this->asys_get_groups($user_id);
		// build an array for all group-permissions array(0 -> array(), 1 -> array())
		$permission_array = array();
		for ($i = 0; $i < count($groups); $i++) {
			$permission_array[] = $this->asys_select_grp_conf($groups[$i]['group_id']);
		}
		// load all asys_permissions from $asys variable into $asys_permissions
		global $asys;
		$asys_permissions = $asys['asys_permissions'];

		/*debug */
		if($asys['DEBUG_MODE']) asys_debug_output($asys_permissions, 'Asys Permissions');
		if($asys['DEBUG_MODE']) asys_debug_output($permission_array, 'Load_Permission Array');
		/*debug */

		// now, wrote down all permissions in the $allover_permissions array
		for ($i = 0; $i < count($permission_array); $i++) {
			for ($int = 0; $int < count($asys_permissions); $int++) {

				if(isset($allover_permissions[$asys_permissions[$int]])){
					$permission_array[$i][$asys_permissions[$int]] = isset($permission_array[$i][$asys_permissions[$int]])?$permission_array[$i][$asys_permissions[$int]]:0; // if permission is not set, permission is 0
					$allover_permissions[$asys_permissions[$int]] .= '|' . $permission_array[$i][$asys_permissions[$int]];
				}else{
					$permission_array[$i][$asys_permissions[$int]] = isset($permission_array[$i][$asys_permissions[$int]])?$permission_array[$i][$asys_permissions[$int]]:0; // if permission is not set, permission is 0
					$allover_permissions[$asys_permissions[$int]] = $permission_array[$i][$asys_permissions[$int]];
				}

			}
		}
		/*debug */
		if($asys['DEBUG_MODE']) asys_debug_output($allover_permissions, 'Allover Permissions');
		/*debug */
		for ($i = 0; $i < count($allover_permissions); $i++) {

			// now, choose the final permission
			// must all permissions be 0 to deny or only one?
			if($asys['all_must_null']){
				if(strpos($allover_permissions[$asys_permissions[$i]], "1") !==false){
					$allover_permissions[$asys_permissions[$i]] = 1;
				}else{
					$allover_permissions[$asys_permissions[$i]] = 0;
				}
			}else{
				if(strpos($allover_permissions[$asys_permissions[$i]], "0") !==false){
					$allover_permissions[$asys_permissions[$i]] = 0;
				}else{
					$allover_permissions[$asys_permissions[$i]] = 1;
				}
			}
		}
		/*debug */
		if($asys['DEBUG_MODE']) asys_debug_output($allover_permissions, 'Allover Permissions');
		/*debug */

		return $allover_permissions;
	}
	
	public function asys_get_languages($where = "WHERE `backend`='1'"){
		global $db_table;
		$languages = $this->db_select_rows($db_table['languages'], $where);
		
		return $languages;
	}
	/*
	 * Adds one log-entry to the database
	 */

	public function asys_log_push($log_type, $log){
		global $db_table;
		global $asys_user;
		$asys_user['asys_u_user'] = isset($asys_user['asys_u_user'])?$asys_user['asys_u_user']:'sysusr'; // adminsystems ?action variable
		$asys_user['asys_u_ip'] = isset($asys_user['asys_u_ip'])?$asys_user['asys_u_ip']:$_SERVER['REMOTE_ADDR']; // adminsystems ?action variable
		$asys_user['asys_u_useragent'] = isset($asys_user['asys_u_useragent'])?$asys_user['asys_u_useragent']:$_SERVER['HTTP_USER_AGENT']; // adminsystems ?action variable
		$log_entry = array(
		$log_type,
		$asys_user['asys_u_user'],
		time(),
		$asys_user['asys_u_ip'],
		$asys_user['asys_u_useragent'],
		$log
		);
		$this->db_insert($db_table['logs'], $log_entry);
	}
	
}
?>