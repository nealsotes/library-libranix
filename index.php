<?php
require "db_connect.php";
session_start();

if (empty($_SESSION['type']));
else if (strcmp($_SESSION['type'], "librarian") == 0)
	header("Location: librarian/home.php");
else if (strcmp($_SESSION['type'], "member") == 0)
	header("Location: member/home.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Libranix | Welcome</title>
	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
	<!-- Bootstrap icons-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
	<!-- Core theme CSS (includes Bootstrap)-->
	<link href="css/styles.css" rel="stylesheet" />
</head>

<body>
	<!-- Navigation-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container px-4 px-lg-5">
			<a class="navbar-brand" href="#">Libranix</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
					<li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="#">About</a></li>

				</ul>
				<a style="margin-right: 5px;" href="member" class="btn btn-success" type="submit">
					Student
				</a>
				<a href="librarian" class="btn btn-success" type="submit">
					Librarian
				</a>
			</div>
		</div>
	</nav>
	<!-- Product section-->
	<section class="py-5">
		<div class="container px-4 px-lg-5 my-5">
			<div class="row gx-4 gx-lg-5 align-items-center">
				<div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="img/library.jpg" alt="..." /></div>
				<div class="col-md-6">
					<h1 class="display-5 fw-bolder">Libranix Library Management System</h1>
					<div class="fs-5 mb-5">
					</div>
					<p class="lead">Speeding up research disclosure to shape a superior future
						The present examination, the upcoming development</p>

				</div>
			</div>
		</div>
	</section>
	<!-- Related items section-->
	
	<!-- Footer-->
	<?php
	include_once('components/footer.php')
	?>
	<!-- Bootstrap core JS-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Core theme JS-->
	<script src="js/scripts.js"></script>
</body>

</html>