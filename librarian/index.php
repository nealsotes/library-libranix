<?php
require "../db_connect.php";
require "../message_display.php";
require "../verify_logged_out.php";
require "../components/header.php";
?>

<html>

<head>
	<title>Librarian Login</title>
</head>

<body>
	<div class="error-message" id="error-message">
		<p id="error"></p>
	</div>
	<div class="container">
		<div id="form-sign" class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<header class="card-header">
						<h4 class="card-title mt-2">Librarian Login</h4>
					</header>
					<article class="card-body">
						<form method="POST" action="#">
							<div class="form-row">
								<div class="col form-group">
									<label>Username</label>
									<input type="text" name="l_user" class="form-control" placeholder="">
								</div> <!-- form-group end.// -->
								<div class="col form-group">
									<label>Password</label>
									<input type="password" name="l_pass" class="form-control" placeholder=" ">
								</div>
							</div>
							<div class="form-group">
								<button style="margin-top: 10px;" type="submit" value="Login" name="l_login" class="btn btn-success"> Login </button>
							</div>
						</form>
					</article>
				</div>
			</div>
		</div>

</body>

<?php
if (isset($_POST['l_login'])) {
	$query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
	$query->bind_param("ss", $username, $password);
	$username =  $_POST['l_user'];
	$password = sha1($_POST['l_pass']);
	$query->execute();
	$result = $query->get_result();
	if (mysqli_num_rows($result) != 1)
		echo error_without_field("Invalid username/password combination");
	else {
		$_SESSION['type'] = "librarian";
		$_SESSION['id'] = mysqli_fetch_array($result)[0];
		$_SESSION['username'] = $_POST['l_user'];
		header('Location: home.php');
	}
}
?>

</html>