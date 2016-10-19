<!DOCTYPE HTML>
<?php

?>

<html>
<head>
	<title>0s to 1s Home</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script> 
		function searchq() {
			var searchTxt = $("input[name='name']").val();
			
			$.post("autocomplete.php", {searchVal: searchTxt}, function(output) {
				$(" #output").html(output);
			
			} );
		
		}
	</script>
</head>

<body>
	<button onclick='window.open("allStudents.php", "_self")'>Show All Students</button>
	<br><br>
	<form method='post' action='student.php'>
		Find a Name: <input type='text' id='name' name='name' onkeyup='searchq()' />
		<input type='submit' id='submit' name='submit' value='>>' />
	</form>
	<div id='output' />
</body>
</html>