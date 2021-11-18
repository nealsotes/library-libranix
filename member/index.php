<?php
require "../db_connect.php";
require "../message_display.php";
require "../verify_logged_out.php";
require "../components/header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Libranix | Member Home</title>
	<link href="../css/form_styles.css" rel="stylesheet" />

</head>

<body>

	<div class="container">

		<div id="form-sign" class="row justify-content-center">

			<div class="col-md-6">
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				<div class="card">
					<header class="card-header">
						<h4 class="card-title mt-2">Member Login</h4>
					</header>
					<article class="card-body">
						<form method="POST" action="#" onsubmit="return do_login();">
							<div class="form-row">
								<div class="col form-group">
									<label>Username</label>
									<input type="text" name="m_user" id="username" class="form-control" placeholder="uclm-idnumber">
								</div> <!-- form-group end.// -->
								<div class="col form-group">
									<label>Password</label>
									<input type="password" name="m_pass" id="password" class="form-control" placeholder=" ">
								</div>
							</div>
							<div class="form-group">
								<button style="margin-top: 10px;" type="submit" value="Login" name="m_login" class="btn btn-success"> Login </button>
							</div>
						</form>
						<p align="center">Don't have an account?&nbsp;  <a href="registerStudent.php">Sign up now.</a></p>

					</article>
				</div>
			</div>

		</div>


	</div>

</body>

</html>
<?php
if (isset($_POST['m_login'])) {
	$query = $con->prepare("SELECT member_id, balance FROM memberrecords WHERE username = ? AND password = ?;");
	$query->bind_param("ss", $username, $password);
	$username = $_POST["m_user"];
	$password = sha1($_POST['m_pass']);
	$query->execute();
	$result = $query->get_result();

	if (mysqli_num_rows($result) != 1)
		echo error_without_field("Invalid username/password combination");
	else {
		$resultRow = mysqli_fetch_array($result);
		$balance = $resultRow[1];
		if ($balance < 0)
			echo error_without_field("Your account has been suspended. Please contact a librarian for further information");
		else {
			$_SESSION['type'] = "member";
			$_SESSION['id'] = $resultRow[0];
			$_SESSION['username'] = $_POST['m_user'];
			header('Location: home.php');
		}
	}
}
?>