<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login'] || $_SESSION['role'] == "Admin") {
        header("Location: login.php");
    }
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];
    require("../snippets/navbar.php");
    $pageName = "Transaction List";    

    $getDate = $dbConn -> prepare("SELECT tglPinjam FROM transaksi GROUP BY tglPinjam");
    $getDate -> execute();
    $getDateCount = $getDate -> rowCount();

    $getTransactionCount = $dbConn ->prepare("SELECT * FROM transaksi, detailtransaksi WHERE transaksi.idTransaksi = detailtransaksi.idTransaksi");
    $getTransactionCount -> execute();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($pageName); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href='dashboard.php'>Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Transaction List</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->        
        <div class="container">
            <div class="alert alert-warning" role="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                You Have <strong><?php echo $getTransactionCount->rowCount();?></strong> Transaction(s) Pending, Waiting To Be Cleared
            </div>
            <center class="mb-5">
                <h1 class="display-4 bold">Do A Transaction</h1>
                <a href="transactionform.php" type="button" class="btn btn-primary mr-1 mt-1"><i class="fa fa-plus" aria-hidden="true"></i> Make New</a>                    
                <a href="transactionlist.php" type="button" class="btn btn-primary ml-1 mt-1">See All <i class="fa fa-eye" aria-hidden="true"></i></a>
            </center>
            <?php
                foreach($getDate -> fetchAll() as $getDateCount){
                    echo"<div class='row mb-2'>";
                    echo"    <div class='col'>";
                    echo"        <div class='card'>";
                    echo"            <nav class='card-header bg-gray sticky-top'>";
                    echo"                <div class='text-white'>".$getDateCount['tglPinjam']."</div>";
                    echo"            </nav>";                    
                    echo"            <ul class='list-group list-group-flush'>";
                    $getTransaction = $dbConn -> prepare("SELECT * FROM siswa, transaksi, detailtransaksi, buku WHERE siswa.nis = transaksi.nis AND transaksi.idTransaksi = detailtransaksi.idTransaksi AND detailtransaksi.idBuku = buku.idBuku AND transaksi.tglPinjam = '".$getDateCount['tglPinjam']."'");
                    $getTransaction -> execute();
                    foreach($getTransaction -> fetchAll() as $dataTransaction){                         
                    echo"                <li class='list-group-item'><h5 class='mb-0'><a class='text-dark stretched-link bold' href='#'>".$dataTransaction['nama']." - ".$dataTransaction['nis']."</a></h5></li>";
                    echo"                <div class='mb-3'>";
                    echo"                <small class='form-text text-muted ml-3 mb-n1'>Class: ".$dataTransaction['tingkat']." ".$dataTransaction['jurusan']." ".$dataTransaction['kelas']."</small>";                    
                    echo"                <small class='form-text text-muted ml-3 mb-n1'>Borrowed Book: ".$dataTransaction['judul']."</small>";
                    echo"                <small class='form-text text-muted ml-3 mb-n1'>'".$dataTransaction['judul']."' Stock Left: ".$dataTransaction['qty']."</small>";
                    $getLibrarian = $dbConn -> prepare("SELECT * FROM pustakawan WHERE idPustakawan ='".$dataTransaction['idPustakawan']."'");
                    $getLibrarian -> execute();
                    foreach($getLibrarian -> fetchAll() as $dataLibrarian)
                    echo"                <small class='form-text text-muted ml-3 mb-2'>Librarian In Charge: ".$dataLibrarian['nama']."</small>";
                    echo"                </div>";
                    }
                    echo"            </ul>";
                    echo"        </div>";
                    echo"    </div>";
                    echo"</div>";                    
                }
            ?>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>