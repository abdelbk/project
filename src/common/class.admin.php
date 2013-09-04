<?php
/**
 * @author Abdelkader Benkhadra
*/

class Admin extends DB_Connect {

/**
 * Stores or creates a database object and sets the salt length
 *
 * @param object $db, a database object
*/
public function __construct($db = NULL)
{
	parent::__construct($db); 
}

/**
 * Checks login credentials for valid user
 *
 * @return TRUE on success or an error message otherwise
*/
public function processLoginForm()
{
	$error = 'Votre combinaison email/mot de passe est invalide';

	// Fail if the proper action was not submitted
	if( $_POST['action'] != 'user_login' )
	{
		return "Invalid action supplied for processLoginForm";
	}

	// Escape the user input for security
	$email 	  = htmlentities($_POST['email'], ENT_QUOTES);
	$password = htmlentities($_POST['password'], ENT_QUOTES);

	// Retrieve the matching info from the database if it exists
	$sql = "SELECT user_id, user_email, user_pass 
			FROM admins
			WHERE user_email = :email
			LIMIT 1";

	try
	{
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$user = array_shift($results);
		$stmt->closeCursor();
	}
	catch(Exception $e)
	{
		die($e->getMessage());
	}

	// Fails if the email doesn't match a database entry
	if(!isset($user))
	{
		header("Location: ../login.php?Error=" . $error);
	}

	// Check if the hashed password matches the stored hash
	if( password_verify($password, $user['user_pass']) )
	{			
		$_SESSION['user_email'] = $user['user_email'];
		return TRUE;
 	}
	// Fail if the password doesn't match
	else
	{
		header("Location: ../login.php?Error=" . $error);
	}

 }

 /**
  * Log out the user
  *
  * @return TRUE on success or an error message otherwise
 */
 public function processLogout()
 {
 	// Fail id the proper action was not submitted
 	if($_POST['action'] != 'user_logout')
 	{
 		return 'Invalid action supplied for progressLogout.';
 	}

 	// Destroy completely the session
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    $_SESSION['user_email'] = NULL;
 	return TRUE;
 }

}

?>