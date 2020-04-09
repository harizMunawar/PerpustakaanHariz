<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    session_start();
    $sql = "SELECT * FROM buku";
    $id = $_GET['id'];
    $type = $_GET['type'];
    if($type=="book"){
        $detailbuku = "SELECT * FROM buku WHERE idBuku='".$id."'";
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
        $selectForeign = "SELECT penerbit.nama, penerbit.alamat, penerbit.phone, penerbit.email, kategori.kategoriBuku FROM penerbit, kategori WHERE idPenerbit=".$row['idPenerbit']." AND idKategori=".$row['idKategori'];
        foreach ($dbConn->query($selectForeign) as $rowforeign) {
            $category = $rowforeign['kategoriBuku'];        
            $publisher = $rowforeign['nama'];
            $pubaddress = $rowforeign['alamat'];
            $pubphone = $rowforeign['phone'];
            $pubemail = $rowforeign['email'];
        }
        $page = $row['judul']." Detail";
        $getBorrowedBook = $dbConn -> prepare("SELECT * FROM buku, detailtransaksi, transaksi, siswa WHERE transaksi.idTransaksi = detailTransaksi.idTransaksi AND transaksi.nis = siswa.nis AND buku.idBuku = detailtransaksi.idBuku AND detailTransaksi.status = 0 AND buku.idBUku = ".$id);
        $getBorrowedBook -> execute();
        $getBorrowedBookCount = $getBorrowedBook -> rowCount();
        $getHistory = $dbConn -> prepare("SELECT * FROM buku, detailtransaksi, transaksi, siswa WHERE transaksi.idTransaksi = detailTransaksi.idTransaksi AND transaksi.nis = siswa.nis AND buku.idBuku = detailtransaksi.idBuku AND detailTransaksi.status = 1 AND buku.idBUku = ".$id);
        $getHistory -> execute();
        $getHistoryCount = $getHistory -> rowCount();
    }
    if($type=="student"){
        $getStudent = $dbConn->prepare("SELECT * FROM siswa WHERE nis = $id");
        $getStudent->execute();
        foreach($getStudent -> fetchAll() as $studentData){}
        $title = $studentData['nama'];
        $page = $studentData['nama']." Detail";

        $getHistory = $dbConn -> prepare("SELECT * FROM buku, detailtransaksi, transaksi, siswa WHERE transaksi.idTransaksi = detailTransaksi.idTransaksi AND transaksi.nis = siswa.nis AND buku.idBuku = detailtransaksi.idBuku AND detailTransaksi.status = 1 AND siswa.nis = ".$id);
        $getHistory -> execute();
    }
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
                    <li class="breadcrumb-item" aria-current="page"><a href='dashboard.php'>Dashboard</a></li>
                <?php } ?>
                <?php if ($type == "book") {?>
                <li class="breadcrumb-item" aria-current="page"><a href="booklist.php">Book List</a></li>
                <?php }else{ ?>
                <li class="breadcrumb-item" aria-current="page"><a href="studentlist.php">Student List</a></li>
                <?php } ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $title;?></li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">
            <div class="row p-0 m-0">
                <div class="col-4 mt-5 text-center">                    
                    <div>
                        <?php if ($type == "book") {?>
                        <img style="width:55%;" class="img img-thumbnail" src="../upload/book_cover/<?php echo $image;?>">
                        <?php }else{?>
                        <img style="width:55%;" class="img img-thumbnail" src="../upload/student_avatar/<?php echo $studentData['image'];?>">
                        <?php }?>
                    </div>
                    <a type="button" data-toggle="modal" data-target="#historyView" class="btn btn-success text-white mr-1 mt-1"><i class="fa fa-folder" aria-hidden="true"></i> History</a>
                </div>
                <div class="col-8 mt-5">
                    <div class="display-3"><?php echo $title;?></div>                    
                    <?php if ($type == "book") {?><small class="text-muted">Writer: <?php echo $writer;?></small><?php }?>
                    <?php if($type == "student"){?><small class="text-muted">Class: <?php echo $studentData['tingkat']." ".$studentData['jurusan']." ".$studentData['kelas'];?></small><br><small class="text-muted">Nis: <?php echo $studentData['nis'];?></small><?php }?>
                    
                    <?php if ($type == "book") {?>
                        <div class="mt-2">
                            <?php echo $title;?> Is A Book That Was Published By <a href="" data-toggle="modal" data-target="#publisherViewForm"><?php echo $publisher;?></a>. The Category Of This Book Is <?php echo $category;?>
                        </div>
                        <div class="mt-2">
                            Current Available Stock: <?php echo $stock;?>
                        </div>
                        <div class="mt-2 text-danger">
                        <?php if($getBorrowedBookCount > 0){?><a class='text-danger' href="" data-toggle="modal" data-target="#borrowerView"><?php }?>Currently Borrowed: <?php echo $getBorrowedBookCount;?></a>
                        </div>
                        <div class="mt-2">
                            Synopsis:<br><small class="text-muted text-justify"><?php echo $synopsis;?></small>
                        </div>                 
                    <?php }else{?>
                        <div class="mt-2">
                            Address: <?php echo $studentData['alamat'];?>
                        </div>
                        <div class="">
                            Email Address: <?php echo $studentData['email'];?>
                        </div>
                        <div class="">
                            Phone: <?php echo $studentData['phone'];?>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
        <!-- End Of Main Content -->

        <!-- View Publisher Modal -->
        <div class="modal fade" id="publisherViewForm" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">Publisher Detail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="detailForm">
                            <input hidden type="text" class="form-control" name="Id" id="id">
                            <fieldset disabled="disabled">
                                <div class="modal-body">                                       
                                    <div class="form-group">
                                        <label for="inputName">Publisher's Name</label>
                                        <input required type="text" class="form-control" name="Name" id="name" value="<?php echo $publisher?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress">Publisher's Address</label>
                                        <input required type="text" class="form-control" name="Address" id="address" value="<?php echo $pubaddress?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPhone">Publisher's Phone Number</label>
                                        <input required type="tel" class="form-control" name="Phone" id="phone" value="<?php echo $pubphone?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail">Publisher's Email Address</label>
                                        <input required type="email" class="form-control" name="Email" id="email" value="<?php echo $pubemail?>">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        <!-- End Of View Publisher Modal -->

        <!-- View Borrower Modal -->
        <div class="modal fade" id="borrowerView" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">Current Book's Borrower List</h5>
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
        </div>
        <!-- End Of View Borrower Modal -->

        <!-- View History Modal -->
        <div class="modal fade" id="historyView" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">History</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="detailForm">
                            <input hidden type="text" class="form-control" name="Id" id="id">
                            <fieldset disabled="disabled">
                                <div class="modal-body">                                       
                                    <div class="form-group">
                                        <label for="inputName" class="text-capitalize"><?php echo $type?>'s Borrowing History</label>
                                        <?php 
                                            foreach($getHistory as $historyData){
                                                if($_GET['type'] == "book"){?>
                                                    <input required type="text" class="form-control mb-1" name="Name" id="name" value="<?php echo $historyData['nama']." at ".$historyData['tglPinjam']?>">
                                                    <small class="text-muted"><?php echo $historyData['nis']?></small>
                                                <?php }else{
                                                    $pinjam = date_create($historyData['tglPinjam']);
                                                    $kembali = date_create($historyData['tglKembali']);
                                                    $daysBorrowed = date_diff($pinjam, $kembali);
                                                ?>
                                                    <input required type="text" class="form-control mb-1" name="Name" id="name" value="<?php echo $historyData['judul']." at ".$historyData['tglPinjam']?>">            
                                                    <small class="text-danger"><?php if((1000 * ($daysBorrowed -> format ('%a%') - 3)) > 0)echo "Penalty: ".(1000 * ($daysBorrowed -> format ('%a%') - 3))?></small>
                                                <?php }?>                                                                                                                                                                   
                                        <?php }?>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        <!-- End Of View History Modal -->
    </body>
</html>