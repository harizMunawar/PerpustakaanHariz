<?php
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require_once("config.php");
    require("../snippets/navbar.php");

    $userId = $_SESSION['id'];
    $role = $_SESSION['role'];
    $action = $_GET['action'];

    if($action != "add"){     
        $id = $_GET['bookid'];
        if($action == "edit"){
            $buttonText = "Edit";
        } else {
            $buttonText = "Delete";
        }
        $getData = $dbConn -> prepare("SELECT * FROM buku WHERE idBuku = $id");
        $getData -> execute();
        $data = $getData -> fetchAll();
        foreach ($data as $data){
            $title = $data['judul'];
            $writer = $data['penulis'];
            $stock = $data['qty'];
            $image = $data['image'];
            $synopsis = $data['sinopsis'];
        }
    }
    if($action == "add"){
        $buttonText = "Add";
    }
    $page = $buttonText." Book";

    $penerbit = $dbConn -> prepare("SELECT * FROM penerbit");
    $penerbit -> execute();

    $kategori = $dbConn -> prepare("SELECT * FROM kategori");
    $kategori -> execute();

    $countId = $dbConn -> prepare("SELECT MAX(idBuku) AS id FROM buku");
    $countId -> execute();
    foreach ($countId -> fetchAll() as $newIdrow){
        $newIdrow['id']++;
    }
    
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
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href='dashboard.php'>Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="booklist.php">Book List</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $buttonText?> Book</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container"> 
            <?php 
                if($action != 'add'){
                    echo "<form method='POST' action='bookform.php?action=".$action."&bookid=".$id."' enctype='multipart/form-data' id='form'>";
                    if($action == 'delete'){
                        echo "<fieldset disabled>";
                    }
                }else{
                    echo "<form method='POST' action='bookform.php?action=".$action."' enctype='multipart/form-data' id='form'>";
                }
            ?>
                <!-- Hidden Id Field -->
                <input hidden type="text" name="id" id="inputId" value="<?php if($action != "edit")echo $newIdrow['id'];else echo $id;?>">
                    <!-- Image Field -->
                    <div class="row h-100">
                        <div class="col-4">
                            <div class="card">
                                <img src="<?php if($action!='add')echo "../upload/book_cover/".$data['image'];else echo "../assets/images/default.jpg";?>" <?php if($action!='add')echo "style=height:400px";?> class="card-img-top" id="imageCard" name="imagecard">
                                <?php if($action=='edit'){?>
                                    <input hidden type="text" name="oldImage" value="<?php echo $data['image'];?>">
                                <?php }?>
                                <div class="card-body p-0">
                                    <div class="custom-file">
                                        <input <?php if($action=="add") echo"required";?> type="file" name="image" class="custom-file-input" id="customImage" onchange = "document.getElementById('imageCard').src = window.URL.createObjectURL(this.files[0]); document.getElementById('imageHelp').innerHTML = this.value;" aria-describedby="imageHelp">
                                        <label class="custom-file-label" for="customImage">Choose Picture</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small id="imageHelp" class="form-text text-muted hidden"><?php if($action=='add')echo "Please Select An Image";?></small>
                            </div>                                            
                        </div>
                        <div class="col">
                            <!-- Title And Stock Field -->
                            <div class="row">
                                <!-- Title Field -->
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputTitle">Book's Title</label>
                                        <input required type="text" class="form-control" name="title" id="inputTitle" placeholder='Enter Title' value="<?php if($action!='add')echo $data['judul'];?>">
                                    </div>
                                </div>
                                <!-- Stock Field -->
                                <div class="col col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label for="inputStock">Stock</label>
                                        <input required type="number" class="form-control" name="stock" id="inputStock" placeholder='Enter Stock' value="<?php if($action!='add')echo $data['qty'];?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Writer Field -->
                            <div class="form-group">
                                <label for="inputWriter">Book's Writer</label>
                                <input required type="text" class="form-control" name="writer" id="inputWriter" placeholder="Enter Writer's Name" value="<?php if($action!='add')echo $data['penulis'];?>">
                            </div>
                            <!-- Category And Publisher -->
                            <div class="row">
                                <!-- Category Field -->
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputId">Book's Category</label>
                                        <select class="custom-select" name="category">
                                            <?php                                        
                                                $dataKategori = $kategori->fetchAll();
                                                foreach ($dataKategori as $row) {
                                                    if($action != "add"){                                                        
                                                        if ($row['idKategori'] == $data['idKategori']){
                                                            $selected = "selected";                                                            
                                                        }else{
                                                            $selected = "";                                                            
                                                        }
                                                        echo "<option $selected value='".$row['idKategori']."'>".$row['kategoriBuku']."</option>";
                                                    } else{
                                                        echo "<option value='".$row['idKategori']."'>".$row['kategoriBuku']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Publisher Field -->
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputId">Book's Publisher</label>
                                        <select class="custom-select" name="publisher">
                                            <?php
                                                $dataPenerbit = $penerbit->fetchAll();
                                                foreach ($dataPenerbit as $row) {
                                                    if($action != "add"){                                                        
                                                        if ($row['idPenerbit'] == $data['idPenerbit']){
                                                            $selected = "selected";                                                            
                                                        }else{
                                                            $selected = "";                                                            
                                                        }
                                                        echo "<option $selected value='".$row['idPenerbit']."'>".$row['nama']."</option>";
                                                    } else{
                                                        echo "<option value='".$row['idPenerbit']."'>".$row['nama']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Synopsis Field -->
                            <div class="form-group">
                                <label for="inputSynopsis">Book's Synopsis</label>
                                <textarea required class="form-control" name="synopsis" id="inputSynopsis" rows="4"><?php if($action!='add')echo $data['sinopsis'];?></textarea>
                            </div>
                            <?php 
                                if($action == 'delete'){
                                    echo "</fieldset>";
                                    $buttonStyle = "danger";
                                }else{
                                    $buttonStyle = "primary";
                                }
                            ?>
                            <input required type="submit" class="btn btn-<?php echo $buttonStyle;?> right" name="submit" value=<?php echo $buttonText;?>>
                        </div>
                    </div>                    
            </form>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>

<?php
    if (isset($_POST['submit']) && $action != "delete") {
        $action = $_GET['action'];
        $id = $_POST['id'];
        $title = $_POST['title'];
        $writer = $_POST['writer'];
        $category = $_POST['category'];
        $publisher = $_POST['publisher'];
        $stock = $_POST['stock'];
        $synopsis = $_POST['synopsis'];

        if(!empty($_FILES['image'])){
            $image_file = $_FILES['image']['name'];                
            $type = $_FILES['image']['type'];
            $size = $_FILES['image']['size'];
            $temp = $_FILES['image']['tmp_name'];    
            $path = "../upload/book_cover/".$image_file;          

            if (!file_exists($path) || $action == "edit") {

                if($action == "edit" && !file_exists($path)){
                    $oldImage = $_POST['oldImage'];
                    unlink("../upload/book_cover/".$oldImage);
                }
                if ($size < 5000000) {                    
                    if ($action == "add"){
                        $sql = "INSERT INTO buku(idBuku, idKategori, judul, idPenerbit, penulis, qty, image, sinopsis) values(:fid,:fkategori,:fjudul,:fpenerbit,:fpenulis,:fqty,:fimage,:fsinopsis)";                        
                    }
                    if ($action == "edit" && !file_exists($path)){
                        $sql = "UPDATE buku SET idKategori=:fkategori, judul=:fjudul, idPenerbit=:fpenerbit, penulis=:fpenulis, qty=:fqty, image=:fimage, sinopsis=:fsinopsis WHERE idBuku=:fid";
                    } else if ($action == "edit" && file_exists($path)){
                        $sql = "UPDATE buku SET idKategori=:fkategori, judul=:fjudul, idPenerbit=:fpenerbit, penulis=:fpenulis, qty=:fqty, sinopsis=:fsinopsis WHERE idBuku=:fid";
                    }                              
                    $query = $dbConn->prepare($sql);
                    $query ->bindParam(':fid',$id);     
                    $query ->bindParam(':fkategori',$category);
                    $query ->bindParam(':fjudul',$title);
                    $query ->bindParam(':fpenerbit',$publisher);
                    $query ->bindParam(':fpenulis',$writer);
                    $query ->bindParam(':fqty',$stock);       
                    if (!($action == "edit" && file_exists($path))){
                        $query ->bindparam(':fimage',$image_file);
                    }                                        
                    $query ->bindParam(':fsinopsis',$synopsis);
                    if ($query->execute()) {
                        move_uploaded_file($temp, "../upload/book_cover/".$image_file);
                        if (headers_sent()) {
                            die("<script> location.replace('booklist.php'); </script>");
                        }
                        else{
                            exit(header("Location: booklist.php"));
                        }
                    }
                    else {
                        echo "<script> alert('There's An Error While Inserting Data. Please Try Again'); </script>";
                    }
                }
                else {
                    echo "<script> alert('Your Image File Size Is Too Large'); </script>";
                }
            }
            else {
                echo "<script> alert('Image File With That Name Is Already Existed'); </script>";
            }
        }
        else {
            echo "<script> alert('Please Select An Image'); </script>";
        }
    }
    if(isset($_POST['submit']) && $action == "delete"){
        $action = $_GET['action'];
        if($action == "delete") {
            $getImage = $dbConn ->prepare("SELECT image FROM buku WHERE idBuku=".$_GET['bookid']);
            $getImage ->execute();
            foreach($getImage->fetchAll() as $deletedRow){
                unlink("../upload/book_cover/".$deletedRow['image']);
            }
            $query = $dbConn->prepare("DELETE FROM buku WHERE idBuku=".$_GET['bookid']);
            if($query ->execute()){
                if (headers_sent()) {
                    die("<script> location.replace('booklist.php'); </script>");
                }
                else{
                    exit(header("Location: booklist.php"));
                }
            }
        }
    }
?>