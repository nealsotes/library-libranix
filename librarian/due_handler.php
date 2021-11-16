<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Reminders for today</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>

<body>

	<?php
	$query = "CALL generate_due_list();";
	$result = mysqli_query($con, $query);
	$rows = mysqli_num_rows($result);

	if ($rows > 0) {
		$successfulEmails = 0;
		$idArray;
		$header = 'From: <noreply@library.com>' . "\r\n";
		$subject = "Return your book today";
		$query = "";

		for ($i = 0; $i < $rows; $i++) {
			$row = mysqli_fetch_array($result);
			$to = $row[1];
			$message = "This is a reminder to return the book '" . $row[3] . "' with ISBN " . $row[2] . " to the library.";
			if (mail($to, $subject, $message, $header) != FALSE) {
				$idArray[$i] = $row[0];
				$successfulEmails++;
			}
		}

		mysqli_next_result($con);

		for ($i = 0; $i < $rows; $i++) {
			$query = $con->prepare("UPDATE book_issue_log SET last_reminded = CURRENT_DATE WHERE issue_id = ?;");
			$query->bind_param("d", $idArray[$i]);
			$query->execute();
			$query->get_result();
		}

		if ($successfulEmails > 0)
			echo "<h2 align='center'>Successfully notified " . $successfulEmails . " members</h2>";
		else
			echo "ERROR: Couldn't notify any member.";
	} else
		echo "<div class='container'><h2 align='center'>No reminders pending</h2>";
	echo "<div class='cold-md-12 text-center'><button type='submit' class='btn btn-danger'  aria-haspopup='false' onClick='goBack()' aria-expanded='false'> Go back </button> </div></div>";

	?>
	<script src="../components/js/app.js"></script>
</body>

</html>