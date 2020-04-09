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
    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
    }
    $getDate = $dbConn -> prepare("SELECT transaksi.tglPinjam FROM transaksi, detailTransaksi WHERE transaksi.idTransaksi = detailTransaksi.idTransaksi AND detailTransaksi.status = 0 GROUP BY transaksi.tglPinjam DESC");    
    $getDate -> execute();
    $getDateCount = $getDate -> rowCount();

    $getTransactionCount = $dbConn ->prepare("SELECT * FROM transaksi, detailtransaksi WHERE transaksi.idTransaksi = detailtransaksi.idTransaksi AND transaksi.idPustakawan ='".$_SESSION['id']."' AND detailTransaksi.status = 0");
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
            <?php if($getTransactionCount->rowCount() > 0){?>
            <div class="alert alert-warning" role="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                You Have <strong><?php echo $getTransactionCount->rowCount();?></strong> Transaction(s) Pending, Waiting To Be Finished
            </div>
            
            <?php }?>
            <center class="mb-5">                        
                <h1 class="display-4 bold">Do A Transaction</h1>
                <a href="transactionform.php?action=add" type="button" class="btn btn-primary mr-1 mt-1"><i class="fa fa-plus" aria-hidden="true"></i> Make New</a>                    
                <a href="report.php" type="button" class="btn btn-primary mr-1 mt-1"><i class="fa fa-folder" aria-hidden="true"></i> Report</a>
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
                    $getTransaction = $dbConn -> prepare("SELECT * FROM pustakawan, siswa, transaksi, detailtransaksi, buku WHERE siswa.nis = transaksi.nis AND transaksi.idTransaksi = detailtransaksi.idTransaksi AND detailtransaksi.idBuku = buku.idBuku AND transaksi.idPustakawan = pustakawan.idPustakawan AND transaksi.tglPinjam = '".$getDateCount['tglPinjam']."' AND detailTransaksi.status = 0 AND(siswa.nama LIKE :param OR siswa.nis LIKE :param OR siswa.tingkat LIKE :param OR siswa.jurusan LIKE :param OR siswa.kelas LIKE :param OR buku.judul LIKE :param OR transaksi.tglPinjam LIKE :param OR pustakawan.nama LIKE :param)");
                    $getTransaction->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                    $getTransaction -> execute();                                        
                    foreach($getTransaction -> fetchAll() as $dataTransaction){
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
                        if($_SESSION['id'] == $dataLibrarian['idPustakawan']){
                        echo"                               <i class='fa fa-exclamation-circle text-primary ml-1' aria-hidden='true'></i>";
                        }
                        echo"                            </button>";
                        echo"                        </h2>";
                        echo"                    </div>";
                        echo"                    <div id='collapse".$dataTransaction['idTransaksi']."' class='collapse' data-parent='#accordion".$dataTransaction['idTransaksi']."'>";
                        echo"                        <div class='card-body'>";                        
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Class: ".$dataTransaction['tingkat']." ".$dataTransaction['jurusan']." ".$dataTransaction['kelas']."</small>";                    
                        echo"                           <small class='form-text text-muted ml-3 mb-n1'>Borrowed Book: ".$dataTransaction['judul']."</small>"; 
                        echo"                           <small class='form-text text-muted ml-3 mb-2'>Librarian In Charge: ".$dataLibrarian['nama']."</small>";
                        if($_SESSION['id'] == $dataLibrarian['idPustakawan']){
                        echo"                           <a href='transactionform.php?action=finish&idtr=".$dataTransaction['idTransaksi']."' type='button' class='btn-sm btn-primary ml-3'>Return Book</a>";
                        }
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

        <!-- Confirm Finish Modal -->
        <!-- <div class="modal fade" id="borrowerView" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class=" text-capitalize modal-title" id="modalTitle"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="detailForm">
                            <input hidden type="text" class="form-control" name="Id" id="id">
                            <fieldset disabled="disabled">
                                <div class="modal-body">                                       
                                    <div class="form-group">
                                        <label for="inputName">Book's Borrower</label>
                                        <?php foreach($getBorrowedBook as $borrowerData){?>
                                        <input required type="text" class="form-control mb-1" name="Name" id="name" value="<?php echo $borrowerData['nama']?>">
                                        <?php }?>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>                
                </div>
            </div>
        </div> -->
        <!-- End Of Confirm Finish Modal -->
    </body>
</html>