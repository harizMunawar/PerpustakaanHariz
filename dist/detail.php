<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $sql = "SELECT * FROM buku";
    $idbuku = $_GET['id'];
    $detailbuku = "SELECT * FROM buku WHERE idBuku='".$idbuku."'";
    foreach ($dbConn->query($detailbuku) as $row) {
        $judul = $row['judul'];
    }
    $page = $judul." Detail";
?>
<html>
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>
    
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="booklist.php">Book List</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $judul; ?></li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="row p-0 m-0">
            <div class="col offset-1 mt-5">
                <div class="display-4"><?php echo $judul; ?></div>
            </div>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>