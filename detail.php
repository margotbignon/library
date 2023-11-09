<?php 
    include "header.php";
    $pdo = connectDB();
    $id = $_GET['identifiant'];
    $query = "SELECT library.b.*, library.c.name, library.a.firstname, library.a.lastname, DATE_FORMAT (date_publication, '%d/%m/%Y') as date_fr FROM library.book b LEFT JOIN library.category_book cb ON library.b.idbook = library.cb.idbook LEFT JOIN library.category c ON library.cb.idcategory = library.c.idcategory LEFT JOIN library.author a ON library.b.idauthor = library.a.idauthor WHERE library.b.idbook = :myId";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':myId', $id, \PDO::PARAM_INT);
    $statement->execute();
    $book = $statement->fetchAll(PDO::FETCH_ASSOC); 
    $bookTitle = $book[0]['title'];
    $bookPrice = $book[0]['price'];
    $bookDatePublication = $book[0]['date_fr'];
    $bookCategory = $book[0]['name'];
    $bookFirstnameAuthor = $book[0]['firstname'];
    $bookLastnameAuthor = $book[0]['lastname'];
    $bookDescription = $book[0]['description_book'];
?>
<h2>Details book <?php echo $bookTitle?></h2>
<table style='border-collapse:collapse ; width:30%;'>
    <tr>
        <td style='border:1px solid white; padding:1em; background-color:#5472ae; color:white;'>Title</td>
        <td style='border:1px solid blue; padding:1em;'><?php echo $bookTitle?></td>
    </tr>
    <tr>
        <td style='border:1px solid white; padding:1em;background-color:#5472ae; color:white;'>Price</td>
        <td style='border:1px solid blue;padding:1em;'><?php echo $bookPrice?> €</td>
    </tr>
    <tr>
        <td style='border:1px solid white; padding:1em;background-color:#5472ae; color:white;'>Publication date</td>
        <td style='border:1px solid blue;padding:1em;'><?php echo $bookDatePublication?></td>
    </tr>
    <tr>
        <td style='border:1px solid white; padding:1em;background-color:#5472ae; color:white;'>Author</td>
        <td style='border:1px solid blue;padding:1em;'><?php echo $bookFirstnameAuthor . " " . $bookLastnameAuthor?></td>
    </tr>

        <?php $query = "SELECT library.c.name FROM library.category c LEFT JOIN library.category_book cb ON c.idcategory = cb.idcategory LEFT JOIN library.book b ON cb.idbook = b.idbook WHERE library.cb.idbook = :myId";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':myId', $id, \PDO::PARAM_INT);
        $statement->execute();
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo "<tr><td style='border:1px solid white; padding:1em;background-color:#5472ae; color:white;'>";
        if (count($categories) > 1) {
            echo "Categories";
        } else {
            echo "Category";
        }
        echo "</td><td style='border:1px solid blue;padding:1em;'>";
        foreach($categories as $categorie) {
            echo $categorie['name'] . "<br/>";
    }
?>
    <tr>
        <td style='border:1px solid white; padding:1em;background-color:#5472ae; color:white;'>Description</td>
        <td style='border:1px solid blue;padding:1em;'><?php echo $bookDescription?></td>
    </tr>
</table>
<a href='index.php'><br/><br/>Back</a>

