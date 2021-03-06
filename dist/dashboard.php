<?php
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require_once("config.php");
    require("../snippets/navbar.php");
    $sql = "SELECT * FROM pustakawan WHERE pustakawan.idPustakawan=".$_SESSION['id'];
    $result = $dbConn -> prepare($sql);
    $result -> execute();
    $userData=$result->fetch(PDO::FETCH_ASSOC);
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];
    $page = "$role Site";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $id); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="pricing-header px-3 py-3 pt-md-3 pb-md-3 mx-auto text-center">
        <h1 class="display-4">Hello, <?php echo $userData['nama'];?></h1>
        <p class="lead">Welcome To <?php echo $role;?> Dashboard<br>Please Select One Of The Available Action Below</p>
        </div>

        <div class="container-fluid">
            <div class="card-deck text-center">

                <!-- Manage Books -->
                <?php
                    $buku = $dbConn -> prepare("SELECT * FROM buku");
                    $buku -> execute();
                    $kategori = $dbConn -> prepare("SELECT * FROM buku GROUP BY idKategori");
                    $kategori -> execute();
                    $penerbit = $dbConn -> prepare("SELECT * FROM buku GROUP BY idPenerbit");
                    $penerbit -> execute();
                    $penulis = $dbConn -> prepare("SELECT * FROM buku GROUP BY penulis");
                    $penulis -> execute();
                    $totalBuku = $buku -> rowCount();
                    $totalKategori = $kategori -> rowCount();
                    $totalPenerbit = $penerbit -> rowCount();
                    $totalPenulis = $penulis -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="booklist.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Books</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-book" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                            <li><?php echo $totalBuku;?> Available Books</li>
                            <li><?php echo $totalKategori;?> Different Categories</li>
                            <li>From <?php echo $totalPenulis?> Writers</li>
                            <li>Published By <?php echo $totalPenerbit?> Different Publisher</li>
                        </ul>                        
                    </div>
                </div>

                <!-- Manage Administrator -->
                <?php 
                    if ($role == 'Admin') {
                        $administrator = $dbConn ->prepare("SELECT * FROM login, pustakawan WHERE login.idPustakawan = pustakawan.idPustakawan ORDER BY nama");
                        $administrator -> execute();
                        $totalAdministrator = $administrator -> rowCount();

                        $admin = $dbConn ->prepare("SELECT * FROM login, pustakawan WHERE login.idPustakawan = pustakawan.idPustakawan AND login.hakUser = 'Admin' ORDER BY nama");
                        $admin -> execute();
                        $totalAdmin = $admin -> rowCount();

                        $librarian = $dbConn ->prepare("SELECT * FROM login, pustakawan WHERE login.idPustakawan = pustakawan.idPustakawan AND login.hakUser = 'Librarian' ORDER BY nama");
                        $librarian -> execute();
                        $totalLibrarian = $librarian -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="admin.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Librarian</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-user-circle" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li><?php echo $totalAdministrator?> Total Administrator</li>
                        <li><?php echo $totalAdmin?> Admin</li>
                        <li><?php echo $totalLibrarian?> Librarian</li>                        
                        </ul>
                    </div>
                </div>
                <?php }?>
                
                <!-- Manage Transaction -->
                <?php 
                if ($role == 'Librarian') {
                    $getUnfinishedTransaction = $dbConn -> prepare("SELECT * FROM detailtransaksi WHERE detailtransaksi.status=0");
                    $getUnfinishedTransaction -> execute();
                    $getUnfinishedTransactionCount = $getUnfinishedTransaction -> rowCount();
                    
                    $getMyTransaction = $dbConn -> prepare("SELECT * FROM transaksi, detailtransaksi WHERE transaksi.idTransaksi = detailtransaksi.idTransaksi AND transaksi.idPustakawan = ".$_SESSION['id']." AND detailtransaksi.status = 0");
                    $getMyTransaction -> execute();
                    $getMyTransactionCount = $getMyTransaction -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="transactionform.php?action=add" class="text-white display-5 my-0 font-weight-normal stretched-link">ManageTransaction</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-money" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li><?php echo $getUnfinishedTransactionCount?> Borrowed Books</li>
                        <li><?php echo $getMyTransactionCount?> Waiting For Your Action</li>
                        </ul>
                    </div>
                </div>
                <?php }?>
                
                <!-- Manage Publisher -->
                <?php
                    $publisher = $dbConn -> prepare("SELECT * FROM penerbit");
                    $publisher -> execute();
                    $totalPublisher = $publisher -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="publisher.php?action=" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Publisher</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-podcast" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li><?php echo $totalPublisher?> Registered Publisher</li>
                        </ul>
                    </div>
                </div>

                <!-- Manage Student -->
                <?php
                    $student = $dbConn -> prepare("SELECT * FROM siswa");
                    $student -> execute();
                    $totalStudent = $student -> rowCount();

                    $jurusan = $dbConn -> prepare("SELECT * FROM siswa GROUP BY jurusan");
                    $jurusan -> execute();
                    $totalJurusan = $jurusan -> rowCount();

                    $tingkat = $dbConn -> prepare("SELECT * FROM siswa GROUP BY tingkat");
                    $tingkat -> execute();
                    $totalTingkat = $tingkat -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="studentlist.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Student</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-id-card" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>Total <?php echo $totalStudent?> Students</li>
                        <li><?php echo $totalJurusan?> Jurusan</li>
                        <li><?php echo $totalTingkat?> Tingkat</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Manage Report -->
                <?php
                    $getFinishedTransaction = $dbConn -> prepare("SELECT * FROM detailTransaksi WHERE detailTransaksi.status=1");
                    $getFinishedTransaction -> execute();
                    $getFinishedTransactionCount = $getFinishedTransaction -> rowCount();

                    $getPenalty = $dbConn -> prepare("SELECT * FROM transaksi, detailTransaksi WHERE transaksi.idTransaksi = detailTransaksi.idTransaksi AND (DATEDIFF(detailTransaksi.tglKembali, transaksi.tglPinjam) > 3)");
                    $getPenalty -> execute();
                    $getPenaltyCount = $getPenalty -> rowCount();
                ?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="report.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Report</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-folder-open" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li><?php echo $getFinishedTransactionCount?> Returned Books</li>
                        <li><?php echo $getPenaltyCount?> Late Returned Books</li>                        
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Of Main Content -->

    </body>
</html>
