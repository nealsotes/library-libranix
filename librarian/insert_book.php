<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
	<title>Add book</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link href="../css/styles.css" rel="stylesheet" />

</head>

<body>
	<div class="container">
		<div id="form-sign" class="row justify-content-center">
			<div class="col-md-6">
				<legend>Enter book details</legend>

				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				<form method="POST" action="#">
					<div class="form-group">
						<input type="number" name="b_isbn" class="form-control" placeholder="Enter ISBN" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_title" class="form-control" placeholder="Enter Title" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_language" class="form-control" placeholder="Enter Language" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_publisher" class="form-control" placeholder="Enter Publisher" required>
					</div>
					<div class="form-group">
						<input type="text" name="b_author" class="form-control" placeholder="Enter Author" required>
					</div>
					


					<label for="pwd">Category</label>
					<div class="form-group">
						<select class="form-select form-select-lg mb-3" name="b_category" aria-label=".form-select-lg example">
							<option selected>Science Fiction</option>
							<option value="Short Stories">Short Stories</option>
							<option value="Suspense and Thrillers">Suspense and Thrillers</option>
							<option value="Biographies and Autobiographies">Biographies and Autobiographies</option>
							<option value="Cookbooks">Cookbooks</option>
							<option value="Literary Fiction">Literary Fiction</option>
							<option value="Romance">Romance</option>
							<option value="Non-fiction">Non-fiction</option>
							<option value="Education">Education</option>
							<option value="Action and Adventure">Action and Adventure</option>
							<option value="Classics<">Classics</option>
							<option value="Comic Book or Graphic Novel">Comic Book or Graphic Novel</option>
							<option value="Detective and Mystery">Detective and Mystery</option>
							<option value="Fantasy">Fantasy</option>
							<option value="Horror">Horror</option>
						</select>
					</div>
					<div class="form-group">
						<input type="number" name="b_price" class="form-control" placeholder="Enter Price" required>
					</div>
					<div class="form-group">
						<input type="number" name="rental_price" class="form-control" placeholder="Enter Rental Price" required>
					</div>
					<div class="form-group">
						<input type="number" name="b_copies" class="form-control" placeholder="Enter Copies" required>
					</div>
					<button type="submit" name="b_add" class="btn btn-primary">Add book</button>
				</form>
			</div>
		</div>
	</div>
	<script></script>
</body>
		<?php
		
		if (isset($_POST['b_add'])) {
			$query = $con->prepare("SELECT isbn FROM books WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();

			if (mysqli_num_rows($query->get_result()) != 0)
				echo error_with_field("A book with that ISBN already exists", "b_isbn");
			else {
				
				
			
				try{
				
					
					$con->begin_transaction();
					
				
					
						$stmt = $con->prepare("INSERT INTO authors(name) VALUES(?);");
						$stmt->bind_param("s",$_POST['b_author']);
						$stmt->execute();
					
					
			
				
						$stmt = $con->prepare("INSERT INTO publishers(name) VALUES(?);");
						$stmt->bind_param("s", $_POST["b_publisher"]);
						$stmt->execute();
					
					
					
					
				
						$stmt = $con->prepare("INSERT INTO categories(name) VALUES(?)");
						$stmt->bind_param("s", $_POST['b_category']);
						$stmt->execute();
					

					

					
					
					$stmt = $con->prepare("INSERT INTO books (author_id,publisher_id,category_id,title,isbn,language,price,rentalPrice,numberOfCopies) VALUES((SELECT author_id FROM authors WHERE name = ?), (SELECT publisher_id FROM publishers WHERE name = ?),(SELECT category_id FROM categories WHERE name = ?), ?, ?, ?, ?, ?,?);");
					$stmt->bind_param("ssssssddd", $_POST['b_author'], $_POST['b_publisher'],$_POST['b_category'], $_POST['b_title'], $_POST['b_isbn'],$_POST['b_language'],$_POST['b_price'],$_POST['rental_price'], $_POST['b_copies']);
					$stmt->execute();
					$con->commit();
				}catch(PDOException $ex){
					$con->rollback();
					echo $ex->getMessage();
				}

				/* if (!$queryBooks->execute() && !$queryAuthor->execute() && !$queryCategory->execute() && !$queryPublisher->execute())
					die(error_without_field("ERROR: Couldn't add book"));
				echo success("Successfully added book"); */
			}
		}
		?>

</html>