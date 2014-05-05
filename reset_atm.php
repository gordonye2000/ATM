<?php
include_once('config.php');

$atm = atm::getInstance($db);

$atm->update_atm(array('total'=>900, 'fifty'=>10, 'twenty'=>20));

?>

Please click <a href="index.php">here</a> to go back index page.
