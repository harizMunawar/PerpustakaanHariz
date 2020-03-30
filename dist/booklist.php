<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
        <title>Heaven's Door</title>
    </head>
    <body>
        <!--Navbar-->
        <?php echo file_get_contents("../snippets/navbar.html"); ?>
        <!--End Of Navbar-->
        <!--Book's Table-->
        <div class="container">
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
                    <tr>
                        <th scope="row">1</th>
                        <td>Phantom Blood</td>
                        <td>Hirohiko Araki</td>
                        <td>Shonen Jump</td>
                        <td>Part 1</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                    </tr>
                </tbody>   
            </table>
        </div>
        <!--End Of Book's Table-->
    </body>
</html>