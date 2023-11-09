<?php 
    include "header.php";
    $id=$_GET['identifiant'];
    $pdo = connect_bd();
    if (!empty($_POST)) {
        if ((strlen($_POST['title']) > 45) || (strlen($_POST['newauthorfirstname']) > 45) || (strlen($_POST['newauthorlastname']) > 45) || (empty($_POST['category']))  ){
            if (strlen($_POST['title']) > 45) {
            echo "<br/><p style='color:red'><strong>Ne pas dépasser 45 caractères pour le titre - Veuillez le ressaisir</strong></p>";
            }
            if (strlen($_POST['newauthorfirstname']) > 45) {
                echo "<br/><p style='color:red'><strong>Ne pas dépasser 45 caractères pour le prénom de l'auteur - Veuillez le ressaisir</strong></p>";
            }
            if (strlen($_POST['newauthorlastname']) > 45) {
                echo "<br/><p style='color:red'><strong>Ne pas dépasser 45 caractères pour le nom de l'auteur - Veuillez le ressaisir</strong></p>";
            }
            if (empty($_POST['category'])) {
                echo "<br/><p style='color:red'><strong>Veuillez choisir au moins une catégorie.</strong></p>";
            }
        } else {
            $title = $_POST["title"];
            $price = $_POST['price'];
            $date_publication = $_POST['date_publication'];
            $idauthor = $_POST['author'];
            $description_book = $_POST['description_book'];
            $newAuthorfirstname = ($_POST['newauthorfirstname']);
            $newAuthorlastname = ($_POST['newauthorlastname']);
            if ($_POST['author'] != 0) {
            $query = "UPDATE library.book SET title = :title, price = :price, 
            date_publication = :date_publication, idauthor = :idauthor, description_book = :description_book 
            WHERE idbook = :editId";
            $statement = $pdo->prepare($query);
            $statement->bindValue(':editId', $id, PDO::PARAM_INT);
            $statement->bindValue(':title', $title, PDO::PARAM_STR);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':date_publication', $date_publication, PDO::PARAM_STR);
            $statement->bindValue(':idauthor', $idauthor, PDO::PARAM_INT);
            $statement->bindValue(':description_book', $description_book, PDO::PARAM_STR);
            $statement->execute();
            } else {
                $query = "INSERT INTO library.author (firstname, lastname) VALUES (:firstname, :lastname)";
                $statement = $pdo->prepare($query);
                $statement->bindValue(':firstname', $newAuthorfirstname, PDO::PARAM_STR);
                $statement->bindValue(':lastname', $newAuthorlastname, PDO::PARAM_STR);
                $statement->execute();
                $id_new = $pdo->lastInsertId();
                $query = "UPDATE library.book SET idauthor = ". $id_new. " WHERE idbook = :editId ";
                $statement = $pdo->prepare($query);
                $statement->bindValue(':editId', $id);
                $statement->execute();
            }
        if (isset($_POST['category'])) {   
            deleteRow('library.category_book', 'idbook', $id);
            $updateCategories = $_POST['category'];
            $i = 0;
            foreach ($updateCategories as $updateCategory) {
            $statement = $pdo->prepare("INSERT INTO library.category_book (idbook, idcategory) VALUES (:editId, '".$updateCategories[$i]."')");
            $statement->bindValue(':editId', $id, PDO::PARAM_INT);
            $statement->execute();
            $i++;
            }
        }
      
    } 
}
$query = "SELECT library.b.*, library.a.firstname, library.a.lastname  FROM library.book b LEFT JOIN library.author a ON library.b.idauthor = library.a.idauthor WHERE idbook = :editId";
$statement = $pdo->prepare($query);
$statement->bindValue(':editId', $id, PDO::PARAM_INT);
$statement->execute();
$bookEdit = $statement->fetchAll(PDO::FETCH_ASSOC);?>

<h2>Modification du livre <?php echo $bookEdit[0]['title'] ?> </h2>
</br>
<form method='post' style='margin-left:3em';>
    <strong>Titre</strong>
    <br/>
    <input type='text' name='title' value="<?php echo $bookEdit[0]['title'] ?>" required>
    <br/><br/>
    <?php
    $idEdit = $_GET['identifiant'];
    $statement = $pdo->query("SELECT * FROM library.author");
    $authors = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement = $pdo->prepare("SELECT a.idauthor FROM library.author a LEFT JOIN library.book b ON a.idauthor=b.idauthor WHERE b.idbook = :idEdit");
    $statement->bindValue(':idEdit', $idEdit, PDO::PARAM_INT);
    $statement->execute();
    $checkAuthors = $statement->fetchAll(PDO::FETCH_ASSOC);?>
    <table>
        <tr>
            <th style='text-align:left;'><strong>Auteur</strong></th>
            <th style='text-align:left;'>Prénom Nouvel Auteur</th>
            <th style='text-align:left;'>Nom Nouvel Auteur</th></tr>
        <tr>
            <td>
                <select id='author' name='author'>
                    <option value=''>Choisissez votre auteur</option>
    <?php $i=0;
    foreach ($authors as $author) {
        if ($author['idauthor'] === $checkAuthors[$i]['idauthor']) {
        echo "<option value='" . $author['idauthor'] . "' selected = 'yes'>" . $author['firstname'] . " " . $author['lastname'] . "</option>";
        while ($i < (count($checkAuthors) - 1)) {
            $i++;
        }
        } else {
         echo "<option value='" . $author['idauthor'] . "'>" . $author['firstname'] . " " . $author['lastname'] . "</option>";
                }
            } ?>
                    <option value='0'> -- Nouvel auteur --  </option>
                </select>
            </td>
            <td><input type='text' name='newauthorfirstname'></td>
            <td><input type='text' name='newauthorlastname'></td>
            </tr>
        </table>
    <br/>
    <strong>Prix</strong>
    <br/>
    <input type='number' step="0.01" name='price' value='<?php echo $bookEdit[0]['price']?>' required>
    <br/><br/>
    <strong>Date de publication</strong>
    <br/>
    <input type='date' name='date_publication' value='<?php echo $bookEdit[0]['date_publication']?>' required>
    <br/><br/>
    <?php $query="SELECT * FROM library.category";
    $statement=$pdo->query($query);
    $allCategories = $statement->fetchAll(PDO::FETCH_ASSOC);
    $query="SELECT cb.idcategory AS id_category_cb FROM library.category c LEFT JOIN library.category_book cb ON c.idcategory = cb.idcategory WHERE cb.idbook = :editId";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':editId', $id, PDO::PARAM_INT);
    $statement->execute();
    $checkCategories = $statement->fetchAll(PDO::FETCH_ASSOC);?>
    <fieldset style='width:30%'>
        <legend>Catégories</legend>
    <?php $i=0;
    foreach ($allCategories as $onecategory) { 
        if ($onecategory['idcategory'] === $checkCategories[$i]['id_category_cb']) {
            echo "<div><input type='checkbox' id='categories' name='category[]' value='$onecategory[idcategory]' checked><label for='idcategories' >". $onecategory['name']. "</label></div>";
            while ($i < (count($checkCategories) - 1)) {
            $i++;
        } 
        } else {
            echo "<div><input type='checkbox' id='categories' name='category[]' value='$onecategory[idcategory]'><label for='idcategories' >". $onecategory['name']. "</label></div>";
        }
    }?>
    </fieldset>
    <br/><br/>
    <strong>Description</strong>
    <br/>
    <textarea id="description_book" name="description_book" maxlength="100"><?php echo $bookEdit[0]['description_book'] ?></textarea>
    <br/><br/>
    <input type='submit' value='Enregistrer'>
    </form>

    <a href='index.php'><br/><br/>Retour</a>  