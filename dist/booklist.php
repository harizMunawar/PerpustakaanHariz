<?php
    session_start();
    if(!isset($_SESSION['login'])){
        $_SESSION['login'] = FALSE;
    }else{
        if($_SESSION['login']){
            $role = $_SESSION['role'];
            $id = $_SESSION['id'];
        }
    }
    require_once("config.php");
    require("../snippets/navbar.php");
    $pageName = "Book List";

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 5;
    $limit_start = ($page - 1) * $limit;
    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
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
                <?php if ($_SESSION['login']) {?>
                    <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href='dashboard.php'>Dashboard</a></li>
                <?php } ?>
                <li class="breadcrumb-item active" aria-current="page">Book List</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!--Book's Table-->        
        <div class="container-fluid">
            <h1 class="display-3 text-dark mt-5">Available Book List</h1>
            <div class="row">
                <div class="col display-5 text-dark">Click the book for more detail</div>
                <?php if ($_SESSION['login']) { ?>
                    <div class="col">
                        <div class="right">
                            <a role='button' href='bookform.php?action=add' type="button" class="btn btn-success m-0">Add Book &nbsp;<i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        </div>
                    </div>
                <?php } ?>    
            </div>
            <table class="table table-responsive-md table-responsive-sm table-hover mt-4 h-100">
                <thead class="bg-gray text-light thead">
                    <tr>
                        <th scope="col" class='d-none d-md-table-cell'>Image</th>
                        <th scope="col">Title</th>
                        <th scope="col">Writer</th>
                        <th scope="col" class='d-none d-md-table-cell'>Publisher</th>
                        <th scope="col">Category</th>               
                        <th scope="col" class='d-none d-sm-table-cell'>Stock</th>
                        <th scope="col" class='d-none d-lg-table-cell'>Synopsis</th>  
                        <?php if($_SESSION['login']){?>                      
                        <th scope="col">Action</th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody>
                <?php                    
                    $sql = "SELECT idBuku, judul, penulis, idPenerbit, idKategori, qty, image, SUBSTRING(sinopsis, 1, 200) AS sinopsis FROM buku WHERE idBuku LIKE :param OR SUBSTRING(sinopsis, 1, 200) LIKE :param OR judul LIKE :param OR penulis LIKE :param OR idKategori = (SELECT idKategori FROM kategori WHERE kategoriBuku LIKE :param) OR idPenerbit = (SELECT idPenerbit FROM penerbit WHERE nama LIKE :param) ORDER BY judul LIMIT ".$limit_start.",".$limit;
                    $result = $dbConn -> prepare($sql);
                    $result->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                    $result -> execute();
                    $no = $limit_start + 1;
                    $total = $result -> rowCount();
                    while ($row = $result -> fetch(PDO::FETCH_ASSOC)){
                        echo "<tr class='items' data-href='detail.php?id=".$row['idBuku']."&type=book' border='0'>";   
                        echo "<th class='d-none d-md-table-cell' style='max-width: 200px;' name='idBuku'><img class='img-thumbnail img-fluid' src='../upload/book_cover/".$row['image']."'></th>";                     
                        echo "<td>".$row['judul']."</td>";
                        echo "<td>".$row['penulis']."</td>";
                        $selectForeign = "SELECT penerbit.nama, kategori.kategoriBuku FROM penerbit, kategori WHERE idPenerbit=".$row['idPenerbit']." AND idKategori=".$row['idKategori'];
                        foreach ($dbConn->query($selectForeign) as $rowforeign) {
                            echo "<td class='d-none d-md-table-cell'>".$rowforeign['nama']."</td>";
                            echo "<td>".$rowforeign['kategoriBuku']."</td>";
                        }
                        echo "<td class='d-none d-sm-table-cell'>".$row['qty']."</td>";
                        echo "<td style='word-wrap: break-word; width: 39%;' class='d-none d-lg-table-cell'>".$row['sinopsis']." ...</td>";
                        if($_SESSION['login']){                        
                        echo "  <td>  
                                    <a name='' id='' class='btn-sm btn-warning mr-1' href='bookform.php?action=edit&bookid=".$row['idBuku'] ."' role='button'>Edit</a><a name='' id='' class='btn-sm btn-danger' href='bookform.php?action=delete&bookid=".$row['idBuku'] ."' role='button'>Delete</a>
                                </td>";
                        }
                        echo "</tr>";                         
                        $no++; 
                    }
                ?>
                </tbody>   
            </table> 
        </div>
        <!--End Of Book's Table-->

        <!-- Pagination -->
        <footer class="footer">
            <div class="container">
                <div class="row container">
                    <div class="offset-5">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <?php
                                    $sql2 = $dbConn->prepare("SELECT COUNT(*) AS jumlah FROM buku WHERE idBuku LIKE :param OR judul LIKE :param OR penulis LIKE :param OR idKategori = (SELECT idKategori FROM kategori WHERE kategoriBuku LIKE :param) OR idPenerbit = (SELECT idPenerbit FROM penerbit WHERE nama LIKE :param)");
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
                                            echo "<li class='page-item'><a class='page-link' href='booklist.php?page=1&search=$searchword'>First</a></li>";
                                            echo "<li><a class='page-link' href='booklist.php?page=$link_prev&search=$searchword'>&laquo;</a></li>";
                                        }
                                        
                                        if ($page == $jumlah_page) {
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>&raquo;</a></li>";
                                            echo "<li class='disabled page-item'><a class='page-link' href='booklist.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
                                        } 
                                        else {
                                            $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                                            echo "<li><a class='page-link' href='booklist.php?page=$link_next&search=$searchword'>&raquo;</a></li>";
                                            echo "<li class='page-item'><a class='page-link' href='booklist.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
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
<script>
    $(document).ready(function() {
        $(document.body).on("click", "tr[data-href]", function () {
            window.location.href = this.dataset.href;
        });
    });
</script>