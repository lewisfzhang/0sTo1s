<!DOCTYPE HTML>
<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
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
	
	<!--JQuery-->
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	
	<!--Bootstrap-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	<!--Select2-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	
	<!--Select2 setup-->
	<script>
		//Apply Select2 to all of class 'tags'
		$(function(){
			$('.tags').select2({
			  tags: true,
			  tokenSeparators: [',', ' ']
			})
		});
	</script>
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
				//$date = new DateTime(getdate());
				$dateStr = date('Y-m-d H:i:s');
				$dateCovered = new DateTime($_POST['date']);
				$dateCoveredStr = $dateCovered -> format('Y-m-d H:i:s');
				$description = $_POST['description'];
				$tags = $_POST['tagInput'];
				$type = $_POST['type'];
				$isIdea = $_POST['isIdea'];
				$user = $_POST['user'];
				
				if((isset($_POST['description']) and $_POST['description'] != "") and !(isset($_POST['tagInput']) and $_POST['tagInput'] != "")){ //if a description was entered but no tag
					$statement = $db -> prepare('INSERT INTO coverages (name, description, isDeleted, date, isIdea, type, dateCompleted, user) VALUES (:name, :description, 0, :dateStr, :isIdea, :type, :dateCompleted, :user);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':description', $description);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> bindValue(':isIdea', $isIdea);
					$statement -> bindValue(':type', $type);
					$statement -> bindValue(':dateCompleted', $dateCoveredStr);
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					error_reporting(E_ERROR | E_PARSE); //change error reporting to not report warnings from SQL executions 
					//add new users
					$statement = $db -> prepare('INSERT INTO users (user) VALUES (:user)');
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					error_reporting(E_ERROR | E_WARNING | E_PARSE); //reset error reporting
					
					echo "Coverage Record added for $name on $dateStr: $description";
				}
				elseif((isset($_POST['description']) and $_POST['description'] != "") and (isset($_POST['tagInput']) and $_POST['tagInput'] != "")){ //if desc and tag
					//add into coverages
					$statement = $db -> prepare('INSERT INTO coverages (name, description, isDeleted, date, isIdea, tags, type, dateCompleted, user) VALUES (:name, :description, 0, :dateStr, :isIdea, :tags, :type, :dateCompleted, :user);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':description', $description);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> bindValue(':isIdea', $isIdea);
					$statement -> bindValue(':tags', $tags);
					$statement -> bindValue(':type', $type);
					$statement -> bindValue(':dateCompleted', $dateCoveredStr);
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					//add new tags
					$tagArray = explode(",", $tags);
					$index = 0;
					error_reporting(E_ERROR | E_PARSE); //change error reporting to not report warnings from SQL executions
					foreach($tagArray as $newTag){
						$newTag = $tagArray[$index];
						$statement2 = $db -> prepare('INSERT INTO tags (tag) VALUES (:tag)');
						$statement2 -> bindValue(':tag', $newTag);
						$statement2 -> execute();
						$index++;
					}
					
					//add new users
					$statement = $db -> prepare('INSERT INTO users (user) VALUES (:user)');
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					error_reporting(E_ERROR | E_WARNING | E_PARSE); //reset error reporting
					
					echo "Coverage Record added for $name on $dateStr: $description With tags: $tags";
				}
				elseif(!(isset($_POST['description']) and $_POST['description'] != "") and (isset($_POST['tagInput']) and $_POST['tagInput'] != "")){ //if no desc and yes tag
					$statement = $db -> prepare('INSERT INTO coverages (name, isDeleted, date, isIdea` tags, type, dateCompleted, user) VALUES (:name, 0, :dateStr, :isIdea, :tags, :type, :dateCompleted, :user);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> bindValue(':isIdea', $isIdea);
					$statement -> bindValue(':tags', $tags);
					$statement -> bindValue(':type', $type);
					$statement -> bindValue(':dateCompleted', $dateCoveredStr);
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					//add new tags
					$tagArray = explode(",", $tags);
					$index = 0;
					error_reporting(E_ERROR | E_PARSE); //change error reporting to not report warnings from SQL executions
					foreach($tagArray as $newTag){
						$newTag = $tagArray[$index];
						$statement2 = $db -> prepare('INSERT INTO tags (tag)  VALUES (:tag)');
						$statement2 -> bindValue(':tag', $newTag);
						$statement2 -> execute();
						$index++;
					}
					 
					//add new users
					$statement = $db -> prepare('INSERT INTO users (user) VALUES (:user)');
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					error_reporting(E_ERROR | E_WARNING | E_PARSE); //reset error reporting
					
					echo "Coverage Record added for $name on $dateStr with tags: $tags";
				}
				else { //if neither desc nor tag
					$statement = $db -> prepare('INSERT INTO coverages (name, isDeleted, date, isIdea, tags, type, dateCompleted, user) VALUES (:name, 0, :dateStr, :isIdea, :tags, :type, :dateCompleted, :user);');
					$statement -> bindValue(':name', $name);
					$statement -> bindValue(':dateStr', $dateStr);
					$statement -> bindValue(':isIdea', $isIdea);
					$statement -> bindValue(':tags', $tags);
					$statement -> bindValue(':type', $type);
					$statement -> bindValue(':dateCompleted', $dateCoveredStr);
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					
					error_reporting(E_ERROR | E_PARSE); //change error reporting to not report warnings from SQL executions 
					//add new users
					$statement = $db -> prepare('INSERT INTO users (user) VALUES (:user)');
					$statement -> bindValue(':user', $user);
					$statement -> execute();
					error_reporting(E_ERROR | E_WARNING | E_PARSE); //reset error reporting
					
					echo "Coverage Record added for $name on $dateStr";
				}
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
	?></b> <br>
	Current Year Ideas: <b><?php
			//counts number of undeleted coverage records
			$statement = $db -> prepare("SELECT count('name') AS currentYearCount FROM coverages WHERE name = :name AND isDeleted = 0 AND isIdea = 1");
			$statement -> bindValue(':name', $name);
			$result = $statement -> execute();
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				$currentYearCount = $row['currentYearCount']; //set the values into the array
			}
			echo $currentYearCount;
	?></b> <br>
	Current Year Completed Coverages: <b><?php
			//counts number of undeleted coverage records
			$statement = $db -> prepare("SELECT count('name') AS currentYearCount FROM coverages WHERE name = :name AND isDeleted = 0 AND isIdea = 0");
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
		<label for="user">
			Your Name
		</label>
		<input type="text" name="user" id="user" required>
		<br><br>
		<textarea rows='4' cols='50' name='description' id='description' placeholder="A short description of the coverage of <?php echo $name; ?>"></textarea>
		<br><br>
		<label for="coverageTags">
			Tags
			
			<!--Tag selector that allows use of previously used tags or creating new ones, places selected tags into hidden input for use in php-->
			<select class="tags" id="coverageTags" multiple="multiple" onchange="document.getElementById('tagInput').value = $('#coverageTags').val();">
				<?php
					//echo an <option> element for each possible tag in the tags table
					$statement = $db -> prepare('SELECT * FROM tags');
					$result = $statement -> execute();
					while($row = $result->fetchArray(SQLITE3_ASSOC)){
						$tag = $row['tag']; //set the values in to the array
						echo "<option value='$tag'>$tag</option>";
					}
				?>
			</select>
		</label>
		<br><br>
		<label for="isIdeaRadios">
			Coverage Status
		</label>
		<div id="isIdeaRadios">
			<input type='radio' name='isIdea' id="isIdea1" value='1' required> Will be Covered <br>
			<input type='radio' name="isIdea" id="isIdea0" value="0"> Already Covered
		</div>
		<label for="typeRadios">
			Coverage Type
		</label>
		<div id="typeRadios">	
			<input type="radio" name="type" value="Copy/Alt Copy" required> Copy/Alt Copy <br>
			<input type="radio" name="type" value="Mod"> Mod <br>
			<input type="radio" name="type" value="Caption">  Caption (photo) <br>
			<input type="radio" name="type" value="Talking Head"> Talking Head <br>
			<input type="radio" name="type" value="Other"> Other
		</div>
		<label for="date">
			Date Covered (if applicable) 
		</label>
		<input type='date' id='date' name='date'>
		<br><br>
		<input type='submit'>
		<input type='reset'>
		<input type="hidden" id="tagInput" name="tagInput">
		<input type="hidden" name="name" value="<?php echo $name ?>"> 
		<input type="hidden" name="submitCheck" value="1" /> 
	</form>
	
	<!--Display coverage records-->
	<h2>Coverage Records</h2>
	<?php
			//get arrays of coverages
			$statement = $db -> prepare("SELECT description, date, numID, tags, isIdea, type, dateCompleted, user FROM coverages WHERE name = :name AND isDeleted = 0");
			$statement -> bindValue(':name', $name);
			$result = $statement -> execute();
			$descriptionArray = [];
			$dateArray = [];
			$dateCompletedArray = [];
			$numIDArray = [];
			$recordTagArray = [];
			$ideaArray = [];
			$typeArray = [];
			$userArray = [];
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				array_push($descriptionArray, $row['description']); //set the values in to the array
				array_push($dateArray, $row['date']); //set the values in to the array
				array_push($dateCompletedArray, $row['dateCompleted']); //set the values in to the array
				array_push($numIDArray, $row['numID']); //set the values in to the array
				array_push($recordTagArray, $row['tags']); //set the values in to the array
				array_push($ideaArray, $row['isIdea']); //set the values in to the array
				array_push($typeArray, $row['type']); //set the values in to the array
				array_push($userArray, $row['user']); //set the values in to the array
			}
			
			//echo coverages as divs
			$index = 0;
			foreach ($numIDArray as $numID){
				$desc = $descriptionArray[$index];
				$isIdea = $ideaArray[$index];
				$dt = ($isIdea == 1 ? $dateArray[$index] : $dateCompletedArray[$index]);
				$recTag = $recordTagArray[$index];
				$recType = $typeArray[$index];
				$user = $userArray[$index];
	?>
			<!--Div for each coverage rec-->
			<div id="coverageRec<?php echo $numID ?>"> <!--id is diff for each coverage rec-->
				<?php echo $index+1 . ".";?> <b>Description:</b> <?php echo $desc; ?> 
				<?php if($isIdea == 1){ ?><b>Date Created:</b> <?php } else{ ?> <b>Date Covered:</b> <?php } echo explode("-", explode(" ", $dt)[0])[1] . "-" . explode("-", explode(" ", $dt)[0])[2] . "-" . explode("-", explode(" ", $dt)[0])[0]; //only before space, this takes out time of day ?>
				<b>Tags:</b> <?php echo str_replace(",", ", ",$recTag); ?> <b>by:</b> <?php echo $user; ?> <b>Type:</b> <?php echo $recType;?> (<?php if($isIdea == 1){ ?><b>Will be covered</b><?php } else{ ?><b>Already covered</b><?php } ?>)
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