<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Book List";
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
                <li class="breadcrumb-item active" aria-current="page">Book List</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!--Book's Table-->
        <div class="container-fluid">
            <h1 class="display-3 text-dark mt-5">Available Book List</h1>
            <div class="display-5 text-dark">Click the book for more detail</div>
            <table class="table table-hover mt-4">
                <thead class="bg-gray text-light thead">
                    <tr>
                        <th scope="col">Id Buku</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Penulis</th>
                        <th scope="col">Penerbit</th>
                        <th scope="col">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                    $limit = 5;
                    $limit_start = ($page - 1) * $limit;
                    $searchword = "";
                    if(isset($_GET['search'])){
                        $searchword = $_GET['search'];
                    }
                    $sql = "SELECT * FROM buku WHERE idBuku LIKE :param OR judul LIKE :param OR penulis LIKE :param OR idKategori = (SELECT idKategori FROM kategori WHERE kategoriBuku LIKE :param) OR idPenerbit = (SELECT idPenerbit FROM penerbit WHERE nama LIKE :param) LIMIT ".$limit_start.",".$limit;
                    $result = $dbConn -> prepare($sql);
                    $result->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                    $result -> execute();
                    $no = $limit_start + 1;
                    $total = $result -> rowCount();
                    while ($row = $result -> fetch(PDO::FETCH_ASSOC)){
                        echo "<tr class='items' data-href='detail.php?id=".$row['idBuku']."' border='0'>";
                        echo "<td name='idBuku' value=".$row['idBuku'].">".$row['idBuku']."</td>";
                        echo "<td>".$row['judul']."</td>";
                        echo "<td>".$row['penulis']."</td>";
                        $selectForeign = "SELECT penerbit.nama, kategori.kategoriBuku FROM penerbit, kategori WHERE idPenerbit=".$row['idPenerbit']." AND idKategori=".$row['idKategori'];
                        foreach ($dbConn->query($selectForeign) as $rowforeign) {
                            echo "<td>".$rowforeign['nama']."</td>";
                            echo "<td>".$rowforeign['kategoriBuku']."</td>";
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

                                    if ($page == 1) {
                                        echo "<li class='disabled page-item'><a class='page-link' href='booklist.php?page=1&search=$searchword'>First</a></li>";
                                        echo "<li class='disabled page-item'><a class='page-link' href='booklist.php?page=1&search=$searchword'>&laquo;</a></li>";
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
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </footer>
        <!--End Of Pagination-->

        <script>
            $(document).ready(function() {
                $(document.body).on("click", "tr[data-href]", function () {
                    window.location.href = this.dataset.href;
                });
            });
        </script>
    </body>
</html>