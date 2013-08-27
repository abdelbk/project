<?php
/**
 * @author Abdelkader Benkhadra
*/

Class Sites extends DB_Connect {

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
						<th>Nombre de pages</th>
						<th>Nombre de visites</th>
					</tr>
				</thead>
				<tbody>
<?php					
		foreach($data as $sites)
		{	
			echo "<tr>";
			echo "<td>" . $sites['year'] . "</td>";
			echo "<td>" . $sites['month'] . "</td>";
			echo "<td>" . $sites['nb_pages'] . "</td>";
			echo "<td>" . $sites['nb_visitors'] . "</td>";
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
		$sql = "SELECT nb_pages, nb_visitors FROM sites
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
		$sql = "SELECT * FROM sites";

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