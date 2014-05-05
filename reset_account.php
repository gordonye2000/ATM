<?php
include_once('config.php');

$account = account::getInstance($db);
$account->update_account(array('balance'=>500));

?>

Please click <a href="index.php">here</a> to go back index page.
