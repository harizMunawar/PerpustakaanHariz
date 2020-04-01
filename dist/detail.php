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
    $navitem = array(
        'main' => array('text'=>'Back', 'url'=>'booklist.php'),
    );
?>
<html>
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
        <title>Heaven's Door</title>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $navitem); ?>
        <!--End Of Navbar-->
        <!-- Main Content -->
        <div class="row p-0 m-0">
            <div class="col offset-1 mt-5">
                <div class="display-4"><?php echo $judul; ?></div>
            </div>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>