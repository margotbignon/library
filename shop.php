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


?>

<table style='margin-left:3em; width:40%; text-align:center'>
<th>Titre</th>
    <th>Prix</th>
    <th>Auteur<th>
<?php 
    if ($statement->rowCount() > 0) {
        foreach($books as $book) {
?>
    
        <tr>
            <td>
                <?=$book['title']?>
            </td>
            <td>
                <?=$book['price']?>
            </td>
            <td>
                <?=$book['firstname']?> <?=$book['lastname']?>
            </td>
            <td>
                <form action='cart.php' method='post'>
                    <input type='hidden' name='id_books' value='<?=$book['idbook']?>'>
                    <input type='submit' value='Ajouter au panier' style='margin-top:1.2em;'>
                </form>
            </td>
            
        </tr>
    <?php }
    } ?>
</table>