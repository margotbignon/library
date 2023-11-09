<?php 
    include "header.php";
    $pdo = connectDB();
        if (!empty($_POST)) {
            if ((strlen($_POST['title']) > 45) || (strlen($_POST['newauthorfirstname']) > 45) || (strlen($_POST['newauthorlastname']) > 45) || (empty($_POST['category'])) ){
                if (strlen($_POST['title']) > 45) {
                    echo "<br/><p style='color:red'><strong>Do not exceed 45 characters for the title - Please re-type it</strong></p>";
                }
                if (strlen($_POST['newauthorfirstname']) > 45) {
                    echo "<br/><p style='color:red'><strong>Do not exceed 45 characters for the firstname author - Please re-type it</strong></p>";
                }
                if (strlen($_POST['newauthorlastname']) > 45) {
                    echo "<br/><p style='color:red'><strong>Do not exceed 45 characters for the lastname author - Please re-type it</strong></p>";
                }
                if (empty($_POST['category'])) {
                    echo "<br/><p style='color:red'><strong>Please choose at least one category.</strong></p>";
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

    
<h2>Add a book</h2></br></br>
<form method='post' style='margin-left:3em';>
    <strong>Title</strong> <br/> <input type="text" name="title"/>
    <br/><br/>
        <table>
            <tr>
                <th style='text-align:left;'><strong>Author</strong></th>
                <th style='text-align:left;'>Firstname New Author</th>
                <th style='text-align:left;'>LastName New Author</th></tr> 
            <tr>
                <td>
                    <select id='author' name='author'>
                        <option value=''>Choose the author</option>
                        <?php
                        
                        $authors = getQuery('library.author', 'library.author.lastname');
                        foreach ($authors as $author) {
                            echo "<option value='" . $author['idauthor'] . "'>" . $author['firstname'] . " " . $author['lastname'] . "</option>";       
                    } ?>
                        <option value='0'> -- New author --  </option></select></td>
                        <td><input type='text' name='newauthorfirstname'></td>
                        <td><input type="text" name="newauthorlastname"></td>
            </tr>
        </table>
    <br/>
    <strong>Price</strong> <br/> <input type='number' step="0.01" name='price' required>
    <br/><br/>
    <strong>Publication date</strong> <br/> <input type="date" name="date_publication" required>
    <br/><br/>
    <fieldset style='width:30%'>
        <legend>Categories</legend>
        <?php
        $allCategories = getQuery('library.category', 'library.category.name'); 
        foreach ($allCategories as $onecategory) { 
                echo "<div><input type='checkbox' id='categories' name='category[]' value='$onecategory[idcategory]'><label for='idcategories' >". $onecategory['name']. "</label></div>"; 
        }
    ?>
    </fieldset>
    <br/>
    <a href="category.php?ref=add">Ajouter une catégorie</a>
    <br/><br/>
    <strong>Description</strong>
    <br/>
    <textarea id="description_book" name="description_book" maxlength="100"></textarea>
    <br/><br/>
    <input type='submit' value='Save'>
</form>
<a href='index.php'><br/><br/>Back</a>  
       
   