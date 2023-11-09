<?php 
    $pdo = new \PDO('mysql:host=localhost;dbname=library', 'root', '');
        if (!empty($_POST)) {
            if ((strlen($_POST['title']) > 45) || (strlen($_POST['newauthorfirstname']) > 45) || (strlen($_POST['newauthorlastname']) > 45) || (empty($_POST['category'])) ){
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
                $title = trim($_POST["title"]);
                $idauthor = $_POST['author'];
                $price = trim($_POST['price']);
                $date_publication = ($_POST['date_publication']);
                $description_book = ($_POST['description_book']);
                $newAuthorfirstname = ($_POST['newauthorfirstname']);
                $newAuthorlastname = ($_POST['newauthorlastname']);
                if ($idauthor != 0) {
                    $query = "INSERT INTO library.book (title, price, date_publication, idauthor, description_book) VALUES (:title, :price, :date_publication, :idauthor, :description_book)";
                    $statement = $pdo->prepare($query);
                    $statement->bindValue(':title', $title);
                    $statement->bindValue(':price', $price);
                    $statement->bindValue(':date_publication', $date_publication);
                    $statement->bindValue(':idauthor', $idauthor);
                    $statement->bindValue(':description_book', $description_book);
                    $statement->execute();
                } else {
                    $query = "INSERT INTO library.author (firstname, lastname) VALUES (:firstname, :lastname)";
                    $statement = $pdo->prepare($query);
                    $statement->bindValue(':firstname', $newAuthorfirstname, PDO::PARAM_STR);
                    $statement->bindValue(':lastname', $newAuthorlastname, PDO::PARAM_STR);
                    $statement->execute();
                    $idNewAuthor = $pdo->lastInsertId();
                    $query = "INSERT INTO library.book (title, price, date_publication, idauthor) VALUES (:title, :price, :date_publication, :idauthor)";
                    $statement = $pdo->prepare($query);
                    $statement->bindValue(':title', $title);
                    $statement->bindValue(':price', $price);
                    $statement->bindValue(':date_publication', $date_publication);
                    $statement->bindValue(':idauthor', $idNewAuthor);
                    $statement->execute();
                }
                $categories = ($_POST['category']);
                $idNewbook = $pdo->lastInsertId();
                foreach ($categories as $category) {
                    $query = "INSERT INTO library.category_book (idbook, idcategory) VALUES (:idNewbook, :category)";
                    $statement = $pdo->prepare($query);
                    $statement->bindValue(':idNewbook', $idNewbook, PDO::PARAM_STR);
                    $statement->bindValue(':category', $category, PDO::PARAM_STR);
                    $statement->execute();
                    } 
                header('Location: index.php');
                die;   
            }   
        }
?>

    
<h2>Ajout d'un livre</h2></br></br>
<form method='post' style='margin-left:3em';>
    <strong>Titre</strong> <br/> <input type="text" name="title"/>
    <br/><br/>
        <table>
            <tr>
                <th style='text-align:left;'><strong>Auteur</strong></th>
                <th style='text-align:left;'>Prénom Nouvel Auteur</th>
                <th style='text-align:left;'>Nom Nouvel Auteur</th></tr> 
            <tr>
                <td>
                    <select id='author' name='author'>
                        <option value=''>Choisissez votre auteur</option>
                        <?php
                        
                        $statement = $pdo->query("SELECT * FROM library.author");
                        $authors = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($authors as $author) {
                            echo "<option value='" . $author['idauthor'] . "'>" . $author['firstname'] . " " . $author['lastname'] . "</option>";       
                    } ?>
                        <option value='0'> -- Nouvel auteur --  </option></select></td>
                        <td><input type='text' name='newauthorfirstname'></td>
                        <td><input type="text" name="newauthorlastname"></td>
            </tr>
        </table>
    <br/>
    <strong>Prix</strong> <br/> <input type='number' step="0.01" name='price' required>
    <br/><br/>
    <strong>Date de publication</strong> <br/> <input type="date" name="date_publication" required>
    <br/><br/>
    <fieldset style='width:30%'>
        <legend>Catégories</legend>
        <?php
        $query="SELECT * FROM library.category";
        $statement=$pdo->query($query);
        $allCategories = $statement->fetchAll(PDO::FETCH_ASSOC); 
        foreach ($allCategories as $onecategory) { 
                echo "<div><input type='checkbox' id='categories' name='category[]' value='$onecategory[idcategory]'><label for='idcategories' >". $onecategory['name']. "</label></div>"; 
        }
    ?>
    </fieldset>
    <br/><br/>
    <strong>Description</strong>
    <br/>
    <textarea id="description_book" name="description_book" maxlength="100"></textarea>
    <br/><br/>
<input type='submit' value='Enregistrer'>
</form>
<a href='index.php'><br/><br/>Retour</a>  
       
   