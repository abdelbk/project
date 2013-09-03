<?php

include_once '/../config/init.php';
include_once 'class.db_manager.php';

// Specify the folder where you want to store the json files
$manager = new DB_Manager('your/folder/path/', $dbo);

// Create the json files
$manager->setJsonFiles(2013, 4);

// Get data from the files and update the database
$manager->processFiles();

// Get the messages
$messages = $manager->getMessages();

// Display the messages
echo "<ul>";
foreach ($messages as $message) {
	echo "<li>" . $message . "</li>";
}
echo "</ul>";

?>