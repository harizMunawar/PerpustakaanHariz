<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    session_start();
    $page = "Report";

    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
    }
    $getDate = $dbConn -> prepare("SELECT transaksi.tglPinjam FROM transaksi, detailtransaksi WHERE transaksi.idTransaksi = detailtransaksi.idTransaksi AND detailtransaksi.status = 1 GROUP BY transaksi.tglPinjam DESC");    
    $getDate -> execute();
    $getDateCount = $getDate -> rowCount();
?>
<!DOCTYPE html>
<html lang="en">
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
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href='dashboard.php'>Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container">
            <center>
                <h1 class="display-4 bold mb-3">Returned Books</h1>                    
            </center>
            <?php
                foreach($getDate -> fetchAll() as $getDate){                    
                    echo"<div class='row mb-2'>";
                    echo"    <div class='col'>";
                    echo"        <div class='card'>";
                    echo"            <nav class='card-header bg-gray sticky-top'>";
                    echo"                <div class='text-white'>".$getDate['tglPinjam']."</div>";
                    echo"            </nav>";                                                            
                    $getTransaction = $dbConn -> prepare("SELECT * FROM pustakawan, siswa, transaksi, detailtransaksi, buku WHERE siswa.nis = transaksi.nis AND transaksi.idTransaksi = detailtransaksi.idTransaksi AND detailtransaksi.idBuku = buku.idBuku AND transaksi.idPustakawan = pustakawan.idPustakawan AND transaksi.tglPinjam = '".$getDate['tglPinjam']."' AND detailtransaksi.status = 1 AND(siswa.nama LIKE :param OR siswa.nis LIKE :param OR siswa.tingkat LIKE :param OR siswa.jurusan LIKE :param OR siswa.kelas LIKE :param OR buku.judul LIKE :param OR transaksi.idTransaksi LIKE :param OR transaksi.tglPinjam LIKE :param OR pustakawan.nama LIKE :param OR DATEDIFF(detailtransaksi.tglKembali, transaksi.tglPinjam) LIKE :param OR (1000 * (DATEDIFF(detailTransaksi.tglKembali, transaksi.tglPinjam) - 3)) LIKE :param)");
                    $getTransaction->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                    $getTransaction -> execute();                                                            
                    foreach($getTransaction -> fetchAll() as $dataTransaction){
                        $pinjam = date_create($dataTransaction['tglPinjam']);
                        $kembali = date_create($dataTransaction['tglKembali']);
                        $daysBorrowed = date_diff($pinjam, $kembali);
                        
                        $getLibrarian = $dbConn -> prepare("SELECT * FROM pustakawan WHERE idPustakawan ='".$dataTransaction['idPustakawan']."'");
                        $getLibrarian -> execute();
                        foreach($getLibrarian -> fetchAll() as $dataLibrarian){}
                        echo"            <div class='accordion' id='accordion".$dataTransaction['idTransaksi']."'>";
                        echo"                <div class='card'>";
                        echo"                    <div class='card-header' id='headingOne'>";
                        echo"                        <h2 class='mb-0'>";
                        echo"                            <button class='btn btn-link text-dark text-capitalize' type='button' data-toggle='collapse' data-target='#collapse".$dataTransaction['idTransaksi']."' aria-expanded='false' aria-controls='collapseOne'>";
                        echo                                $dataTransaction['nama']." - ".$dataTransaction['nis'];
                        echo"                               <small class='text-muted'>(Transaction ID: ".$dataTransaction['idTransaksi'].")</small>";
                        echo"                            </button>";
                        echo"                        </h2>";
                        echo"                    </div>";
                        echo"                    <div id='collapse".$dataTransaction['idTransaksi']."' class='collapse' data-parent='#accordion".$dataTransaction['idTransaksi']."'>";
                        echo"                        <div class='card-body'>";                        
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Class: ".$dataTransaction['tingkat']." ".$dataTransaction['jurusan']." ".$dataTransaction['kelas']."</small>";                    
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Borrowed Book: ".$dataTransaction['judul']."</small>";
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Returned Date: ".$dataTransaction['tglKembali']."</small>";
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Borrowing Period: ".$daysBorrowed -> format ('%a%')." days</small>";
                        if($daysBorrowed -> format ('%a%') > 3){
                        echo"                           <small class='form-text text-danger ml-3 mb-n1'>Penalty: ".(1000 * ($daysBorrowed -> format ('%a%') - 3))."</small>";
                        }
                        echo"                           <small class='form-text text-muted ml-3 mb-2'>Librarian In Charge: ".$dataLibrarian['nama']."</small>";
                        echo"                           <a href='transactionform.php?action=edit&idtr=".$dataTransaction['idTransaksi']."&penalty=".(1000 * ($daysBorrowed -> format ('%a%') - 3))."' type='button' class='btn-sm btn-warning ml-3'>Edit</a>";
                        echo"                           <a href='transactionform.php?action=delete&idtr=".$dataTransaction['idTransaksi']."&penalty=".(1000 * ($daysBorrowed -> format ('%a%') - 3))."' type='button' class='btn-sm btn-danger'>Delete</a>";
                        echo"                        </div>";
                        echo"                    </div>";
                        echo"                </div>";                                                        
                        echo"            </div>";                        
                        }                        
                    echo"        </div>";
                    echo"    </div>";
                    echo"</div>";                                     
                }
            ?>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>
