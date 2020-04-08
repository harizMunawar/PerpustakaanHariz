<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login'] || $_SESSION['role'] == "Admin") {
        header("Location: login.php");
    }
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];
    $action = $_GET['action'];

    if($action == "finish"){
        $getTransaction = $dbConn -> prepare("SELECT * FROM siswa, transaksi, detailtransaksi, buku WHERE siswa.nis = transaksi.nis AND transaksi.idTransaksi = detailtransaksi.idTransaksi AND detailtransaksi.idBuku = buku.idBuku AND transaksi.idTransaksi = ".$_GET['idtr']);        
        $getTransaction -> execute();
        foreach($getTransaction as $transactionData);
    }
    require("../snippets/navbar.php");
    $pageName = "Transaction Form";    

    $getLibrarianData = $dbConn -> prepare("SELECT * FROM pustakawan WHERE idPustakawan = ".$_SESSION['id']);
    $getLibrarianData -> execute();
    foreach($getLibrarianData -> fetchAll() as $data){

    }

    $getTransactionId = $dbConn -> prepare("SELECT MAX(idTransaksi DIV 1) AS id FROM transaksi");
    $getTransactionId -> execute();
    foreach ($getTransactionId -> fetchAll() as $newRow){
        $newRow['id']++;
    }
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
                <li class="breadcrumb-item active" aria-current="page">Transaction Form</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container">
            
            <center>            
                <h1 class="display-4 bold">Do A Transaction</h1>
            <?php if($action == 'add'){?>
                <a href="transactionform.php?transaction=add" type="button" class="btn btn-primary mr-1 mt-1"><i class="fa fa-plus" aria-hidden="true"></i> Make New</a>                    
            <?php }?>
                <a href="transactionlist.php" type="button" class="btn btn-primary ml-1 mt-1">See All <i class="fa fa-eye" aria-hidden="true"></i></a>
            </center>
            
            <div class="row">
                <div class="col">
                    <form action="transactionform.php?action=<?php echo $action;?><?php if($action=="finish"){?>&idtr=<?php echo $_GET['idtr'];}?>" method="post">
                        <div class="form-group">                            
                            <label for="inputLibrarian">Librarian In Charge</label>
                            <input disabled type="text" class="form-control" name="librarian" id="inputLibrarian" value="<?php echo $data['nama'];?>">
                            <input hidden type="text" class="form-control" name="id" id="inputId" value="<?php if($action=="add")echo $newRow['id'];else echo $_GET['idtr']?>">
                        </div>
                        <div class="form-group">                            
                            <label for="inputBorrower">Select Borrower</label>                            
                            <?php
                                if($action == "add"){
                                    echo "<select class='form-control' name='borrower' id='inputBorrower'>";
                                    $getStudent = $dbConn -> prepare("SELECT * FROM siswa ORDER BY nama");
                                    $getStudent -> execute();
                                    foreach($getStudent -> fetchAll() as $rowStudent){
                                        echo "<option value='".$rowStudent['nis']."'>".$rowStudent['nama']."</option>";
                                    }                    
                                }else{
                                    echo "<select disabled class='form-control' name='borrower' id='inputBorrower'>";
                                    echo"<option value'".$transactionData['nis']."'>".$transactionData['nama']."</option>";
                                }                    
                            ?>
                            </select>                           
                        </div> 
                        <div class="form-group">
                            <div class="row">                                                            
                                <div class="col">
                                    <?php if($action == "add"){?>
                                        <label for="inputDate">Borrowed Date</label>
                                        <input required type="date" class="form-control" name="date" id="inputDate">
                                        <small id="dateHelp" class="form-text text-muted hidden">You Cannot Enter Date That Is More Than Today</small>    
                                    <?php }else{?>
                                        <label for="inputDate">Borrowed Date</label>
                                        <input required disabled type="date" class="form-control" name="date" id="inputDate" value="<?php echo $transactionData['tglPinjam'];?>">                                    
                                    <?php }?>
                                </div>   
                                <?php if ($action == "finish"){?>
                                    <div class="col">
                                        <label for="inputDate">Returned Date</label>
                                        <input required type="date" class="form-control " min="<?php echo $transactionData['tglPinjam']?>" name="return" id="returnDate">
                                        <small id="dateHelp" class="form-text text-muted hidden">You Can't Enter Date That Is Less Than Borrowed Date</small>
                                    </div>
                                    <div class="col">
                                        <label for="inputDate">Penalty</label>
                                        <input disabled type="text" class="form-control " name="penalty" id="penaltyField" value="0">
                                        <small id="dateHelp" class="form-text text-muted hidden">You'll Get Penalty If You Borrowed Book More Than 3 Days</small>
                                    </div>
                                <?php }?>
                            </div>
                        </div> 
                        <div class="form-group">                                                       
                            <?php
                                if($action == "add"){
                                    echo "<label for='inputBook1'>First Book's Title</label>";
                                    echo "<select class='form-control' name='book1' id='inputBook1'>";
                                    $getBook = $dbConn -> prepare("SELECT * FROM buku WHERE qty != 0 ORDER BY judul");
                                    $getBook -> execute();
                                    foreach($getBook -> fetchAll() as $rowBuku){
                                        echo "<option value='".$rowBuku['idBuku']."'>".$rowBuku['judul']."</option>";
                                    }                    
                                }else{
                                    echo "<label for='inputBook1'>Borrowed Book's Title</label>";
                                    echo "<select disabled class='form-control' name='book1' id='inputBook1'>";                                    
                                    echo "<option value='".$transactionData['idBuku']."'>".$transactionData['judul']."</option>";
                                }                 
                            ?>
                            </select>
                            <input hidden type='text' value='<?php echo $transactionData['idBuku']?>' name='returnbook1'>
                            <small id="book1Help" class="form-text text-muted hidden">First Borrowed Book Title</small>                            
                        </div>
                        <?php if($action == "add"){?>
                        <div class="form-group">
                            <label for="inputBook2">Second Book's Title</label>
                            <select class="form-control" name="book2" id="inputBook2">
                                <option value="null">This Is Optional</option>
                            <?php
                                $getBook2 = $dbConn -> prepare("SELECT * FROM buku WHERE qty != 0 ORDER BY judul");
                                $getBook2 -> execute();
                                foreach($getBook2 -> fetchAll() as $rowBuku){
                                    echo "<option value='".$rowBuku['idBuku']."'>".$rowBuku['judul']."</option>";
                                }                                        
                            ?>
                            </select>
                            <small id="book1Help" class="form-text text-muted hidden">Second Borrowed Book Title (Leave It Alone If You Only Want To Borrow One Book)</small>
                        </div>
                        <?php }?>
                        <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>
<?php
    if(isset($_POST['submit']) && $action == "add"){
        $id = $_POST['id'];
        $nis = $_POST['borrower'];
        $librarianid = $_SESSION['id'];
        $date = $_POST['date'];
        $bookid1 = $_POST['book1'];
        $insertTransaksi1 = $dbConn ->prepare("INSERT INTO transaksi VALUES(:fidtr,:fnis,:fidpus,:ftgl)");        
        $insertTransaksi1 -> bindParam(":fidtr",$id);
        $insertTransaksi1 -> bindParam(":fnis",$nis);
        $insertTransaksi1 -> bindParam(":fidpus",$librarianid);
        $insertTransaksi1 -> bindParam(":ftgl",$date);
        if ($insertTransaksi1 -> execute()){
            $insertDetail1 = $dbConn ->prepare("INSERT INTO detailtransaksi (idTransaksi, idBuku, status) VALUES (:fidtr,:fidbuku,0)");
            $insertDetail1 -> bindparam(":fidtr",$id);
            $insertDetail1 -> bindparam(":fidbuku",$bookid1);
            if($insertDetail1 -> execute()){
                $updateBook1 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$bookid1");
                if($updateBook1 -> execute()){
                    if($_POST['book2'] != "null"){
                        $bookid2 = $_POST['book2'];
                        $id2 = $id+1;
                        $insertTransaksi2 = $dbConn ->prepare("INSERT INTO transaksi VALUES(:fidtr,:fnis,:fidpus,:ftgl)");        
                        $insertTransaksi2 -> bindParam(":fidtr",$id2);
                        $insertTransaksi2 -> bindParam(":fnis",$nis);
                        $insertTransaksi2 -> bindParam(":fidpus",$librarianid);
                        $insertTransaksi2 -> bindParam(":ftgl",$date);
                        if ($insertTransaksi2 -> execute()){
                            $insertDetail2 = $dbConn ->prepare("INSERT INTO detailtransaksi (idTransaksi, idBuku, status) VALUES (:fidtr,:fidbuku,0)");
                            $insertDetail2 -> bindparam(":fidtr",$id2);
                            $insertDetail2 -> bindparam(":fidbuku",$bookid2);
                            if($insertDetail2 -> execute()){
                                $updateBook2 = $dbConn ->prepare("UPDATE buku SET qty=qty-1 WHERE idBuku=$bookid2");
                                if($updateBook1 -> execute()){
                                    if (headers_sent()) {
                                        die("<script> location.replace('transactionlist.php'); </script>");
                                    }else{
                                        exit(header("Location: transactionlist.php"));
                                    }
                                }
                            }
                        }
                    }                    
                }else{
                    if (headers_sent()) {
                        die("<script> location.replace('transactionlist.php'); </script>");
                    }
                    else{
                        exit(header("Location: transactionlist.php"));
                    }
                }           
            }
        }
    }
    if(isset($_POST['submit']) && $action == "finish"){
        $idtr = $_GET['idtr'];
        $bookid = $_POST['returnbook1'];
        $returnDate = $_POST['return'];
        echo $returnDate;
        $returnBook = $dbConn -> prepare("UPDATE detailTransaksi SET tglKembali = '".$returnDate."', status = 1 WHERE idTransaksi = ".$idtr);
        if($returnBook -> execute()){
            $updateBook = $dbConn -> prepare("UPDATE buku SET qty=qty+1 WHERE idBuku=(SELECT buku.idBuku FROM buku, detailtransaksi, transaksi WHERE buku.idBuku = detailtransaksi.idBuku AND transaksi.idTransaksi = detailtransaksi.idTransaksi AND buku.idBuku = ".$bookid." AND transaksi.idTransaksi = ".$idtr.")");
            if($updateBook -> execute()){
                if (headers_sent()) {
                    die("<script> location.replace('transactionlist.php'); </script>");
                }
                else{
                    exit(header("Location: transactionlist.php"));
                }
            }
        }
    }
?>
<script>
    $(function(){
        $('[type="date"]').prop('max', function(){
            return new Date().toJSON().split('T')[0];
        });
    });
</script>
<?php if($action == "finish"){?>
<script>
    function CustomValidation(input) {
        this.invalidities = [];
    }
    CustomValidation.prototype = {
        addInvalidity: function(message) {
            this.invalidities.push(message);
        },  
        getInvalidities: function() {
            return this.invalidities.join('. \n');
        },
        checkValidity: function(input) {
            const diff = 24*60*60*1000;
            var d = document.getElementById('inputDate');
            const borrowed = new Date($('#inputDate').val());
            const returned = new Date($('#returnDate').val());
            if(input.value < d.value){
                var element = document.querySelector('#returnDate');
                element.classList.add("is-invalid");
                element.classList.remove("is-valid");
            } else {
                var element = document.querySelector('#returnDate');
                element.classList.add("is-valid");
                element.classList.remove("is-invalid");
                const datediff = Math.floor(Math.abs((borrowed-returned) / diff));
                var denda = 1000*(datediff - 3);
                if(denda > 0){
                    document.getElementById("penaltyField").value = denda;
                }else{
                    document.getElementById("penaltyField").value = 0;
                }
            }
        },
    };
    var returnDate = document.getElementById('returnDate');
    returnDate.CustomValidation = new CustomValidation();
    returnDate.addEventListener("change", function(){
        returnDate.CustomValidation.checkValidity(this);
    }) 
</script>
<?php }?>