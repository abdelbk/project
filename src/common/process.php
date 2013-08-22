<?php
/**
 * @author Abdelkader Benkhadra
*/

//Activate sessions
session_start();

// Include necessary files
include_once '/../config/class.db_connect.php';
include_once '/../config/db-cred.php';
include_once 'class.admin.php';

// Define the configuration constants
foreach($C as $name => $val )
{
	define($name, $val);
}

// Create a PDO object
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS);

// Create a lookup array for login action
$actions = array(
		'user_login' => array(
			'object' => 'Admin',
			'method' => 'processLoginForm',
			'header' => 'Location: /../app/index.php'
		),
		'user_logout' => array(
			'object' => 'Admin',
			'method' => 'processLogout',
			'header' => 'Location: /../app/login.php'
		)
	);
	
/* 
 * Make sure the anti-CSRF token was passed and that the
 * requested action exists in the lookup array
*/
if ($_POST['token'] == $_SESSION['token']
		&& isset($actions[$_POST['action']]))
{
	$use_array = $actions[$_POST['action']];
	$obj = new $use_array['object']($dbo);
	if(TRUE === $msg = $obj->$use_array['method']())
	{
		header($use_array['header']);
	}
	// If an error occured, output it and end execution
	else
	{
		die($msg);
	}
}
// Redirect to the login form if token/action is invalid
else
{
	header("Location: /../app/login.php");
}

?> 