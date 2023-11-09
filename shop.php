<?php 
include "function.php";

session_start();
if (isset($_SESSION['name'])) {
    echo "Bienvenue " . $_SESSION['name'] . " !<br/> <a href='logout.php'>Logout</a><br/><br/>";
} else {
    echo "<a href='login.php'>Login</a> <br/><br/><br/>";
}

echo "<a href='cart.php'>Mon panier</a><br/><br/>";


$pdo = connect_bd();
$query = "SELECT library.b.*, library.a.firstname, library.a.lastname  FROM library.book b LEFT JOIN library.author a ON library.b.idauthor = library.a.idauthor";
$statement = $pdo->query($query);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($statement->rowCount() > 0) {
    foreach($books as $book) {
        echo "Titre : " . $book['title'] . "<br/>Prix : " . $book['price'] . " €<br/>Auteur : " . $book['lastname'] . " " . $book['firstname'] .  
    "<form action='cart.php' method='post'><input type='hidden' name='id_books' value='" . $book['idbook'] . "'>" . "<a href='cart.php'><input type='submit' value='Ajouter au panier'></a></form>" . "<br/><br>";
     
    }
} else {
    echo "0 results";
}

?>