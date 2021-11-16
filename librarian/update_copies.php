<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Update copies</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
	
	<div class="container">
		<div id="form-sign" class="row justify-content-center">
			<div class="col-md-6">
				<legend>Enter details</legend>

				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				<form method="POST" action="#">
					<div class="form-group">
						<input type="number" name="b_isbn" class="form-control" placeholder="Enter ISBN" required>
					</div>
					<div class="form-group">
						<input type="number" name="b_copies" class="form-control" placeholder="Enter Copies" required>
					</div>
					<button type="submit" name="b_add" class="btn btn-primary">Add Copies</button>
				</form>
			</div>
		</div>
	</div>

</body>

<?php
if (isset($_POST['b_add'])) {
	$query = $con->prepare("SELECT isbn FROM books WHERE isbn = ?;");
	$query->bind_param("s", $_POST['b_isbn']);
	$query->execute();
	if (mysqli_num_rows($query->get_result()) != 1)
		echo error_with_field("Invalid ISBN", "b_isbn");
	else {
		$query = $con->prepare("UPDATE books SET numberOfCopies = numberOfCopies + ? WHERE isbn = ?;");
		$query->bind_param("ds", $_POST['b_copies'], $_POST['b_isbn']);
		if (!$query->execute())
			die(error_without_field("ERROR: Couldn\'t add copies"));
		echo success("Copies successfully updated");
	}
}
?>

</html>