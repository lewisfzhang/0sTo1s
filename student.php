<!DOCTYPE HTML>
<?php
	//error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	function nameExists($value) {
		$db = new SQLite3('zerosToOnes.sqlite3'); //connect
		$statement = $db -> prepare("SELECT * FROM students WHERE name = :value");
		$statement -> bindValue(':value', $value);
		$result = $statement -> execute();
		while($row = $result->fetchArray(SQLITE3_ASSOC)){
			$valueRetrieved = $row['name']; //set the values in to the array
		}
		//echo $valueRetrieved;
		if(isset($valueRetrieved) AND $value === $valueRetrieved) {
			return true;
		} 
		else {
			return false;
		}
	}
?>

<html>
<head>
	<title>0s to 1s Student</title>
</head>

<body>
	<?php
		if (isset($_POST["name"])){
			$name = $_POST["name"];
			if (nameExists($name)) {
				echo "$name";
	?>
	<!--HTML goes here-->
	<?php
			}
			else {
				echo "$name not found. Please go <a href='index.php'>back</a>.";
			}
		}
	?>
</body>
</html>