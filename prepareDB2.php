<!DOCTYPE html>
<html>
<head>
	<title>Prepare DB 2</title>
</head>
<body>
<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	for ($i = 1; $i < 1918; $i++){ //for each row
		//get lastName and firstName
		$statement = $db -> prepare('SELECT lastName, firstName FROM students WHERE numID = :i;');
		$statement -> bindValue(':i', $i);
		$result = $statement -> execute();
		while($row = $result -> fetchArray(SQLITE3_ASSOC)){
            $lastName = $row['lastName'];
			$firstName = $row['firstName'];
        }
		$name = $lastName . ' ' . $firstName; //put names first and last together
		echo "$name <br>";
		
		//update
		$statement = $db -> prepare('UPDATE students SET name = :name WHERE numID = :i');
		$statement -> bindValue(':i', $i);
		$statement -> bindValue(':name', $name);
		$statement -> execute();
	}
?>
</body>
</html>