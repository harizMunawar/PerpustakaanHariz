<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Book List";
    $navitem = array(
        'index' => array('text'=>'Back', 'url'=>'../index.html'),
    );
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>

    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $navitem); ?>
        <!--End Of Navbar-->

        <!--Book's Table-->
        <div class="container-fluid">
            <h1 class="display-3 text-dark mt-5">Available Book List</h1>
            <div class="display-5 text-dark">Click the book for more detail</div>
            <table class="table table-hover mt-5">
                <thead class="thead-dark">
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
                    $searchword = "";
                    if(isset($_GET['search'])){
                        $searchword = $_GET['search'];
                    }
                    $sql = "SELECT * FROM buku WHERE idBuku LIKE :param OR judul LIKE :param OR penulis LIKE :param OR idKategori = (SELECT idKategori FROM kategori WHERE kategoriBuku LIKE :param) OR idPenerbit = (SELECT idPenerbit FROM penerbit WHERE nama LIKE :param)";
                    $result = $dbConn -> prepare($sql);
                    $result->bindValue(':param', '%'.$searchword.'%', PDO::PARAM_STR);
                    $result -> execute();
                    $total = $result -> rowCount();
                    while ($row = $result -> fetch(PDO::FETCH_ASSOC)){
                        echo "<tr data-href='detail.php?id=".$row['idBuku']."' border='0'>";
                        echo "<td name='idBuku' value=".$row['idBuku'].">".$row['idBuku']."</td>";
                        echo "<td>".$row['judul']."</td>";
                        echo "<td>".$row['penulis']."</td>";
                        $selectForeign = "SELECT penerbit.nama, kategori.kategoriBuku FROM penerbit, kategori WHERE idPenerbit=".$row['idPenerbit']." AND idKategori=".$row['idKategori'];
                        foreach ($dbConn->query($selectForeign) as $rowforeign) {
                            echo "<td>".$rowforeign['nama']."</td>";
                            echo "<td>".$rowforeign['kategoriBuku']."</td>";
                        }
                        echo "</tr>";  
                    }
                ?>
                </tbody>   
            </table>
        </div>
        <!--End Of Book's Table-->

        <script>
            $(document).ready(function() {
                $(document.body).on("click", "tr[data-href]", function () {
                    window.location.href = this.dataset.href;
                });
            });
        </script>
    </body>
</html>