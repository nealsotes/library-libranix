<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php";
?>

<html>

<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link href="../css/styles.css" rel="stylesheet" />


</head>

<body>
	<?php
	$query = $con->prepare("SELECT isbn,books.title,CONCAT(authors.firstname,' ', authors.lastname),  categories.name,price,numberOfCopies  FROM books INNER JOIN authors ON books.author_id = authors.author_id INNER JOIN categories ON books.category_id = categories.category_id ");
	$query->execute();
	$result = $query->get_result();
	$rows = mysqli_num_rows($result);
	if ($rows == 0)
		echo "<h2 align='center'>No books available</h2>";
	else {
		echo "<div class='container'><form class='cd-form' method='POST' action='#'>";
		echo "<legend>Available books</legend>";
		echo "<div class='error-message' id='error-message'>
						<div id='error'></div>
						
					</div>";
		echo "<table class='table'";
		echo "<tr>
						<th></th>
						<th scope='col'>ISBN</th>
						<th scope='col'>Title</th>
						<th scope='col'>Author</th>
						<th scope='col'>Category</th>
						<th scope='col'>Price</th>
						<th scope='col'>Copies available</th>
					</tr>";
		for ($i = 0; $i < $rows; $i++) {
			$row = mysqli_fetch_array($result);
			echo "<tr>
							<td>
								<label class='control control--radio'>
									<input type='radio' name='rd_book' value=" . $row[0] . " />
								<div class='control__indicator'></div>
							</td>";
			for ($j = 0; $j < 6; $j++)
				if ($j == 4)
					echo "<td>₱" . $row[$j] . "</td>";
				else
					echo "<td>" . $row[$j] . "</td>";
			echo "</tr>";
		}
		echo "</table> </div>";
		echo "<button type='submit' style='margin-right: 70px; margin-bottom: 100px' class='btn btn-danger float-right'  aria-haspopup='false' name='m_request' aria-expanded='false'> Request Book </button> ";

		echo "</form></div>";
	}


	if (isset($_POST['m_request'])) {
		if (empty($_POST['rd_book']))
			echo error_without_field("Please select a book to issue");
		else {
			$query = $con->prepare("SELECT numberOfCopies FROM books WHERE isbn = ?;");
			$query->bind_param("s", $_POST['rd_book']);
			$query->execute();
			$copies = mysqli_fetch_array($query->get_result())[0];
			if ($copies == 0)
				echo error_without_field("No copies of the selected book are available");
			else {
				$query = $con->prepare("SELECT request_id, memberrecords.username FROM pendingbookrequests INNER JOIN memberrecords ON  memberrecords.username = ?;");
				$query->bind_param("s", $_SESSION['username']);
				$query->execute();
				if (mysqli_num_rows($query->get_result()) == 1)
					echo error_without_field("You can only request one book at a time");
				else {
					$query = $con->prepare("SELECT book_id FROM bookissue WHERE member_id = ?;");
					$query->bind_param("s", $_SESSION['username']);
					$query->execute();
					$result = $query->get_result();
					if (mysqli_num_rows($result) >= 3)
						echo error_without_field("You cannot issue more than 3 books at a time");
					else {
						$rows = mysqli_num_rows($result);
						for ($i = 0; $i < $rows; $i++)
							if (strcmp(mysqli_fetch_array($result)[0], $_POST['rd_book']) == 0)
								break;
						if ($i < $rows)
							echo error_without_field("You have already issued a copy of this book");
						else {
							$query = $con->prepare("SELECT balance FROM memberrecords WHERE username = ?;");
							$query->bind_param("s", $_SESSION['username']);
							$query->execute();
							$memberBalance = mysqli_fetch_array($query->get_result())[0];

							$query = $con->prepare("SELECT price FROM books WHERE isbn = ?;");
							$query->bind_param("s", $_POST['rd_book']);
							$query->execute();
							$bookPrice = mysqli_fetch_array($query->get_result())[0];
							if ($memberBalance < $bookPrice)
								echo error_without_field("You do not have sufficient balance to issue this book");
							else {
								$query = $con->prepare("INSERT INTO pendingbookrequests(member_id, book_id)  VALUES(?,?);");
								//get member id from records then pass it to the pending book requests
								$getMemberIdquery = $con->prepare("SELECT member_id FROM memberrecords where username = ?;");
								$getMemberIdquery->bind_param("s", $_SESSION["username"]);
								$getMemberIdquery->execute();
								$member_id = mysqli_fetch_array($getMemberIdquery->get_result())[0];
								//get book id from book then pass it to the pending book requests
								$getBookIdquery = $con->prepare("Select book_id FROM books WHERE isbn = ?;");
								$getBookIdquery->bind_param('s',$_POST['rd_book']);
								$getBookIdquery->execute();
								$book_id = mysqli_fetch_array($getBookIdquery->get_result())[0];
								$query->bind_param("ss", $member_id, $book_id);
								 
								if (!$query->execute())
									echo error_without_field("ERROR: Couldn\'t request book");
								else
									echo success("Book successfully requested. You will be notified by email when the book is issued to your account");
							}
						}
					}
				}
			}
		}
	}
	
	?>
</body>

</html>	