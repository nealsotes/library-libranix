<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Add book</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container">
		<div id="form-sign" class="row justify-content-center">
			<div class="col-md-6">
				<legend>Enter book details</legend>

				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				<form method="POST" action="#">
					<div class="form-group">
						<input type="number" name="b_isbn" class="form-control" placeholder="Enter ISBN" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_title" class="form-control" placeholder="Enter Title" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_author" class="form-control" placeholder="Enter Author" required>
					</div>


					<label for="pwd">Category</label>
					<div class="form-group">
						<select class="form-select form-select-lg mb-3" name="b_category" aria-label=".form-select-lg example">
							<option selected>Fiction</option>
							<option>Non-fiction</option>
							<option>Education</option>
						</select>
					</div>
					<div class="form-group">
						<input type="number" name="b_price" class="form-control" placeholder="Enter Price" required>
					</div>
					<div class="form-group">
						<input type="number" name="b_copies" class="form-control" placeholder="Enter Copies" required>
					</div>
					<button type="submit" name="b_add" class="btn btn-primary">Add book</button>
				</form>
			</div>
		</div>
	</div>

	<body>

		<?php
		if (isset($_POST['b_add'])) {
			$query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();

			if (mysqli_num_rows($query->get_result()) != 0)
				echo error_with_field("A book with that ISBN already exists", "b_isbn");
			else {
				$query = $con->prepare("INSERT INTO book VALUES(?, ?, ?, ?, ?, ?);");
				$query->bind_param("ssssdd", $_POST['b_isbn'], $_POST['b_title'], $_POST['b_author'], $_POST['b_category'], $_POST['b_price'], $_POST['b_copies']);

				if (!$query->execute())
					die(error_without_field("ERROR: Couldn't add book"));
				echo success("Successfully added book");
			}
		}
		?>

</html>