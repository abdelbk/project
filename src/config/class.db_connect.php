<?php
/**
 * @author Abdelkader Benkhadra
*/ 
class DB_Connect 
{
/**
* Stores a database object 
*
* @var object, a database object
*/
protected $db;

/**
* Checks if a database object exists, creates one otherwise
*
* @param object, a database object
*/
	protected function __construct($dbo = NULL)
	{
		if( is_object($dbo) )
		{
			$this->db = $dbo;
		}
		else
		{
			// Constants are defined in db_cred.php
			$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
			try
			{
				$this->db = new PDO($dsn, DB_USER, DB_PASS);
			}
			catch (Exception $e)
			{
				// Gets the error message if the connexion fails
				die( $e->getMessage() );
			}
		}	
	}
}

?>