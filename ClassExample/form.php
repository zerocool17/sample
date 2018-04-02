<?php

// Create and include a configuration file with the database connection
include('config.php');

// Get an associative array of categories from the database
// Dynamically print the categories as options in the form
$sql = file_get_contents('sql/category.sql');
$statement = $database->prepare($sql);
$statement->execute();
$categories= $statement->fetchAll(PDO::FETCH_ASSOC);
//echo "<pre>"; print_r($categories); echo "</pre>";

$action = $_GET['action'];

// If form submitted
	// if the type of form specified in the URL is add
		// Get variables from the form submitted using $_POST
		// Insert the book into the database
		// Set the categories of the book in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$isbn = $_POST['isbn'];
	$title = $_POST['book-title'];
	$book_categories = $_POST['book-category'];
	$author = $_POST['book-author'];
	$price = $_POST['book-price'];

	//insert book
	$insert_sql = file_get_contents('sql/insert.sql');
	$params = array (
		'isbn' => $isbn,
		'book_title' => $title,
		'book_author' => $author,
		'book_price' => $price
	);

	$insert_statement = $database->prepare($insert_sql);
	$insert_statement->execute($params);

	//insert category
	foreach ($book_categories as $book_categoryid) {
		$insert_sql_book_category = file_get_contents('sql/insert_book_category.sql');
		$params_category = array (
			'isbn' => $isbn,
			'book_category_id' => $book_categoryid
		);

		$insert_category_statement = $database->prepare($insert_sql_book_category);
		$insert_category_statement->execute($params_category);
	}

	// Redirect to book listing page
	header('location: index.php');
	die();
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

  	<title>Add New Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Add New Book</h1>
		<form action="" method="POST">
			<div class="form-element">
				<label>ISBN:</label>
				<input type="text" name="isbn" class="textbox" />
			</div>
			<div class="form-element">
				<label>Title:</label>
				<input type="text" name="book-title" class="textbox" />
			</div>
			<div class="form-element">
				<label>Category:</label>
					<?php foreach($categories as $category) : ?>
						<input class="radio" type="checkbox" name="book-category[]" value=<?php echo $category['categoryid'] ?> /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php endforeach ?>
			</div>
			<div class="form-element">
				<label>Author</label>
				<input type="text" name="book-author" class="textbox" />
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input type="number" step="any" name="book-price" class="textbox" />
			</div>
			<div class="form-element">
				<input type="submit" class="button" />&nbsp;
				<input type="reset" class="button" />
			</div>
		</form>
	</div>
</body>
</html>
