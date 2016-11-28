<!DOCTYPE html>

<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
	$db = new SQLite3('zerosToOnes.sqlite3'); //connect
?>

<html>
<head>
	<title>0s to 1s Students Search</title>
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

	<form action="student.php" method="post">
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
		<br>
		<input type="hidden" name="name" id="studentSearchInput">
		<input type="submit" value=">>">
		<input type="hidden" name="submitCheck" value="1" /> 
	</form>

</body>
</html>