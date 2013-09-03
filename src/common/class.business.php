<?php
/**
 * @author Abdelkader Benkhadra
*/

Class Business extends DB_Connect {

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
						<th>Utilisateurs prospectés</th>
						<th>Nouveaux utilisateurs</th>
						<th>Total</th>
						<th>Catégories de métiers</th>
					</tr>
				</thead>
				<tbody>
<?php					
		foreach($data as $business)
		{	
		echo "<tr>";
			for($i=0; $i < sizeof($business); $i++)
			{
				echo "<td>" . $business[$i] . "</td>";
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
		$sql = "SELECT nb_prospected_users, nb_new_users, nb_total_users, nb_jobcategories FROM business
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
		$sql = "SELECT year, month, nb_prospected_users, nb_new_users, nb_total_users, nb_jobcategories FROM business";

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