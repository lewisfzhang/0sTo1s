<!DOCTYPE HTML>
<?php
	//error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('zerosToOnes.sqlite3'); //connect
	function nameExists($value) { //check if name is in students table
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
		if (isset($_POST["name"])){ //if came from index.php with name
			$name = $_POST["name"];
			if (nameExists($name)) { //if name is in db
				echo "<h1>$name</h1>";
			}
			else {
				echo "$name not found. Please go <a href='index.php'>back</a>.";
			}
		}
		else {
				echo "$name not found. Please go <a href='index.php'>back</a>.";
		}
		if ((isset($_POST["name"]) and nameExists($name)) or isset($_POST['submitCheck']) or isset($_POST['submitCheckDelete'])) {
	?>
	<?php
			//form handlers
			//add form Handler
			if (isset($_POST['submitCheck'])) { //if form was submitted
				unset($_POST['submitCheck']);
				//insert new coverage record
				$date = new DateTime($_POST['date']);
				$dateStr = $date -> format('Y-m-d H:i:s'); //string version of date for SQLite
				//echo $dateStr;
				if(isset($_POST['description']) and $_POST['description'] != ""){ //if a description was entered
					$description = $_POST['description'];
					$statement = $db -> prepare('INSERT INTO coverages (name, description, isDeleted, date) VALUES (:name, :description, 0, :dateStr);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':description', $description);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> execute();
					echo "Coverage Record added for $name on $dateStr: $description";
				}
				else {
					$statement = $db -> prepare('INSERT INTO coverages (name, isDeleted, date) VALUES (:name, 0, :dateStr);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> execute();
					echo "Coverage Record added for $name on $dateStr";
				}
			}
			
			//Delete Form Handler
			if(isset($_POST['submitCheckDelete'])) {
				unset($_POST['submitCheckDelete']);
				$statement = $db -> prepare('UPDATE coverages SET isDeleted = 1 WHERE numID = :numID');
				$statement -> bindValue(':numID', $_POST['numID']);
				$statement -> execute();
			}
	?>
	<!--Number of Times Covered-->
	<h2>Coverage Count</h2>
	Previous Year: <b><?php 
			//gets lastYearCount from students table
			$statement = $db -> prepare("SELECT lastYearCount FROM students WHERE name = :name");
			$statement -> bindValue(':name', $name);
			$result = $statement -> execute();
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				$lastYearCount = $row['lastYearCount']; //set the values in to the array
			}
			echo $lastYearCount;
	?></b>
	Current Year: <b><?php
			//counts number of undeleted coverage records
			$statement = $db -> prepare("SELECT count('name') AS currentYearCount FROM coverages WHERE name = :name AND isDeleted = 0");
			$statement -> bindValue(':name', $name);
			$result = $statement -> execute();
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				$currentYearCount = $row['currentYearCount']; //set the values into the array
			}
			echo $currentYearCount;
	?></b>
	
	<!--Add a coverage record-->
	<h2>Add a Coverage Record</h2>
	<form name='addCoverage' method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<textarea rows='4' cols='50' name='description' id='description'>A short description of the coverage of <?php echo $name; ?></textarea> <br>
		Date: <input type='date' id='date' name='date' required> <br><br>
		<input type='submit'>
		<input type='reset'>
		<input type="hidden" name="name" value="<?php echo $name ?>"/> 
		<input type="hidden" name="submitCheck" value="1"/> 
	</form>
	
	<!--Display coverage records-->
	<h2>Coverage Records</h2>
	<?php
			//get arrays of coverages
			$statement = $db -> prepare("SELECT description, date, numID FROM coverages WHERE name = :name AND isDeleted = 0");
			$statement -> bindValue(':name', $name);
			$result = $statement -> execute();
			$descriptionArray = [];
			$dateArray = [];
			$numIDArray = [];
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				array_push($descriptionArray, $row['description']); //set the values in to the array
				array_push($dateArray, $row['date']); //set the values in to the array
				array_push($numIDArray, $row['numID']); //set the values in to the array
			}
			
			//echo coverages as divs
			$index = 0;
			foreach ($numIDArray as $numID){
				$desc = $descriptionArray[$index];
				$dt = $dateArray[$index];
	?>
			<!--Div for each coverage rec-->
			<div id="coverageRec<?php echo $numID ?>"> <!--id is diff for each coverage rec-->
				<?php echo $index+1 . ".";?> <b>Description:</b> <?php echo $desc ?> <b>Date:</b> <?php echo explode(" ", $dt)[0] //only before space, this takes out time of day ?> 
				<form style='display: inline;' name='deleteCoverage' method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
					<!--<input type='submit' name='delete' value='Delete' />-->
					<input type='hidden' name='numID' value="<?php echo $numID ?>" />
					<input type='hidden' name='submitCheckDelete' value="1" /> 
					<input type="hidden" name="name" value="<?php echo $name ?>"/> 
				</form>
			</div>
			<br>
	<?php
				unset($numID);
				unset($desc);
				unset($dt);
				$index++;
			}
	?>
	
	<!--More HTML, possibly-->
	<a href='index.php'>Back</a>
	<?php
		}
	?>
</body>
</html>