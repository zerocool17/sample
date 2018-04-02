<?php

// Create and include a configuration file with the database connection
include('config.php');

// Get a list of books from the database
$sql = file_get_contents('sql/listbooks.sql');
$statement = $database->prepare($sql);
$statement->execute();
$list_books = $statement->fetchAll(PDO::FETCH_ASSOC);

// Get a list of categories for each book from the database
$sql = file_get_contents('sql/listbookscategory.sql');
$statement = $database->prepare($sql);
$statement->execute();
$books_categories = $statement->fetchAll(PDO::FETCH_ASSOC);
//echo "<pre>"; print_r($books_categories); echo "</pre>";

// Create an associative array storing the categories of each book indexed by isbn
$bookData = array();
foreach ($list_books as $list_book) {
	$bookData[$list_book['isbn']]['title'] = $list_book['title'];
	$bookData[$list_book['isbn']]['categories'] = array();

}
foreach ($books_categories as $bookCategory) {
	$bookData[$bookCategory['isbn']]['categories'][] = $bookCategory['name'];

}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

  	<title>Books And Stuff</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Books</h1>
			<?php foreach ($bookData as $isbn => $book) : ?>
				<div class="book">
					<h2><?php echo $isbn ?> - <?php echo $book['title'] ?></h2>
					<br>
				<p><strong>Categories</strong></p>
				<ul>
						<?php foreach($book['categories'] as $category) : ?>
								<li><?php echo $category ?></li>
							<?php endforeach ?>
							</ul>
					</div>
				<?php endforeach ?>
				<br><br><hr>
	</div>
</body>
</html>
