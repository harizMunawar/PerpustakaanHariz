<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Profile Edit";
    $sql = "SELECT * FROM pustakawan WHERE pustakawan.idPustakawan=".$_GET['id'];
    $result = $dbConn -> prepare($sql);
    $result -> execute();
    $librarian=$result->fetch(PDO::FETCH_ASSOC);
    $role = $_GET['role'];
    $id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
        <title>Heaven's Door</title>
    </head>
    <body>

        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item" aria-current="page"><a class="text-capitalize" <?php echo "href='$role.php?id=$id'>Dashboard</a></li>"?>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

    </body>
</html>