<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require("../snippets/navbar.php");
    $page = "Administrator List";
    $sql = "SELECT * FROM pustakawan WHERE pustakawan.idPustakawan=".$_SESSION['id'];
    $result = $dbConn -> prepare($sql);
    $result -> execute();
    $librarian=$result->fetch(PDO::FETCH_ASSOC);
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];
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
                <li class="breadcrumb-item" aria-current="page"><a class="text-capitalize" <?php echo "href='dashboard.php?role=$role&id=$id'>Dashboard</a></li>"?>
                <li class="breadcrumb-item active" aria-current="page">Administrator List</li>
                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">
            <div class="display-4 m-5">Administrator List</div>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End Of Main Content -->

    </body>
</html>