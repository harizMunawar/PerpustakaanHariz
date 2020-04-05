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

                <?php if ($role == 'Admin') {?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Librarian</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-user-circle" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>20 users included</li>
                        <li>10 GB of storage</li>
                        <li>Priority email support</li>
                        <li>Help center access</li>
                        </ul>
                    </div>
                </div>
                <?php }?>

                <?php if ($role == 'Librarian') {?>
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Transaction</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-money" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>20 users included</li>
                        <li>10 GB of storage</li>
                        <li>Priority email support</li>
                        <li>Help center access</li>
                        </ul>
                    </div>
                </div>
                <?php }?>
                
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Publisher</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-podcast" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Student</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-id-card" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Report</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><i class="fa fa-folder-open" aria-hidden="true"></i></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Of Main Content -->

    </body>
</html>