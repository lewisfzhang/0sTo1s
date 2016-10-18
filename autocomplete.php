<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	$q = $_GET["term"];
	$statement = $db -> prepare('SELECT name FROM students WHERE name LIKE :q;');
	$statement -> bindValeu(':q', $q);
	$result = $statement -> execute();
	$names = [];
	while($row = $result->fetchArray(SQLITE3_ASSOC)){
		array_push($names, $row['name']); //set the values in to the array
	}
	echo json_encode($names);
?>