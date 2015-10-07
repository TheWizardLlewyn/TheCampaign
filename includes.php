<?php
/**
 * Loads important files and classes
 */

error_reporting(E_ALL);
ini_set("display_errors",1);
session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = &$_SESSION['user'];
}
include("config/server_config.php");
include("config/reference_vars.php");
include("utilities/DatabaseUtil.php");
$db = new DatabaseUtil();

include("objects/user.php");

?>