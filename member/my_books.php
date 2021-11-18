<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php";
?>

<html>

<head>
	<title>Member | My books</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link href="../css/styles.css" rel="stylesheet" />
</head>

<body>

	<?php
	$query = $con->prepare("SELECT  books.isbn FROM bookissue INNER JOIN memberrecords ON bookissue.member_id = memberrecords.member_id INNER JOIN books ON bookissue.book_id = books.book_id WHERE memberrecords.username = ?;");
	$query->bind_param("s", $_SESSION['username']);
	$query->execute();
	$result = $query->get_result();
	$rows = mysqli_num_rows($result);
	if ($rows == 0) {
		echo "<div class='container'><h2 align='center' style='margin-top: 70px'>No books currently issued</h2>";
		echo "<div class='cold-md-12 text-center'><button type='submit' class='btn btn-danger'  aria-haspopup='false' onClick='goBack()' aria-expanded='false'> Go back </button> </div></div>";
	} else {
		echo "<div class='container'><form class='cd-form' method='POST' action='#'>";

		echo "<legend>My books</legend>";
		echo "<div class='success-message' id='success-message'>
						<p id='success'></p>
					</div>";
		echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
		echo "<table class='table'>
						<tr>
							<th></th>
							<th>ISBN</th>
							<th>Title</th>
							<th>Author</th>
							<th>Category</th>
							<th>Due Date</th>
						</tr>";
		for ($i = 0; $i < $rows; $i++) {
			$isbn = mysqli_fetch_array($result)[0];
			if ($isbn != NULL) {
				$query = $con->prepare("SELECT title,authors.name,categories.name  FROM books INNER JOIN authors INNER JOIN categories WHERE isbn = ?;");
				$query->bind_param("s", $isbn);
				$query->execute();
				$innerRow = mysqli_fetch_array($query->get_result());
				echo "<tr>
								<td>
									<label class='control control--checkbox'>
										<input type='checkbox' name='cb_book" . $i . "' value='" . $isbn . "'>
										<div class='control__indicator'></div>
									</label>
								</td>";
				echo "<td>" . $isbn . "</td>";
				for ($j = 0; $j < 3; $j++)
					echo "<td>" . $innerRow[$j] . "</td>";
				$query = $con->prepare("SELECT due_date FROM bookissue INNER JOIN memberrecords ON bookissue.member_id = memberrecords.member_id  INNER JOIN books ON bookissue.book_id = books.book_id WHERE memberrecords.username = ? AND books.isbn = ?;");
				$query->bind_param("ss", $_SESSION['username'], $isbn);
				
				$query->execute();
				echo "<td>" . mysqli_fetch_array($query->get_result())[0] . "</td>";
				echo "</tr>";
			}
		}
		echo "</table><br />";
		echo "<button type='submit' id='button-user style='margin-right: 100px; margin-bottom: 100px' class='btn btn-danger float-right'  aria-haspopup='false' name='b_return' aria-expanded='false'> Return Book </button> ";
		echo "</form></div>";
	}
	
	if (isset($_POST['b_return'])) {
		$books = 0;
		for ($i = 0; $i < $rows; $i++)
			if (isset($_POST['cb_book' . $i])) {
				$query = $con->prepare("SELECT due_date FROM bookissue INNER JOIN memberrecords ON bookissue.member_id = memberrecords.member_id INNER JOIN books ON bookissue.book_id = books.book_id WHERE memberrecords.username = ? AND books.isbn = ?;");
				$query->bind_param("ss", $_SESSION['username'], $_POST['cb_book' . $i]);
				$query->execute();
				$due_date = mysqli_fetch_array($query->get_result())[0];

				$query = $con->prepare("SELECT DATEDIFF(CURRENT_DATE, ?);");
				$query->bind_param("s", $due_date);
				$query->execute();
				$days = (int)mysqli_fetch_array($query->get_result())[0];

				$query = $con->prepare("DELETE FROM bookissue WHERE member_id = ?;");
				$query->bind_param("i", $_SESSION['id']);
				if (!$query->execute())
					die(error_without_field("ERROR: Couldn\'t return the books"));

				if ($days > 0) {
					$penalty = 5 * $days;
					$query = $con->prepare("SELECT price FROM books WHERE isbn = ?;");
					$query->bind_param("s", $_POST['cb_book' . $i]);
					$query->execute();
					$price = mysqli_fetch_array($query->get_result())[0];
					if ($price < $penalty)
						$penalty = $price;
					$query = $con->prepare("UPDATE memberrecords SET balance = balance - ? WHERE username = ?;");
					$query->bind_param("ds", $penalty, $_SESSION['username']);
					$query->execute();
					echo '<script>
									document.getElementById("error").innerHTML += "A penalty of ₱ ' . $penalty . ' was charged for keeping book ' . $_POST['cb_book' . $i] . ' for ' . $days . ' days after the due date.<br />";
									document.getElementById("error-message").style.display = "block";
								</scrip>';
				}
				$books++;
			}
		if ($books > 0) {
				echo success("Successfully returned " . $books . " requests"); 
				
			$query = $con->prepare("SELECT balance FROM memberrecords WHERE username = ?;");
			$query->bind_param("s", $_SESSION['username']);
			$query->execute();

			$balance = (int)mysqli_fetch_array($query->get_result())[0];
			if ($balance < 0)
				header("Location: ../logout.php");
		} else
			echo error_without_field("Please select a book to return");
	}
	?>
	<script src="../components/js/app.js"></script>
</body>

</html>