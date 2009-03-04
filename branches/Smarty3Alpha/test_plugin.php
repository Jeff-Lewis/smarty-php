<?php
 /**
* Test script for the function plugin tag
* @author Uwe Tews 
* @package SmartyTestScripts
*/

require('./libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
$smarty->caching = true;
$smarty->caching_lifetime = 10;


$smarty->display('test_plugin.tpl');


?>