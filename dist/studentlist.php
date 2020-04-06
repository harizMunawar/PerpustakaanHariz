<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require("../snippets/navbar.php");
    $pageName = "Student List";
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 5;
    $limit_start = ($page - 1) * $limit;
    $no = $limit_start + 1;
    $searchword = "";
    if(isset($_GET['search'])){
        $searchword = $_GET['search'];
    }
    $getStudent = $dbConn -> prepare("SELECT * FROM siswa WHERE nis LIKE :param OR nama LIKE :param OR alamat LIKE :param OR jurusan LIKE :param OR tingkat LIKE :param OR kelas LIKE :param OR phone LIKE :param OR email LIKE :param ORDER BY nama LIMIT ".$limit_start.",".$limit);
    $getStudent->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
    $getStudent -> execute();
    $total = $getStudent -> rowCount();
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
                <li class="breadcrumb-item" aria-current="page"><a class="text-capitalize" <?php echo "href='dashboard.php?role=$role&id=$id'"?>>Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student List</li>                
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container-fluid">            
            <div class="display-4 mt-3 ml-3 mb-3 text-dark bold">
                Student List
                <div class='right'>
                    <a role='button' type="button" class="btn btn-success m-0 text-white" href="studentform.php?action=add">
                        Add Student &nbsp;<i class="fa fa-plus-square" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <table class="table table-responsive-md table-responsive-sm table-hover mt-4 h-100">
                <thead class="bg-gray text-light thead">
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell">Image</th>
                        <th scope="col">Nis</th>
                        <th scope="col">Name</th>
                        <th scope="col">Class</th>  
                        <th scope="col">Address</th>
                        <th scope="col" class='d-none d-lg-table-cell'>Phone</th>
                        <th scope="col" class='d-none d-lg-table-cell'>Email</th>                        
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php             
                foreach($getStudent -> fetchAll() as $dataStudent){
                    echo "<tr border='0'>";
                    echo "  <td style='max-width: 90px;' class='d-none d-md-table-cell'><img class='img-thumbnail img-fluid' src='../upload/student_avatar/".$dataStudent['image']."'></td>";
                    echo "  <td>".$dataStudent['nis']."</td>";
                    echo "  <td>".$dataStudent['nama']."</td>";
                    echo "  <td>".$dataStudent['tingkat']." ".$dataStudent['jurusan']." ".$dataStudent['kelas']."</td>";
                    echo "  <td>".$dataStudent['alamat']."</td>";
                    echo "  <td class='d-none d-lg-table-cell'>".$dataStudent['phone']."</td>";
                    echo "  <td class='d-none d-lg-table-cell'>".$dataStudent['email']."</td>";                    
                    echo "  <td><a name='' id='' class='btn-sm btn-warning mr-1' href='studentform.php?action=edit&nis=".$dataStudent['nis'] ."' role='button'>Edit</a><a name='' id='' class='btn-sm btn-danger' href='studentform.php?action=delete&nis=".$dataStudent['nis'] ."' role='button'>Delete</a></td>";
                    echo "</tr>";
                    $no++;
                }
                ?>                
                </tbody>
            </table>
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
                                    $sql2 = $dbConn->prepare("SELECT COUNT(*) AS jumlah FROM siswa WHERE nis LIKE :param OR nama LIKE :param OR alamat LIKE :param OR jurusan LIKE :param OR tingkat LIKE :param OR kelas LIKE :param OR phone LIKE :param OR email LIKE :param");
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
                                            echo "<li class='page-item'><a class='page-link' href='studentlist.php?page=1&search=$searchword'>First</a></li>";
                                            echo "<li><a class='page-link' href='studentlist.php?page=$link_prev&search=$searchword'>&laquo;</a></li>";
                                        }
                                        if ($page == $jumlah_page) {
                                            echo "<li class='disabled page-item'><a class='page-link' href='#'>&raquo;</a></li>";
                                            echo "<li class='disabled page-item'><a class='page-link' href='studentlist.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
                                        } 
                                        else {
                                            $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                                            echo "<li><a class='page-link' href='studentlist.php?page=$link_next&search=$searchword'>&raquo;</a></li>";
                                            echo "<li class='page-item'><a class='page-link' href='studentlist.php?page=$jumlah_page&search=$searchword'>Last</a></li>";
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