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
        <title>Heaven's Door</title>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $navitem); ?>
        <!--End Of Navbar-->
        <!--Book's Table-->
        <div class="container-fluid">
            <h1 class="display-3 text-dark mt-5">Available Book List</h1>
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
                    $sql = "SELECT * FROM buku";
                    $result = $dbConn -> prepare($sql);
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
                        echo "<input hidden type='submit' name='submit' value='View Detail'>";
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