<?php

$myServer = 'xxx';
$myUser = 'xxx'; 
$myPass = 'xxx';
$myDB = 'xxx';
$default_customer_id = '1';

// Magic class load : load the classes
function __autoload($class_name) {
	$name_parts = explode("_", $class_name);
	foreach ($name_parts as $key => $name) {
		if (strtolower($name) == 'class') {
			unset($name_parts[$key]);
		}
	}
	$class_name = implode("_", $name_parts);
	$class_file_name = $class_name.".php";
	require_once ($class_file_name);
}


$db = db_class::getInstance();
if (!$db->connect($myServer, $myUser, $myPass, $myDB, true))  
{
	$db->print_last_error(false);
}
?>