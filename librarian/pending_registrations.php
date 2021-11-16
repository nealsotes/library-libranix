<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require '../vendor/autoload.php';

?>

<html>

<head>
	<title>Pending Registrations</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
	<?php



	$mail = new PHPMailer(TRUE);

	$query = $con->prepare("SELECT username, firstname, lastname, email, program, balance FROM pendingusers");
	$query->execute();
	$result = $query->get_result();
	$rows = mysqli_num_rows($result);
	if ($rows == 0) {
		echo "<div class='container' style='margin-top: 7	0px'><h2 align='center'>No registrations pending</h2>";
		echo "<div class='cold-md-12 text-center'><button type='submit' class='btn btn-danger'  aria-haspopup='false' onClick='goBack()' aria-expanded='false'> Go back </button> </div></div>";
	} else {
		echo "<div class='container'><form class='cd-form' method='POST' action='#'>";
		echo "<legend>Pending registrations</legend>";
		echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
		echo "<table class='table'>
						<tr>
							<th></th>
							<th>Username</th>
							<th>First name</th>
							<th>Last name</th>
							<th>Email</th>
							<th>Course</th>
							<th>Balance</th>
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
			$j;
			for ($j = 0; $j < 5; $j++)
				echo "<td>" . $row[$j] . "</td>";
			echo "<td>₱" . $row[$j] . "</td>";
			echo "</tr>";
		}
		echo "</table><br /><br />";
		echo "<div style='float: right;'>";
		echo "<input class='btn btn-danger' type='submit' value='Disapproved' name='l_delete' />&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input class='btn btn-success' type='submit' value='Approved' name='l_confirm' />";
		echo "</div>";
		echo "</form></div>";
	}

	$header = 'From: <noreply@library.com>' . "\r\n";

	if (isset($_POST['l_confirm'])) {
		$members = 0;
		for ($i = 0; $i < $rows; $i++) {
			if (isset($_POST['cb_' . $i])) {
				$username =  $_POST['cb_' . $i];
				$query = $con->prepare("SELECT * FROM pendingusers WHERE username = ?;");
				$query->bind_param("s", $username);
				$query->execute();
				$row = mysqli_fetch_array($query->get_result());

				$query = $con->prepare("INSERT INTO memberrecords(username,password, firstname, lastname, email,phone,address,program, balance) VALUES(?, ?, ?, ?, ?,?,?,?,?);");
				$query->bind_param("ssssssssd", $username, $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9]);
				if (!$query->execute())
					die(error_without_field("ERROR: Couldn\'t insert values"));
				$members++;

				$to = $row[5];
				$subject = "Library membership accepted";
				$message = "Your membership has been accepted by the library. You can now issue books using your account.";
				//	mail($to, $subject, $message, $header);

				try {
					$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
					$mail->isSMTP();                                            //Send using SMTP
					$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
					$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
					$mail->Username   = 'nealfordsotes@gmail.com';                     //SMTP username
					$mail->Password   = '09217934673';                               //SMTP password
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
					$mail->Port       = 465;

					$mail->setFrom('from@example.com', 'Mailer');
					$mail->addAddress($to);     //Add a recipient
					$mail->addReplyTo('info@example.com', 'Information');

					//Attachments
					$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
					$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

					//Content
					$mail->isHTML(true);                                  //Set email format to HTML
					$mail->Subject = $subject;
					$mail->Body    = $message;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					$mail->send();
					echo 'Message has been sent';
				} catch (Exception $e) {
					echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}
		}
		if ($members > 0)
			echo success("Successfully added " . $members . " students");
		else
			echo error_without_field("No registration selected");
	}

	if (isset($_POST['l_delete'])) {
		$requests = 0;
		for ($i = 0; $i < $rows; $i++) {
			if (isset($_POST['cb_' . $i])) {
				$username =  $_POST['cb_' . $i];
				$query = $con->prepare("SELECT email FROM pendingusers WHERE username = ?;");
				$query->bind_param("s", $username);
				$query->execute();
				$email = mysqli_fetch_array($query->get_result())[0];

				$query = $con->prepare("DELETE FROM pendingusers WHERE username = ?;");
				$query->bind_param("s", $username);
				if (!$query->execute())
					die(error_without_field("ERROR: Couldn\'t delete values"));
				$requests++;

				$to = $email;
				$subject = "Library membership rejected";
				$message = "Your membership has been rejected by the library. Please contact a librarian for further information.";

				try {
					$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
					$mail->isSMTP();                                            //Send using SMTP
					$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
					$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
					$mail->Username   = 'nealfordsotes@gmail.com';                     //SMTP username
					$mail->Password   = '09217934673';                               //SMTP password
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
					$mail->Port       = 465;

					$mail->setFrom('from@example.com', 'Mailer');
					$mail->addAddress($to);     //Add a recipient
					$mail->addReplyTo('info@example.com', 'Information');

					//Attachments
					//Optional name

					//Content
					$mail->isHTML(true);                                  //Set email format to HTML
					$mail->Subject = $subject;
					$mail->Body    = $message;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					$mail->send();
					echo 'Message has been sent';
				} catch (Exception $e) {
					echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}
		}
		if ($requests > 0)
			echo success("Successfully deleted " . $requests . " requests");
		else
			echo error_without_field("No registration selected");
	}
	?>
	<script src="../components/js/app.js"></script>
</body>

</html>