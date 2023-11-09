<?php
    include "header.php";
    $id = $_GET['identifiant'];
    deleteRow('library.category_book', 'idbook', $id);
    deleteRow('library.book', 'idbook', $id);
    header('location:index.php');
?>
