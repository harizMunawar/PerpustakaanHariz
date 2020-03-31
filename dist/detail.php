<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $sql = "SELECT * FROM buku";
    $idbuku = $_POST['idBuku'];
    $detailbuku = "SELECT * FROM buku WHERE idBuku='".$idbuku."'";
    foreach ($dbConn->query($detailbuku) as $row) {
        $judul = $row['judul'];
    }
    $page = $judul."Detail";
    $navitem = array(
        'main' => array('text'=>'Main', 'url'=>'booklist.php'),
        'detail' => array('text'=>'Detail', 'url'=>'detail.php'),
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
        
        <!-- End Of Main Content -->
    </body>
</html>