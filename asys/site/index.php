<?php 
include('headerconfig.php'); 

tpl_title($lang['l_index_title']);
tpl_content($lang['l_index_content']);

// if user logged in, show a combined overview menu
if($user_mode AND isset($asys_conf['startpage_dashboard']) AND  $asys_conf['startpage_dashboard'] == 1){	
include $dir['system_dir'] . 'combined_overview.php';	
}

include('footer.php');
?>
