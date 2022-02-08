<?php
/* Krátký script, který se zavolá login.php a switch_admin.php 
* Zařizuje při odhlášení změnění statusu na odhlášení a změna adminu na náhled.
*/
require_once("login.php");
require_once("../admin/switch_admin.php");
$obj = new login("logout","","");
$admin = new switch_admin("");
$admin->set_admin("OFF");
?>