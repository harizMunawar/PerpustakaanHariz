<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login'] || $_SESSION['role'] == "Librarian") {
        header("Location: login.php");
    }
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];
    require("../snippets/navbar.php");
    $pageName = "Administrator List";    

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 1;
    $limit_start = ($page - 1) * $limit;
    $no = $limit_start + 1;
    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
    }
    $getAdminData = $dbConn -> prepare("SELECT * FROM login l, pustakawan p WHERE l.idPustakawan = p.idPustakawan AND (l.username LIKE :param OR l.hakUser LIKE :param OR p.nama LIKE :param OR p.alamat LIKE :param OR p.phone LIKE :param OR p.email LIKE :param) ORDER BY nama LIMIT ".$limit_start.",".$limit);
    $getAdminData->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
    $getAdminData -> execute();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
        <title>Heaven's Door</title>
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
                <li class="breadcrumb-item" aria-current="page"><a class="text-capitalize" <?php echo "href='dashboard.php?role=$role&id=$id'"?>>Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Administrator List</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">            
            <div class="display-4 mt-3 ml-3 mb-3 text-dark bold">
                Administrator List
                <div class='right'>
                    <a href="adminform.php?action=add" role='button' type="button" class="btn btn-success btn-sm m-0 text-white">
                        Add Admin &nbsp;<i class="fa fa-plus-square" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <?php
                $total = $getAdminData -> rowCount();
                foreach($getAdminData -> fetchAll() as $dataAdmin){
                    echo "<div class='card bg-light text-dark mb-2 border-0 font-product'>";
                    echo    "<div class='card-body'>";
                    echo        "<div>";
                    echo            "<p class='right text-muted'>".$dataAdmin['email']."</p>";
                    echo            "<h5 class='card-title bold'>".$dataAdmin['nama']." &nbsp;(".$dataAdmin['hakUser'].")</h5>";
                    echo            "<p class='card-text'>".$dataAdmin['username']."<br>".$dataAdmin['alamat']."<br>".$dataAdmin['phone']."<br></p>";
                    echo            "<img style='max-height: 220px;' class='img-thumbnail img-fluid' src='../upload/admin_avatar/".$dataAdmin['image']."'>";
                    echo        "</div>";
                    echo        "<div class='mt-n4 sticky-top'>";
                    echo            "<a href='adminform.php?action=delete&userid=".$dataAdmin['idPustakawan']."' type='button' class='btn-sm btn-danger right text-white ml-1'>Delete</a>";
                    echo            "<a href='adminform.php?action=edit&userid=".$dataAdmin['idPustakawan']."' type='button' class='btn-sm btn-warning right'>Edit</a>";
                    echo        "</div>";
                    echo    "</div>";                    
                    echo "</div>";
                    $no++;
                }
            ?>
        </div>   
        <!-- End Of Main Content -->
        
        <!-- Pagination -->
        <footer class="footer">
            <div class="container">
                <div class="row container">             
                    <div class="offset-5">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <?php
                                    $sql2 = $dbConn->prepare("SELECT COUNT(l.idPustakawan) AS jumlah FROM login l, pustakawan p WHERE l.idPustakawan = p.idPustakawan AND (l.username LIKE :param OR l.hakUser LIKE :param OR p.nama LIKE :param OR p.alamat LIKE :param OR p.phone LIKE :param OR p.email LIKE :param)");
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
                                            echo "<li class='page-item'><a class='page-link' href='admin.php?page=1&search=$searchword'>First</a></li>";
                                            echo "<li><a class='page-link' href='admin.php?page=$link_prev&search=$searchword'>&laquo;</a></li>";
                                        }
                                        if ($page == $jumlah_page) {
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>&raquo;</a></li>";
                                            echo "<li class='disabled page-item'><a class='page-link' href='admin.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
                                        } 
                                        else {
                                            $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                                            echo "<li><a class='page-link' href='admin.php?page=$link_next&search=$searchword'>&raquo;</a></li>";
                                            echo "<li class='page-item'><a class='page-link' href='admin.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
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