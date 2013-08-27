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
		$data = $this->_loadData();
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
			echo "<td>" . $business['year'] . "</td>";
			echo "<td>" . $business['month'] . "</td>";
			echo "<td>" . $business['nb_prospected_users'] . "</td>";
			echo "<td>" . $business['nb_new_users'] . "</td>";
			echo "<td>" . $business['nb_total_users'] . "</td>";
			echo "<td>" . $business['nb_jobcategories'] . "</td>";
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

	// Load the data from the database
	private function _loadData()
	{
		$sql = "SELECT * FROM business";

		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll();
			$stmt->closeCursor();
			return $results;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

}

?>