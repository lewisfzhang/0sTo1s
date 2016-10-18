<!DOCTYPE HTML>
<?php

?>

<html>
<head>
	<title>0s to 1s</title>
	<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
	<!--<script>
		/*$(function() {
			var available = <?php //include("autocomplete.php"); ?>
			$( "#name" ).autocomplete({
			  source: available
			});
		});*
		/*$(function() {
            $( "#name" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: "autocomplete.php",
                        dataType: "jsonp",
                        data: {
                            q: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
            });
        });*/  
	</script>-->
</head>

<body>
	<form method='post' action='student.php'>
		Name: <input type='text' id='name' name='name' />
		<br>
		<input type='submit' id='submit' name='Submit' />
	</form>
</body>
</html>