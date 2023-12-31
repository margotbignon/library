<?php
function connectDB() {
    require_once '_connect.php';
    $pdo = new \PDO(DSN, USER, PASS);
    return $pdo;
}

function show(string $table) : array
{
    $pdo = connectDB();
    $query = "SELECT * FROM $table";
    $statement = $pdo->query($query);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/*function selectOne(string $valueDatabase, string $valuePhp, string $connectPhp, string $type) : array
{
    $query="SELECT * FROM $table WHERE $valueDatabase=:$valuePhp";
    $statement = pdo->prepare($query);
    $statement->bindValue(':$valuePhp', $connectPhp, PDO::PARAM_$type);
    $statement->execute();
    $array=$statement->fetchAll(PDO::FETCH_ASSOC);

}*/

function getRow($table, $idDatabase, $id) 
{
    $pdo = connectDB();
    $query = "SELECT * FROM $table WHERE $idDatabase=:myId";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':myId', $id, \PDO::PARAM_INT);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC); 
    return $array;
}

function deleteRow($table, $idDatabase, $id) {
    $pdo = connectDB();
    $query = "DELETE FROM $table WHERE $idDatabase=:myId";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':myId', $id, \PDO::PARAM_INT);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC); 
    return $array;
}

function getQuery($table, $column) 
{
    $pdo = connectDB();
    $query="SELECT * FROM $table ORDER BY $column ASC";
    $statement=$pdo->query($query);
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function insertInto2Param($table, $valueParam1, $valueParam2)
{
    $pdo = connectDB();
    $query = "INSERT INTO $table (firstname, lastname) VALUES (:param1, :param2)";
    $statement = $pdo->prepare($query);
    $statement->bindValue('param1', $valueParam1, PDO::PARAM_STR);
    $statement->bindValue('param2', $valueParam2, PDO::PARAM_STR);
    $statement->execute();
}
?>