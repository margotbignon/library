<?php 
    include "header.php";
    $pdo=connectDB();
    
    if (!empty($_POST)) {
    $name_category = $_POST['name_category'];
    $query="INSERT INTO library.category (name) VALUES (:name_category)";
    $statement=$pdo->prepare($query); 
    $statement->bindValue(':name_category', $name_category, \PDO::PARAM_STR);
    $statement->execute();
    if ($_GET['ref'] === 'add') {
    header('Location:add.php');
    }
    if ($_GET['ref'] === 'modify') {
        header('Location:edit.php/?identifiant='.$_GET['id']);
        }
}
?>

<h2>Add a category</h2>
<form method="post" style="margin-left:3em">
    <strong>Name</strong>
    <input type="text" name="name_category">
    <input type="submit" value="Enregistrer">
</form>

<?php 
    if (($_GET['ref'] === 'add')) {
        echo "<a href='add.php'>Back</a>";
    }
    if ($_GET['ref'] === 'modify') {
        echo "<a href='edit.php/?identifiant=".$_GET['id']."'>Back</a>";
        }
?>
