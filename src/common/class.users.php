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
		$data = $this->_loadData();
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
			echo "<td>" . $users['year'] . "</td>";
			echo "<td>" . $users['month'] . "</td>";
			echo "<td>" . $users['nb_trial_user'] . "</td>";
			echo "<td>" . $users['nb_paid_user'] . "</td>";
			echo "</tr>";

		}
		
				echo "</tbody>";
			echo"</table>";
	}

	// Load the data from the database
	private function _loadData()
	{
		$sql = "SELECT * FROM users";

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