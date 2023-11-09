<?php

session_start();
echo "Connectez-vous !<br/><br/>";
if (!empty($_POST['name'])) {
    $_SESSION['name'] = $_POST['name'];
    if ($_SESSION['name'] == "mbignon@gmail.com") {
        header("Location:index.php");
        die;
    } else {
    header("Location:shop.php");
    die;
    }       
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input name="name" type="text">
<input type="submit" value="submit">