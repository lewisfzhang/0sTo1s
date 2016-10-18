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
				echo "<h1>$name</h1>";
			}
		}
		else {
				echo "$name not found. Please go <a href='index.php'>back</a>.";
		}
		if (isset($_POST["name"]) or isset($_POST['submitCheck'])) {
	?>
	<!--Add a coverage record-->
	<h3>Add a Coverage Record</h3>
	<form name='addCoverage' method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<textarea rows='4' cols='50' name='description' id='description'>A short description of the coverage of <?php echo $name; ?></textarea> <br>
		Date: <input type='date' id='date' name='date' required> <br>
		<input type="hidden" name="name" value="<?php echo $name ?>"/> 
		<input type="hidden" name="submitCheck" value="1"/> 
		<input type='submit'>
		<input type='reset'>
	</form>
	<?php
			if (isset($_POST['submitCheck'])) {
				echo "meow";
				$date = $_POST['date'];
				//date('Y-m-d', $date);
				echo $date;
			}
	?>
	<!--More HTML-->
	<?php
		}
	?>
</body>
</html>