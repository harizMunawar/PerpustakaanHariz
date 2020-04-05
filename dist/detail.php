<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    session_start();
    $sql = "SELECT * FROM buku";
    $idbuku = $_GET['id'];
    $detailbuku = "SELECT * FROM buku WHERE idBuku='".$idbuku."'";
    foreach ($dbConn->query($detailbuku) as $row) {
        $id = $row['idBuku'];
        $title = $row['judul'];
        $writer = $row['penulis'];
        $image = $row['image'];
        $stock = $row['qty'];
        $synopsis = $row['sinopsis'];
        $next = $id+1;
        $prev = $id-1;        
    }
    $selectForeign = "SELECT penerbit.nama, kategori.kategoriBuku FROM penerbit, kategori WHERE idPenerbit=".$row['idPenerbit']." AND idKategori=".$row['idKategori'];
    foreach ($dbConn->query($selectForeign) as $rowforeign) {
        $category = $rowforeign['kategoriBuku'];
        $publisher = $rowforeign['nama'];
    }
    $page = $row['judul']." Detail";
?>
<html>
    <head>    
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>
    
    <body class="overflow-auto">
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <?php if ($_SESSION['login']) {?>
                    <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                    <li class="breadcrumb-item" aria-current="page"><?php echo "<a href='dashboard.php?role=".$_SESSION['role']."&id=".$_SESSION['id']."'>Dashboard</a></li>";?>
                <?php } ?>
                <li class="breadcrumb-item" aria-current="page"><a href="booklist.php">Book List</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">
            <div class="row p-0 m-0">
                <div class="col-1 mt-5 text-center p-0 m-0 d-none d-md-block">                    
                    <div style="margin-top:9rem;">
                        <a href="?id=<?php echo $prev?>" role="button" class="btn btn-success"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Prev</a>
                    </div>
                </div>
                <div class="col-4 mt-5 text-center">                    
                    <div>
                        <img style="width:55%;" class="img img-thumbnail" src="../upload/<?php echo $image;?>">
                    </div>
                </div>
                <div class="col-6 mt-5">
                    <div class="display-3"><?php echo $title;?></div>
                    <small class="text-muted">Writer: <?php echo $writer;?></small>
                    <div class="mt-2">
                        <?php echo $title;?> Is A Book That Was Published By <a href=""><?php echo $publisher;?></a>. The Category Of This Book Is <?php echo $category;?>
                    </div>
                    <div class="mt-2">
                        Current Available Stock: <?php echo $stock;?>
                    </div>
                    <div class="mt-2">
                        Synopsis:<br><small class="text-muted text-justify"><?php echo $synopsis;?></small>
                    </div>                    
                </div>
                <div class="col-1 mt-5 text-center p-0 m-0 d-none d-md-block">                    
                    <div style="margin-top:9rem;">
                        <a href="?id=<?php echo $next?>" role="button" class="btn btn-success">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>