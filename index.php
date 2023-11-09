<?php 
include "header.php";
$pdo=connect_bd();
?>
<p style='margin-left:7em; margin-bottom:-2em; font-weight:bold;'><a href='shop.php'>Shop</a></p><br/><br/>
<h2>Books list</h2>
<p style='margin-left:7em; margin-bottom:-2em; font-weight:bold;'><a href='add.php'>Add a book</a></p><br/><br/>
<p style='margin-left:7em; font-weight:bold;'>Search a book</p>
<form method='post' style='margin-left:7em ;'>
    <input type="text" name="search" id="search"><input type="submit" value="Search">
</form>

<table style='border-collapse: collapse; width:60% ; margin-left:7em; margin-top:3em';>
    <tr>
        <th style='background-color:#5472ae; color:white; padding:1em;'>Title</th>
        <th style='background-color:#5472ae; color:white; padding:1em;'>Author</th>
        <th style='background-color:#5472ae; color:white;'>Actions</th></tr>

            <?php 
            if (!empty($_POST['search'])) {
            $search = $_POST['search'];   
            $statement=$pdo->prepare("SELECT library.b.*, library.a.firstname, library.a.lastname  FROM library.book b LEFT JOIN library.author a ON library.b.idauthor = library.a.idauthor WHERE b.title LIKE :search OR a.firstname LIKE :search OR a.lastname LIKE :search");
            $statement->bindValue(':search', '%'.$search.'%', PDO::PARAM_STR);
            $statement->execute();
            echo "<p style='margin-left:7em;'><a href='index.php'>Afficher la liste complète des livres</a>";
                if($statement->rowcount() == 0) {
                    echo "Aucun livre ne correspond à la recherche.";
                }
            
            } else {
            $query = "SELECT library.b.*, library.a.firstname, library.a.lastname  FROM library.book b LEFT JOIN library.author a ON library.b.idauthor = library.a.idauthor";
            $statement = $pdo->query($query);
            }
        $book = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach($book as $bookproduct) { ?>
            <tr>
                <td style='text-align:center; border:1px solid blue;'><?php echo $bookproduct['title']?></td>
                <td style='text-align:center; border:1px solid blue;'><?php echo $bookproduct['firstname']. " ". $bookproduct['lastname']?></td>
                <td style='text-align:center; border:1px solid blue; padding:1em;'>
                <a href='detail.php?identifiant=<?php echo $bookproduct['idbook']?>'>Detail</a><br/>
                <a href='edit.php?identifiant=<?php echo $bookproduct['idbook']?>'>Modify</a><br/>
                <a href='delete.php?identifiant=<?php echo $bookproduct['idbook']?>'>Delete</a><td>
            </tr>
        <?php } ?>

</table>