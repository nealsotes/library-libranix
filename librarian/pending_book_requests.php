<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Libranix | Pending Book Requests</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link href="../css/styles.css" rel="stylesheet"/>
	<script src="../components/js/app.js"></script>
	
	
</head>

<body>
	<?php
	$query = $con->prepare("SELECT request_id, Concat(memberrecords.firstname,' ',memberrecords.lastname),books.title,dateRequested FROM pendingbookrequests INNER JOIN memberrecords ON pendingbookrequests.member_id = memberrecords.member_id INNER JOIN books ON pendingbookrequests.book_id = books.book_id;");
	$query->execute();
	$result = $query->get_result();
	$rows = mysqli_num_rows($result);
	if ($rows == 0) {
		echo "<div class='container'><h2 align='center' style='margin-top: 70px'>No request pending</h2>";
		echo "<div class='cold-md-12 text-center'><button type='submit' class='btn btn-danger'  aria-haspopup='false' onClick='goBack()' aria-expanded='false'> Go back </button> </div></div>";
	} else {
		echo "<div class='container'><form class='cd-form' method='POST' action='#'>";
		echo "<legend>Pending book requests</legend>";
		echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
		echo "<table class='table'>
						<tr>
							
							<th></th>		
							<th>Name</th>
							<th>Book Title</th>
							<th>Time</th>
						</tr>";
		for ($i = 0; $i < $rows; $i++) {
			$row = mysqli_fetch_array($result);
			echo "<tr>";
			echo "<td>
							<label class='control control--checkbox'>
								<input type='checkbox' name='cb_" . $i . "' value='" . $row[0] . "' />
								<div class='control__indicator'></div>
							</label>
						</td>";
			for ($j = 1; $j < 4; $j++)
				echo "<td>" . $row[$j] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<br /><br /><div style='float: right;'>";
		echo "<input type='submit' class='btn btn-danger' value='Reject selected' name='l_reject' />&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type='submit' class='btn btn-success' value='Grant selected' name='l_grant'/>";
		echo "</div>";
		echo "</form></div>";
	}

	$header = 'From: <noreply@libranix.com>' . "\r\n";

	if (isset($_POST['l_grant'])) {
		$requests = 0;
		for ($i = 0; $i < $rows; $i++) {
			if (isset($_POST['cb_' . $i])) {
				$request_id =  $_POST['cb_' . $i];
			
				$query = $con->prepare("SELECT member_id, book_id FROM pendingbookrequests  WHERE request_id = ?;");
				$query->bind_param("i", $request_id);
				$query->execute();
				$resultRow = mysqli_fetch_array($query->get_result());
				$member = $resultRow[0];
				$isbn = $resultRow[1];
				$query = $con->prepare("INSERT INTO bookissue(member_id, book_id) VALUES(?, ?);");
				$query->bind_param("ss", $member, $isbn);
				if (!$query->execute())
					die(error_without_field("ERROR: Couldn\'t issue book"));
				$requests++;

				$query = $con->prepare("SELECT email FROM memberrecords WHERE member_id = ?;");
				$query->bind_param("s", $member);
				$query->execute();
				$to = mysqli_fetch_array($query->get_result())[0];
				$subject = "Book successfully issued";

				$query = $con->prepare("SELECT title FROM books WHERE book_id = ?;");
				$query->bind_param("s", $isbn);
				$query->execute();
				$title = mysqli_fetch_array($query->get_result())[0];

				$query = $con->prepare("SELECT due_date FROM bookissue WHERE member_id = ? AND book_id = ?;");
				$query->bind_param("ss", $member, $isbn);
				$query->execute();
				$due_date = mysqli_fetch_array($query->get_result())[0];
				$message = "The book '" . $title . "' with ISBN " . $isbn . " has been issued to your account. The due date to return the book is " . $due_date . ".";

				//mail($to, $subject, $message, $header);
			}
		}
		if ($requests > 0)
			echo success("Successfully granted " . $requests . " requests");
		else
			echo error_without_field("No request selected");
	}

	if (isset($_POST['l_reject'])) {
		$requests = 0;
		for ($i = 0; $i < $rows; $i++) {
			if (isset($_POST['cb_' . $i])) {
				$requests++;
				$request_id =  $_POST['cb_' . $i];

				$query = $con->prepare("SELECT memberrecords.username,books.isbn FROM pendingbookrequests INNER JOIN memberrecords ON pendingbookrequests.member_id = memberrecords.member_id INNER JOIN books ON pendingbookrequests.book_id = books.book_id WHERE request_id = ?;");
				$query->bind_param("d", $request_id);
				$query->execute();
				$resultRow = mysqli_fetch_array($query->get_result());
				$member = $resultRow[0];
				$isbn = $resultRow[1];

				$query = $con->prepare("SELECT email FROM memberrecords WHERE username = ?;");
				$query->bind_param("s", $member);
				$query->execute();
				$to = mysqli_fetch_array($query->get_result())[0];
				$subject = "Book issue rejected";

				$query = $con->prepare("SELECT title FROM books WHERE isbn = ?;");
				$query->bind_param("s", $isbn);
				$query->execute();
				$title = mysqli_fetch_array($query->get_result())[0];
				$message = "Your request for issuing the book '" . $title . "' with ISBN " . $isbn . " has been rejected. You can request the book again or visit a librarian for further information.";

				$query = $con->prepare("DELETE FROM pendingbookrequests WHERE request_id = ?");
				$query->bind_param("d", $request_id);
				if (!$query->execute())
					die(error_without_field("ERROR: Couldn\'t delete values"));

				mail($to, $subject, $message, $header);
			}
		}
		if ($requests > 0)
			echo success("Successfully deleted " . $requests . " requests");
		else
			echo error_without_field("No request selected");
	}
