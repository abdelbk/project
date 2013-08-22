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