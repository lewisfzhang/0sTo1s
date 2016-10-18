<!DOCTYPE html>
<html>
<head>
	<title>Prepare DB</title>
</head>
<body>
<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	for ($i = 1; $i < 1918; $i++){ //for each row
		//get lastName, which includes a pg num
		$statement = $db -> prepare('SELECT lastName FROM students WHERE numID = :i;');
		$statement -> bindValue(':i', $i);
		$result = $statement -> execute();
		while($row = $result -> fetchArray(SQLITE3_ASSOC)){
            $lastName = $row['lastName']; 
        }
		$lastName = explode(" ", $lastName)[0]; //only take part of last name before space
		//echo "$lastName <br>";
		
		//update
		$statement = $db -> prepare('UPDATE students SET lastName = :lastName WHERE numID = :i');
		$statement -> bindValue(':i', $i);
		$statement -> bindValue(':lastName', $lastName);
		$statement -> execute();
		
		//count non-null values
		$statement = $db -> prepare('SELECT 
		((CASE WHEN pgNum2 IS NULL THEN 0 ELSE 1 END)
		+(CASE WHEN pgNum3 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum4 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum5 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum6 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum7 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum8 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum9 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum10 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum11 IS NULL THEN 0 ELSE 1 END)
		+ (CASE WHEN pgNum12 IS NULL THEN 0 ELSE 1 END)			
		+ (CASE WHEN pgNum13 IS NULL THEN 0 ELSE 1 END)) AS count
		FROM students
		WHERE numID = :i;');
		$statement -> bindValue(':i', $i);
		$result = $statement -> execute();
		while($row = $result -> fetchArray(SQLITE3_ASSOC)){
            $count = $row['count']; 
        }
		
		//update
		$statement = $db -> prepare('UPDATE students SET lastYearCount = :count WHERE numID = :i');
		$statement -> bindValue(':i', $i);
		$statement -> bindValue(':count', $count);
		$statement -> execute();
		//echo "$count <br>";
	}
?>
</body>
</html>