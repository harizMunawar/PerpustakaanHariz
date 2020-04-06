<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require("../snippets/navbar.php");
    $pageName = "Publisher List";
    $sql = "SELECT * FROM pustakawan WHERE pustakawan.idPustakawan=".$_SESSION['id'];
    $result = $dbConn -> prepare($sql);
    $result -> execute();
    $librarian=$result->fetch(PDO::FETCH_ASSOC);
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 3;
    $limit_start = ($page - 1) * $limit;
    $no = $limit_start + 1;
    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
    }    
    $getPublisher = $dbConn -> prepare("SELECT * FROM penerbit WHERE nama LIKE :param OR alamat LIKE :param OR phone LIKE :param OR email LIKE :param LIMIT ".$limit_start.",".$limit);
    $getPublisher->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
    $getPublisher -> execute();

    $getNewId = $dbConn -> prepare("SELECT MAX(idPenerbit) AS id FROM penerbit");
    $getNewId -> execute();
    foreach($getNewId->fetchAll() as $totalRow){
        $newId = $totalRow['id'] + 1;
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
                <li class="breadcrumb-item active" aria-current="page">Publisher List</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">            
            <div class="display-4 mt-3 ml-3 mb-3 text-dark bold">
                Publisher List
                <div class='right'>
                    <a role='button' type="button" class="btn btn-success m-0 text-white" data-toggle='modal' data-target='#publisherAddForm'>
                        Add Publisher &nbsp;<i class="fa fa-plus-square" aria-hidden="true"></i>
                    </a>
                </div>
            </div>            
            <?php                             
                $total = $getPublisher -> rowCount();
                foreach($getPublisher -> fetchAll() as $dataPublisher){
                    echo "<div class='card bg-light text-dark mb-2 border-0 font-product'>";
                    echo    "<div class='card-body'>";
                    echo        "<div style='transform: rotate(0);'>";
                    echo            "<p class='right text-muted'>".$dataPublisher['email']." </p>";
                    echo            "<h5 class='card-title bold'>".$dataPublisher['nama']."</h5>";
                    echo            "<p class='card-text'>".$dataPublisher['alamat']."<br>".$dataPublisher['phone']."</p>";                         
                    echo        "</div>";
                    echo        "<div class='mt-n4 sticky-top'>";
                    echo            "<a type='button' class='btn-sm btn-danger right text-white ml-1' data-toggle='modal' data-target='#publisherDeleteForm' data-publisherid='".$dataPublisher['idPenerbit']."' data-publishername='".$dataPublisher['nama']."' data-publisheraddress='".$dataPublisher['alamat']."' data-publisherphone='".$dataPublisher['phone']."' data-publisheremail='".$dataPublisher['email']."'>Delete</a>";
                    echo            "<a type='button' class='btn-sm btn-warning right' data-toggle='modal' data-target='#publisherEditForm' data-publisherid='".$dataPublisher['idPenerbit']."' data-publishername='".$dataPublisher['nama']."' data-publisheraddress='".$dataPublisher['alamat']."' data-publisherphone='".$dataPublisher['phone']."' data-publisheremail='".$dataPublisher['email']."'>Edit</a>";
                    echo        "</div>";
                    echo    "</div>";                    
                    echo "</div>";
                    $no++;
                }
            ?>
        </div>
        <!-- End Of Main Content -->
        
        <!-- Add Modal Form -->
        <div class="modal fade" id="publisherAddForm" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">Publisher Add Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="publisher.php" method="POST">
                            <div class="modal-body">   
                                <input hidden type="text" class="form-control" name="addId" id="inputId" value="<?php echo $newId?>">
                                <div class="form-group">
                                    <label for="inputName">Publisher's Name</label>
                                    <input required type="text" class="form-control" name="addName">
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress">Publisher's Address</label>
                                    <input required type="text" class="form-control" name="addAddress">
                                </div>
                                <div class="form-group">
                                    <label for="inputPhone">Publisher's Phone Number</label>
                                    <input required type="tel" class="form-control" name="addPhone">
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">Publisher's Email Address</label>
                                    <input required type="email" class="form-control" name="addEmail">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" name="addSubmit">Submit</button>
                            </div>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        <!-- End Of Add Modal Form -->

        <!-- Delete Modal Form -->
        <div class="modal fade" id="publisherDeleteForm" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">Publisher Delete Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" id="deleteForm">
                            <input hidden type="text" class="form-control" name="deleteId" id="id">
                            <fieldset disabled="disabled">
                                <div class="modal-body">                                       
                                    <div class="form-group">
                                        <label for="inputName">Publisher's Name</label>
                                        <input required type="text" class="form-control" name="deleteName" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress">Publisher's Address</label>
                                        <input required type="text" class="form-control" name="deleteAddress" id="address">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPhone">Publisher's Phone Number</label>
                                        <input required type="tel" class="form-control" name="deletePhone" id="phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail">Publisher's Email Address</label>
                                        <input required type="email" class="form-control" name="deleteEmail" id="email">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="modal-footer">
                                <button class="btn btn-danger text-white" name="deleteSubmit">Delete</button>
                            </div>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        <!-- End Of Delete Modal Form -->
        
        <!-- Edit Modal Form -->
        <div class="modal fade" id="publisherEditForm" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="container-fluid">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class=" text-capitalize modal-title" id="modalTitle">Publisher Edit Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" id="deleteForm">
                            <input hidden type="text" class="form-control" name="editId" id="editid">
                                <div class="modal-body">                                       
                                    <div class="form-group">
                                        <label for="inputName">Publisher's Name</label>
                                        <input required type="text" class="form-control" name="editName" id="editname">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress">Publisher's Address</label>
                                        <input required type="text" class="form-control" name="editAddress" id="editaddress">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPhone">Publisher's Phone Number</label>
                                        <input required type="tel" class="form-control" name="editPhone" id="editphone">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail">Publisher's Email Address</label>
                                        <input required type="email" class="form-control" name="editEmail" id="editemail">
                                    </div>
                                </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" name="editSubmit">Edit</button>
                            </div>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
        <!-- Edit Of Add Modal Form -->

        <!-- Pagination -->
        <footer class="footer">
            <div class="container">
                <div class="row container">             
                    <div class="offset-5">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <?php
                                    $sql2 = $dbConn->prepare("SELECT COUNT(*) AS jumlah FROM penerbit WHERE nama LIKE :param OR alamat LIKE :param OR phone LIKE :param OR email LIKE :param ORDER BY nama");
                                    $sql2->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                                    $sql2->execute();
                                    $get_jumlah = $sql2->fetch();
                                    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit);                            
                                    if($total == 0){
                                        echo "<li class='disabled page-item'><a class='page-link' href='#'>First</a></li>";
                                        echo "<li class='disabled page-item'><a class='page-link' href='#'>&laquo;</a></li>";
                                        echo "<li class='disabled page-item'><a class='page-link' href='#'>&raquo;</a></li>";
                                        echo "<li class='disabled page-item'><a class='page-link' href='#'>Last</a></li>";
                                    } else {
                                        if ($page == 1 && $total != 0) {
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>First</a></li>";
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>&laquo;</a></li>";
                                        }
                                        else {
                                            $link_prev = ($page > 1) ? $page - 1 : 1;
                                            echo "<li class='page-item'><a class='page-link' href='publisher.php?page=1&search=$searchword'>First</a></li>";
                                            echo "<li><a class='page-link' href='publisher.php?page=$link_prev&search=$searchword'>&laquo;</a></li>";
                                        }
                                        if ($page == $jumlah_page) {
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>&raquo;</a></li>";
                                            echo "<li class='disabled page-item'><a class='page-link' href='publisher.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
                                        } 
                                        else {
                                            $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                                            echo "<li><a class='page-link' href='publisher.php?page=$link_next&search=$searchword'>&raquo;</a></li>";
                                            echo "<li class='page-item'><a class='page-link' href='publisher.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
                                        }
                                    }
                                ?>
                            </ul>
                        </nav>
                    </div>                
                </div>
            </div>
        </footer>
        <!--End Of Pagination-->

    </body>
</html>

<?php
    
    if(isset($_POST['addSubmit'])){
        $add = $dbConn -> prepare("INSERT INTO penerbit VALUES(:fid, :fnama, :falamat, :fphone, :femail)");
        $add -> bindParam(':fid', $_POST['addId']);
        $add -> bindParam(':fnama', $_POST['addName']);
        $add -> bindParam(':falamat', $_POST['addAddress']);
        $add -> bindParam(':fphone', $_POST['addPhone']);
        $add -> bindParam(':femail', $_POST['addEmail']);
        if($add ->execute()){
            echo "<script>alert('Publisher Has Been Successfully Added');</script>";
            echo "<script>location.replace('publisher.php');</script>";
        }       
    }
    if(isset($_POST['deleteSubmit'])){        
        $idPublisher = $_POST['deleteId'];
        $delete = $dbConn -> prepare("DELETE FROM penerbit WHERE idPenerbit = ".$idPublisher);                    
        if($delete ->execute()){
            echo "<script>alert('Publisher Has Been Successfully Deleted');</script>";
            echo "<script>location.replace('publisher.php');</script>";
        }
    }
    if(isset($_POST['editSubmit'])){
        $add = $dbConn -> prepare("UPDATE penerbit SET nama=:fnama, alamat=:falamat, phone=:fphone, email=:femail WHERE idPenerbit=:fid");
        $add -> bindParam(':fid', $_POST['editId']);
        $add -> bindParam(':fnama', $_POST['editName']);
        $add -> bindParam(':falamat', $_POST['editAddress']);
        $add -> bindParam(':fphone', $_POST['editPhone']);
        $add -> bindParam(':femail', $_POST['editEmail']);
        if($add ->execute()){
            echo "<script>alert('Publisher Has Been Successfully Edited');</script>";
            echo "<script>location.replace('publisher.php');</script>";
        }       
    }
?>

<script>
    $('#publisherDeleteForm').on('show.bs.modal', function(e) {
        document.getElementById('id').value = e.relatedTarget.dataset.publisherid;
        document.getElementById('name').value = e.relatedTarget.dataset.publishername;
        document.getElementById('address').value = e.relatedTarget.dataset.publisheraddress;
        document.getElementById('phone').value = e.relatedTarget.dataset.publisherphone;
        document.getElementById('email').value = e.relatedTarget.dataset.publisheremail;
    });

    $('#publisherEditForm').on('show.bs.modal', function(e) {
        document.getElementById('editid').value = e.relatedTarget.dataset.publisherid;
        document.getElementById('editname').value = e.relatedTarget.dataset.publishername;
        document.getElementById('editaddress').value = e.relatedTarget.dataset.publisheraddress;
        document.getElementById('editphone').value = e.relatedTarget.dataset.publisherphone;
        document.getElementById('editemail').value = e.relatedTarget.dataset.publisheremail;
    });
</script>