<!DOCTYPE HTML>
<?php
	//error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
?>

<html>
<head>
	<title>0s to 1s All Students</title>
</head>

<body>
	<?php
		//gets lastYearCount from students table
		$statement = $db -> prepare("SELECT * FROM students");
		$result = $statement -> execute();
		$names = [];
		$lastYearCount = [];
		$currentYearCount = [];
		while($row = $result->fetchArray(SQLITE3_ASSOC)){
			array_push($names, $row['name']); //set the values into the array
			array_push($lastYearCount, $row['lastYearCount']); //set the values in to the array
		}
	
		//loop through each student 
		$index = 0;
		foreach($names as $name) {
	?>
			<div>
				<?php 
				$last = $lastYearCount[$index];
				//counts number of undeleted coverage records
				$statement = $db -> prepare("SELECT count('name') AS currentYearCount FROM coverages WHERE name = :name AND isDeleted = 0");
				$statement -> bindValue(':name', $name); 
				$result = $statement -> execute();
				while($row = $result->fetchArray(SQLITE3_ASSOC)){
					$current = $row['currentYearCount']; //set the values into the array
				}
				echo "<b>$name:</b> last year: <b>$last</b>, current year: <b>$current</b>"; ?>
			</div>
	<?php
			unset($name);
			unset($last);
			unset($current);
			$index++;
		}
	?>
</body>
</html>