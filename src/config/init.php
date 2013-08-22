<?php
/**
 * @author Abdelkader Benkhadra
 * This file loads all the configuration files
*/

//Activate sessions
session_start();

//Generate an anti-CSRF token if one doesn't exist 
if(!isset($_SESSION['token'])) 
{
	$_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
} 

// Include necessary files
include_once 'class.db_connect.php';
include_once 'db-cred.php';
include_once '/../common/class.admin.php';
include_once '/../common/class.users.php';
include_once '/../common/class.sites.php';
include_once '/../common/class.business.php';

// Define the configuration constants
foreach($C as $name => $val )
{
	define($name, $val);
}

// Remove warnings
if(DEBUG_MODE == TRUE)
{
	error_reporting(E_ALL ^ E_NOTICE);
}
else
{
	error_reporting(0);
}

// Create a PDO object
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS);

?> 