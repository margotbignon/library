<?php
session_start(); 
include "function.php";
if (!isset($_SESSION['name'])) {
    header('Location: login.php') ;
   
    die;

} else {
    echo "Bienvenue " . $_SESSION['name'] . " ! <br/> <a href='logout.php'>Logout</a><br/><br/><br/>";
}


if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = ['id_books' => []];
}

if (!empty($_POST['id_books'])) {
array_push($_SESSION['panier']['id_books'], $_POST['id_books']);
}

if (!empty($_GET)) {
    if (!empty($_GET['action']) && ($_GET['action'] == "delete")) {
       
        unset($_SESSION['panier']['id_books'][$_GET['id']]);
    
    }   
}

$_SESSION['panier']['id_books'] = array_values($_SESSION['panier']['id_books']);
$pdo = connectDB();
$query = "SELECT * FROM library.book ";
$statement = $pdo->query($query);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);


?>

    

<?php
    if (count($_SESSION['panier']['id_books']) < 1) {
        echo "Your cart is empty<br/><br/>";
    } else {
?>
<table style='margin-left:3em; width:40%; text-align:center'>
<th>Title</th>
    <th>Price</th>
<?php 
    if ($statement->rowCount() > 0) {
        for ($i = 0; $i < count($_SESSION['panier']['id_books']); $i++) {
                
            foreach ($books as $book) {
                if ($book['idbook'] == $_SESSION['panier']['id_books'][$i]){
?>
 <tr>
            <td>
                <?=$book['title']?>
            </td>
            <td>
                <?=$book['price']?>
            </td>
            
            <td>
            <a href='cart.php?action=delete&id=<?=$i?>'>Remove</a>
            </td>
            
        </tr>
    <?php
                }
            } 
        }
    }
}
    ?>
</table>
<a href='shop.php'>Back</a>
