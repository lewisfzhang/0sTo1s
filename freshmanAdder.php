<?php
	/*$db0sTo1s = new SQLite3('zerosToOnes.sqlite3'); //connect
	$dbMaster = new SQLite3('masterStudent16-17.sqlite3'); //connect
	
	$statement = $dbMaster -> prepare('SELECT StudFirstName, StudLastName, BCPStudID FROM master;');
	$result = $statement -> execute();
	$firstNameArray = [];
	$lastNameArray = [];
	$studIdArray = [];
	while($row = $result->fetchArray(SQLITE3_ASSOC)){
		array_push($firstNameArray, $row['StudFirstName']); //set the values in to the array
		array_push($lastNameArray, $row['StudLastName']); //set the values in to the array
		array_push($studIdArray, $row['BCPStudID']); //set the values in to the array
	}
	
	$index = 0;
	foreach ($studIdArray as $studId){
		$studId = $studIdArray[$index];
		if (substr($studId, 0, 3) == '220'){
			$firstName = $firstNameArray[$index];
			$lastName = $lastNameArray[$index];
			$name = "$firstName $lastName";
			$statement2 = $db0sTo1s -> prepare('INSERT INTO students (lastName, firstName, lastYearCount, name) VALUES (:lastName, :firstName, 0, :name);');
			$statement2 -> bindValue(':lastName', $lastName);
			$statement2 -> bindValue(':firstName', $firstName);
			$statement2 -> bindValue(':name', $name);
			$statement2 -> execute();
			echo "$studId: $firstName $lastName <br>";
		}
		$index++;
	}*/
	echo "Not for reuse"
?>