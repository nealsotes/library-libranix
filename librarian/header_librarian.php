<html>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container px-4 px-lg-5">
			<a class="navbar-brand" href="../index.php">Libranix</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
		</div>

		<div style="margin-right: 100px;" class="btn-group">
			<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<?php echo "Librarian:" . " " . $_SESSION['username'] ?>
			</button>
			<div class="dropdown-menu">
				<div class="dropdown-divider"></div>
				<div style="width: 10rem;">
					<ul class="list-group list-group-flush">
						<li class="list-group-item"><a href="../logout.php">Logout</a></li>
					</ul>

				</div>
			</div>
		</div>
	</nav>
</body>

</html>