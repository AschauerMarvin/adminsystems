This is a small tool to compare two language packs. 
<br /><br />
It's used to create new language packs
<br /><br />
Add your language file to the URL. e.g. ?lang=de

<?php
$param = $_GET['lang'];
$param = str_replace('..', '', $param);


include '../../languages/en.php';

$lang_params = array();
foreach($lang as $key=>$value){
$lang_params[] = $key;
}

unset($lang);

if($param != ''){
include "../../languages/{$param}.php";

$unset_langs = array();
foreach($lang_params as $value){
if(!isset($lang[$value])){
$unset_langs[] = $value;	
}
}

echo '<br /> <br />Unset:';
echo '<pre>';
print_r($unset_langs);
echo '</pre>';
}
?>