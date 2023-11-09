<?php 
include "header.php";
$pdo=connect_bd();
/*$query = "SELECT * FROM library.book";
$statement = $pdo->query($query);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);
var_dump($books);

foreach($books as $book) {
    echo $book['title'] . "<br/>";
}*/

foreach (show('library.book') as $book) {
    echo $book['title'] . "<br/>";
}
echo "<br/>";
foreach (show('library.author') as $author) {
    echo $author['firstname']." ".$author['lastname']."<br/>";
}

$query = "SELECT * FROM library.book WHERE id=:myId";
$statement = $pdo->prepare($query);
$statement->bindValue(':myId', $id, \PDO::PARAM_INT);
$statement->execute();
$book = $statement->fetch(PDO::FETCH_ASSOC);

/*$query = "SELECT * FROM library.author";
$statement = $pdo->query($query);
$authors = $statement->fetchAll(PDO::FETCH_ASSOC);
var_dump($authors);

foreach($authors as $author) {
    echo $author['firstname']."<br/>";
}

$query = "SELECT * FROM library.category";
$statement = $pdo->query($query);
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
var_dump($categories);

foreach($categories as $category) {
    echo $category['name']."<br/>";
}

include "footer.php";*/

var_dump($_GET);
echo "<a href=connectpdo.php/?id=3>Cliquez ici</a>";