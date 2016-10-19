<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	/*$q = $_GET["term"];
	$statement = $db -> prepare('SELECT name FROM students WHERE name LIKE :q;');
	$statement -> bindValeu(':q', $q);
	$result = $statement -> execute();
	$names = [];
	while($row = $result->fetchArray(SQLITE3_ASSOC)){
		array_push($names, $row['name']); //set the values in to the array
	}
	echo json_encode($names);*/
	$output = "";
	
	//collect
	 
	if(isset($_POST['searchVal'])) {
	 	$searchq = $_POST['searchVal'];
	 	//$searchq = preg_replace("#[^0-9a-z]#i","",$searchq); //Additional Filtering
	 	//echo($searchq);	 
		//echo(isset($db));
	 	$statement = $db -> prepare('SELECT name, lastYearCount FROM students WHERE name LIKE :searchq OR lastName LIKE :searchq OR firstName LIKE :searchq;');
		$statement -> bindValue(':searchq', $searchq);
	 	$result = $statement->execute();
	 	 
		while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $output .= '<div> ' . $row['name'] . ' </div>';
		}	 
	}
	 echo($output);
?>