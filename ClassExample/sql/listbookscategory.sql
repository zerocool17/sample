/*Get a list of categories for each book from the database*/
SELECT *
FROM book_categories
JOIN categories ON  categories.categoryid = book_categories.categoryid