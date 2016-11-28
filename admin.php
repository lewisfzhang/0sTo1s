<!DOCTYPE html>

<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
	$db = new SQLite3('zerosToOnes.sqlite3'); //connect
?>

<html>
<head>
	<title>0s to 1s Admin</title>
	
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
			$('.search').select2()
		});
	</script>
</head>

<body>
	<?php
		if (!isset($_POST['submitCheckStudent']) && !isset($_POST['submitCheckTag']) && !isset($_POST['submitCheckUser'])) { //if form not was submitted
	?>
	<form name='searchByStudent' method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<label for="studentSearch">
			Search by Student
			
			<select class="search" id="studentSearch" onchange="document.getElementById('studentSearchInput').value = $('#studentSearch').val();">
				<?php
					//echo an <option> element for each possible tag in the tags table
					$statement = $db -> prepare('SELECT name FROM students');
					$result = $statement -> execute();
					while($row = $result->fetchArray(SQLITE3_ASSOC)){
						$name = $row['name']; //set the values in to the array
						echo "<option value='$name'>$name</option>";
					}
				?>
			</select>
		</label>
		<input type="hidden" name="studentSearchInput" id="studentSearchInput">
		<input type="submit" value=">>">
		<input type="hidden" name="submitCheckStudent" value="1" /> 
	</form>
	
	<form name='searchByTag' method='post' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onkeypress="return event.keyCode != 13;">
		<label for="tagSearch">
				Search by Tag
				
				<!--Tag selector that allows use of previously used tags or creating new ones, places selected tags into hidden input for use in php-->
				<select class="search" id="tagSearch" onchange="document.getElementById('tagSearchInput').value = $('#tagSearch').val();">
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
		<input type="hidden" name="tagSearchInput" id="tagSearchInput">
		<input type="submit" value=">>">
		<input type="hidden" name="submitCheckTag" value="1" /> 
	</form>
	
	<!--
	<form name='searchByStaffer' method='post' action="<?php //echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onkeypress="return event.keyCode != 13;">
		<label for="stafferSearch">
			Search by Staffer
			
			<!--Tag selector that allows use of previously used tags or creating new ones, places selected tags into hidden input for use in php--
			<select class="search" id="stafferSearch" onchange="document.getElementById('stafferSearchInput').value = $('#stafferSearch').val();">
				<?php
					//echo an <option> element for each possible tag in the tags table
					/*$statement = $db -> prepare('SELECT * FROM users');
					$result = $statement -> execute();
					while($row = $result->fetchArray(SQLITE3_ASSOC)){
						$user = $row['user']; //set the values in to the array
						echo "<option value='$user'>$user</option>";
					}*/
				?>
			</select>
		</label>
		<input type="hidden" name="stafferSearchInput" id="tagSearchInput">
		<input type="submit" value=">>">
		<input type="hidden" name="submitCheckStaffer" value="1" /> 
	</form>
	-->
	
	<?php
		}
		elseif(isset($_POST['submitCheckStudent'])) { //if form was submitted
			unset($_POST['submitCheckStudent']);
			$name = $_POST['studentSearchInput'];
	?>
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
			<a href="admin.php">Back to search</a>
	<?php
		}
		elseif(isset($_POST['submitCheckTag'])){
			unset($_POST['submitCheckTag']);
			$tag = $_POST['tagSearchInput'];
	?>
			<h2>Coverage Records with Tag: <?php echo $tag; ?></h2>
	<?php
			$statement = $db -> prepare("SELECT name, description, date, numID, isIdea, type, dateCompleted, user FROM coverages WHERE tags LIKE :tag AND isDeleted = 0");
			$statement -> bindValue(':tag', $tag);
			$result = $statement -> execute();
			$nameArray = [];
			$descriptionArray = [];
			$dateArray = [];
			$dateCompletedArray = [];
			$numIDArray = [];
			$ideaArray = [];
			$typeArray = [];
			$userArray = [];
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				array_push($nameArray, $row['name']); //set the values in to the array
				array_push($descriptionArray, $row['description']); //set the values in to the array
				array_push($dateArray, $row['date']); //set the values in to the array
				array_push($dateCompletedArray, $row['dateCompleted']); //set the values in to the array
				array_push($numIDArray, $row['numID']); //set the values in to the array
				array_push($ideaArray, $row['isIdea']); //set the values in to the array
				array_push($typeArray, $row['type']); //set the values in to the array
				array_push($userArray, $row['user']); //set the values in to the array
			}
			
			//echo coverages as divs
			$index = 0;
			foreach ($numIDArray as $numID){
				$name = $nameArray[$index];
				$desc = $descriptionArray[$index];
				$isIdea = $ideaArray[$index];
				$dt = ($isIdea == 1 ? $dateArray[$index] : $dateCompletedArray[$index]);
				$recType = $typeArray[$index];
				$user = $userArray[$index];
	?>
			<!--Div for each coverage rec-->
			<div id="coverageRec<?php echo $numID ?>"> <!--id is diff for each coverage rec-->
				<?php echo $index+1 . ".";?> <b>Name:</b> <?php echo $name; ?> <b>Description:</b> <?php echo $desc; ?> 
				<?php if($isIdea == 1){ ?><b>Date Created:</b> <?php } else{ ?> <b>Date Covered:</b> <?php } echo explode("-", explode(" ", $dt)[0])[1] . "-" . explode("-", explode(" ", $dt)[0])[2] . "-" . explode("-", explode(" ", $dt)[0])[0]; //only before space, this takes out time of day ?> <b>by:</b> <?php echo $user; ?> <b>Type:</b> <?php echo $recType;?> (<?php if($isIdea == 1){ ?><b>Will be covered</b><?php } else{ ?><b>Already covered</b><?php } ?>)
			</div>
			<br>
	<?php
				unset($numID);
				unset($desc);
				unset($dt);
				$index++;
			}
	?>
	<a href="admin.php">Back to search</a>
	<?php
		}
		elseif(isset($_POST['submitCheckStaffer'])){
			unset($_POST['submitCheckStaffer']);
			$user = $_POST['stafferSearchInput'];
	?>
			<h2>Coverage Records by: <?php echo $user; ?></h2>
	<?php
			$statement = $db -> prepare("SELECT name, description, date, numID, isIdea, type, dateCompleted, tags FROM coverages WHERE user = :user AND isDeleted = 0");
			$statement -> bindValue(':user', $user);
			$result = $statement -> execute();
			$nameArray = [];
			$descriptionArray = [];
			$tagArray = [];
			$dateArray = [];
			$dateCompletedArray = [];
			$numIDArray = [];
			$ideaArray = [];
			$typeArray = [];
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				array_push($nameArray, $row['name']); //set the values in to the array
				array_push($descriptionArray, $row['description']); //set the values in to the array
				array_push($dateArray, $row['date']); //set the values in to the array
				array_push($dateCompletedArray, $row['dateCompleted']); //set the values in to the array
				array_push($numIDArray, $row['numID']); //set the values in to the array
				array_push($ideaArray, $row['isIdea']); //set the values in to the array
				array_push($typeArray, $row['type']); //set the values in to the array
				array_push($tagArray, $row['tags']); //set the values in to the array
			}
			
			//echo coverages as divs
			$index = 0;
			foreach ($numIDArray as $numID){
				$name = $nameArray[$index];
				$desc = $descriptionArray[$index];
				$isIdea = $ideaArray[$index];
				$dt = ($isIdea == 1 ? $dateArray[$index] : $dateCompletedArray[$index]);
				$recType = $typeArray[$index];
				$recTag = $tagArray[$index];
	?>
			<!--Div for each coverage rec-->
			<div id="coverageRec<?php echo $numID ?>"> <!--id is diff for each coverage rec-->
				<?php echo $index+1 . ".";?> <b>Name:</b> <?php echo $name; ?> <b>Description:</b> <?php echo $desc; ?> 
				<?php if($isIdea == 1){ ?><b>Date Created:</b> <?php } else{ ?> <b>Date Covered:</b> <?php } echo explode("-", explode(" ", $dt)[0])[1] . "-" . explode("-", explode(" ", $dt)[0])[2] . "-" . explode("-", explode(" ", $dt)[0])[0]; //only before space, this takes out time of day ?> <b>Tags:</b> <?php echo str_replace(",", ", ",$recTag); ?> <b>Type:</b> <?php echo $recType;?> (<?php if($isIdea == 1){ ?><b>Will be covered</b><?php } else{ ?><b>Already covered</b><?php } ?>)
			</div>
			<br>
	<?php
				unset($numID);
				unset($desc);
				unset($dt);
				$index++;
			}
	?>
	<a href="admin.php">Back to search</a>
	<?php
		}
	?>
</body>
</html>