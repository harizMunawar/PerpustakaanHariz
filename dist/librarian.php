<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Librarian Site";
    $navitem = array(
        'index' => array('text'=>'Logout', 'url'=>'login.php'),
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
        <title>Heaven's Door</title>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $navitem); ?>
        <!--End Of Navbar-->
    </body>
</html>