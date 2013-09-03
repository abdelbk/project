<?php
/**
 * @author Abdelkader Benkhadra
*/

include_once 'config/init.php';
$class = htmlentities($_GET['class'], ENT_QUOTES);

$obj = new $class($dbo);
$results = $obj->loadData();
$json = array();

// Group by the values of each column
for($i = 0; $i < sizeof($results[0]); $i++)
{
	foreach($results as $result)
	{
		$json[] = $result[$i];
	}
}

echo json_encode(array_chunk($json, 5));

?>
