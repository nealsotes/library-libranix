<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<style>
		#container {
			margin-top: 40px;
		}
	</style>
</head>

<body>
	<div id="container" class="container">
		<a type="button" href="pending_registrations.php" class="btn btn-danger btn-lg btn-block w-50 mx-auto">Pending registrations</a>
		<a type="button" href="pending_book_requests.php" class="btn btn-danger btn-lg btn-block w-50 mx-auto">Pending book requests</a>
		<a type="button" href="insert_book.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Add a new book</a>
		<a type="button" href="update_copies.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Update book copies</a>
		<a type="button" href="update_balance.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Update member balance</a>
		<a type="button" href="due_handler.php" class="btn btn-success btn-lg btn-block w-50 mx-auto">Reminders for today</a>
	</div>
</body>

</html>