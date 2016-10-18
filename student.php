<!DOCTYPE HTML>
<?php
	If(isset($_POST["name"])){
?>

<html>
<head>
	<title>0s to 1s</title>
</head>

<body>
	<form method='post' action='student.php'>
		Name: <input type='text' id='name' name='name' />
		<br>
		<input type='submit' id='submit' name='Submit' />
	</form>
</body>
</html>