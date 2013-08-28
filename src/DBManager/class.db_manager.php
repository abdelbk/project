<?php
/**
 * @author Abdelkader Benkhadra
 *
 * This class processes all needed json files and updates
 * the database with new data if it exists
*/

Class DB_Manager extends DB_Connect {
	/**
	 * Sets the folder which contains the json files to be processed
	 *
	 * @var String $_folder, the path of the folder
	*/ 
	protected $_folder;

	/**
	 * Stores the json files to be processed
	 *
	 * @var array $_files, an array of files
	*/
	protected $_files = array();

	/**
	 * Stores all the messages to be displayed
	 *
	 * @var array $_messages, the messages
	*/
	protected $_messages = array(); 

	/**
	 * Stores or creates a database object
	 * 
	 * @param String $path, the path of the folder
	 * @param object $db, a database object
	*/ 
	public function __construct($path, $db = NULL)
	{
		if(!is_dir($path) || !is_readable($path))
		{
			throw new Exception("$path needs to be a valid readable folder");
		}
		$this->_folder = $path;
		parent::__construct($dbo);
	}

	// This is the main function that performs all the data insertions
	public function processFiles()
	{
		$files = $this->getJsonFiles();
		foreach($files as $file)
		{
			$fields = array();
			$content = $this->readFile($this->_folder . $file);
			$date = $this->getJsonDate($file);
			
			// Insert data if the file is being processed for the first time
			if($this->checkDate($date))
			{
				foreach($content['users'] as $users)
				{
					$fields[] = $users;
				}
				
				// Insert data in the users table
				$sql = "INSERT INTO users(year, month, nb_trial_user, nb_paid_user)
					VALUES($date[0], $date[1], $fields[0], $fields[1])";

				try
				{
					$stmt = $this->db->prepare($sql);
					$stmt->execute();
					$stmt->closeCursor();
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}

				// Free the fields array for the next data insertion
				$fields = NULL;

				foreach($content['business'] as $business)
				{
					$fields[] = $business;

				}
				
				// Insert data in the business table
				$sql = "INSERT INTO business(year, month, nb_prospected_users, nb_new_users, nb_total_users, nb_jobcategories)
					VALUES($date[0], $date[1], $fields[0], $fields[1], $fields[2], $fields[3])";

				try
				{
					$stmt = $this->db->prepare($sql);
					$stmt->execute();
					$stmt->closeCursor();
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}

				$fields = NULL;

				foreach($content['sites'] as $visits)
				{
					$fields[] = $visits;
				}

				// Insert data in the sites table
				$sql = "INSERT INTO sites(year, month, nb_pages, nb_visitors)
					VALUES($date[0], $date[1], $fields[0], $fields[1])";

				try
				{
					$stmt = $this->db->prepare($sql);
					$stmt->execute();
					$stmt->closeCursor();
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}
			}
			// Update database if the file's data changed
			else
			{
				// Update the users table
				$this->checkForUpdates('users', 'Users', $content, $date);
				// Update the sites table
				$this->checkForUpdates('sites', 'Sites', $content, $date);
				// Update the business table
				$this->checkForUpdates('business', 'Business', $content, $date);
			}
		}
		echo '<strong>database updated !!</strong';
	}

	/**
	 * Create the json files from their urls
	 *
	 * @param int $year, the the year from which we want to start checking for urls
	 * @param int $month , the month from which we want to start checking for urls
	*/
	public function setJsonFiles($year, $month)
	{
		for($y = $year;; $y++)
		{
			for($m = $month; $m < 13; $m++)
			{
				$m = str_pad($m, 2, '0', STR_PAD_LEFT);
				$url = 'http://data-json.simplebo.fr/'.$y.$m.'/json';
				if($this->urlExists($url))
				{
					$file = $y.$m.'.json';
					$this->writeContent($url, $file);
					if(filesize($file) > 0)
					{
						$this->_messages[] = $file . " has been created<br />";
					}
				}
				else
				{
					break 2;
				}

				if($m == 12)
				{
					$month = 1;
				}
			}
		}
	}

	/**
	 * Check for updates for a given table
	 *
	 * @param String $table, the tablename that we want to check updates for
	 * @param String $obj, the classname from wich we want to get the existent data
	 * @param array $content, the content of the file with which we want to compare the old data to
	 * @param array $date, the date of the file  
	 */

	private function checkForUpdates($table, $obj, $content, $date)
	{
		$new_data = array();
		$object = new $obj($dbo);
		$old_data = $object->loadDataByDate($date);
		$new_data = $content[$table];
		$updates = array_diff_assoc($new_data, $old_data);
		if(!empty($updates))
		{
			foreach($updates as $key => $value)
			{
				$sql = "UPDATE $table SET $key = '$value' WHERE year = $date[0] AND month = $date[1]";
				try
				{
					$stmt = $this->db->prepare($sql);
					$stmt->execute();
					$stmt->closeCursor();
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}
			}
		}
	}

	// Get all the json files
	private function getJsonFiles()
	{
		$handle = opendir($this->_folder);
		while(false !== ($entry = readdir($handle)))
		{
			if($this->checkFile($entry))
			{
				$this->_files[] = $entry;
			}
		}
		return $this->_files;
		closedir($handle);
	}

	/**
	 * Check if the file is an actual json file
	 * And that the filename respects the convention which is : 
	 * A string with 6 digits : 4 for the year and 2 for the month
	 *
	 * @param String $file, the file name
	*/
	private function checkFile($file)
	{
		$extension = 'json';
		$pattern = '/^\d{6}+$/';
		$f = pathinfo($file);
		preg_match($pattern, $f['filename'], $matches);
		if($f['extension'] !== $extension || empty($matches))
		{
			return false;
		}
		return true;
	}

	/**
	 * Extract the year and month from the filename
	 *
	 * @param String $file, the file
	*/
	private function getJsonDate($file)
	{
		$f = pathinfo($file);
		$date = explode('/', substr(chunk_split($f['filename'], 4, '/'), 0, -1));
		return $date;
	}

	/**
	 * Check if the file had been already processed
	 * 
	 * @param array $date, contains the year and month of the file
	 */
	private function checkDate($date)
	{
		$sql = "SELECT users_id FROM users
			WHERE year = :year AND month = :month
			LIMIT 1";

		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':year', $date[0], PDO::PARAM_STR);
			$stmt->bindParam(':month', $date[1], PDO::PARAM_STR);
			$stmt->execute();
			$results = $stmt->fetchAll();
			$id = array_shift($results);
			$stmt->closeCursor();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}

		// Fail if the year and month match an entry that already exists
		if(isset($id))
		{
			return false;
		}

		return true;
	}

	/**
	 * Read the given file
	 *
	 * @param String $file, the file path
	*/
	private function readFile($file)
	{
		$handle = fopen($file, "r");
		$content = fread($handle, filesize($file));
		$result = (json_decode($content, true));
		return $result;
		fclose($handle);

	}

	/**
	 * Check if the url exists
	 *
	 * @param String $url, the url
	*/
	private function urlExists($url) 
	{
    		$ch = @curl_init($url);
    		@curl_setopt($ch, CURLOPT_HEADER, TRUE);
    		@curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		$status = array();
    		preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
    		if($status[1] == '200')
    		{
    			return true;
    		}
    		return false;
	}

	/**
	 * Write the content of the url to a file
	 *
	 * @param String $url, the url from which we want to get the content
	 * @param String $file, the filename that will hold the content
	*/
	private function writeContent($url, $file)
	{
		$ch = @curl_init($url);
    		$fp = fopen($this->_folder . $file, "w");
    		@curl_setopt ($ch, CURLOPT_URL, $url);
    		@curl_setopt($ch, CURLOPT_FILE, $fp);
    		@curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
    		$content = @curl_exec($ch);
    		fwrite($fp, $content);
    		@curl_close($ch);
		fclose($fp);
	}

}
