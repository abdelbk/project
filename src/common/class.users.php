<?php
/**
 * @author Abdelkader Benkhadra
*/

Class Users extends DB_Connect {

	public function __construct($dbo)
	{
		parent::__construct($dbo);
	}

	// Display the data in a table
	public function createTable()
	{
		$data = $this->loadData();
?>
			<table>
				<thead>
					<tr>
						<th>Année</th>
						<th>Mois</th>
						<th>Trial Utilisateurs</th>
						<th>Utilisateurs payants</th>
					</tr>
				</thead>
				<tbody>
<?php					
		foreach($data as $users)
		{	
			echo "<tr>";
			for($i=0; $i < sizeof($users); $i++)
			{
				echo "<td>" . $users[$i] . "</td>";
			}
			echo "</tr>";

		}
		
				echo "</tbody>";
			echo"</table>";
	}

	/**
	 * Load data for a specific data
	 *
	 * @param array $date, the given date
	*/ 
	public function loadDataByDate($date)
	{
		$sql = "SELECT nb_trial_user, nb_paid_user FROM users
				WHERE year = :year AND month = :month
				LIMIT 1";

		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':year', $date[0], PDO::PARAM_STR);
			$stmt->bindParam(':month', $date[1], PDO::PARAM_STR);
			$stmt->execute();
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results;
			$stmt->closeCursor();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}

	}

	// Load data
	public function loadData()
	{
		$sql = "SELECT year, month, nb_trial_user, nb_paid_user FROM users";

		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_NUM);
			return $results;
			$stmt->closeCursor();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

}

?>