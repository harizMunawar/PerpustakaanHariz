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
        $nis = $_GET['nis'];
        if($action == "edit"){
            $buttonText = "Edit";
        } else {
            $buttonText = "Delete";
        }
        $getData = $dbConn -> prepare("SELECT * FROM siswa WHERE nis = $nis");
        $getData -> execute();
        $data = $getData -> fetchAll();
        foreach($data as $data){

        }
    }
    if($action == "add"){
        $buttonText = "Add";
    }
    $page = $buttonText." Book";

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
                <li class="breadcrumb-item" aria-current="page"><?php echo "<a href='dashboard.php?role=$role&id=$userId'>Dashboard</a></li>";?>
                <li class="breadcrumb-item" aria-current="page"><a href="studentlist.php">Student List</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $buttonText?> Book</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container"> 
            <?php 
                if($action != 'add'){
                    echo "<form method='POST' action='studentform.php?action=".$action."&nis=".$nis."' enctype='multipart/form-data' id='form'>";
                    if($action == 'delete'){
                        echo "<fieldset disabled>";
                    }
                }else{
                    echo "<form method='POST' action='studentform.php?action=".$action."' enctype='multipart/form-data' id='form'>";
                }
            ?>                
                    <!-- Image Field -->
                    <div class="row h-100">
                        <div class="col-4">
                            <div class="card">
                                <img src="<?php if($action!='add')echo "../upload/student_avatar/".$data['image'];else echo "../assets/images/default.jpg";?>" <?php if($action!='add')echo "style=height:400px";?> class="card-img-top" id="imageCard" name="imagecard">
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
                            <!-- NIS And Name Field -->
                            <div class="row">
                                <!-- NIS Field -->
                                <div class="col col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label for="input NIS">NIS</label>
                                        <input required type="text" class="form-control" name="nis" id="inputNIS" placeholder='Enter NIS' value="<?php if($action!='add')echo $data['nis'];?>">                                        
                                    </div>
                                </div>
                                <!-- Name Field -->
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputName">Name</label>
                                        <input required type="text" class="form-control" name="name" id="input Name" placeholder='Enter Name' value="<?php if($action!='add')echo $data['nama'];?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Class Select -->
                            <div class="row">                                
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputTingkat">Tingkat</label>
                                        <select class="form-control" name="tingkat" id="inputTingkat">
                                            <?php 
                                                if($action!="add"){
                                                    if($data['tingkat']=="10"){
                                                        echo "<option selected value='10'>10</option>";
                                                    }else{
                                                        echo "<option value='10'>10</option>";
                                                    }
                                                    if($data['tingkat']=="11"){
                                                        echo "<option selected value='11'>11</option>";
                                                    }else{
                                                        echo "<option value='11'>11</option>";
                                                    }
                                                    if($data['tingkat']=="12"){
                                                        echo "<option selected value='12'>12</option>";
                                                    }else{
                                                        echo "<option value='12'>12</option>";
                                                    }
                                                }else{
                                                    echo "<option value='10'>10</option>";
                                                    echo "<option value='11'>11</option>";
                                                    echo "<option value='12'>12</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputJurusan">Jurusan</label>
                                        <select class="form-control" name="jurusan" id="inputJurusan">
                                            <?php 
                                                if($action != "add"){
                                                    if($data['jurusan'] == "RPL"){
                                                        echo "<option selected value='RPL'>RPL</option>";
                                                    }else{
                                                        echo "<option value='RPL'>RPL</option>";
                                                    }
                                                    if($data['jurusan'] == "SIJA"){
                                                        echo "<option selected value='SIJA'>SIJA</option>";
                                                    }else{
                                                        echo "<option value='SIJA'>SIJA</option>";   
                                                    }
                                                    if($data['jurusan'] == "EIND"){
                                                        echo "<option selected value='EIND'>EIND</option>";
                                                    }else{
                                                        echo "<option value='EIND'>EIND</option>";   
                                                    }
                                                }else{
                                                    echo "<option value='RPL'>RPL</option>";
                                                    echo "<option value='SIJA'>SIJA</option>";
                                                    echo "<option value='EIND'>EIND</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputKelas">Kelas</label>
                                        <select class="form-control" name="kelas" id="inputKelas">
                                            <?php 
                                                if($action != "add"){
                                                    if($data['kelas'] == "A"){
                                                        echo "<option selected value='A'>A</option>";
                                                    }else{
                                                        echo "<option value='A'>A</option>";
                                                    }
                                                    if($data['kelas'] == "B"){
                                                        echo "<option selected value='B'>B</option>";
                                                    }else{
                                                        echo "<option value='B'>B</option>";   
                                                    }
                                                    if($data['kelas'] == "C"){
                                                        echo "<option selected value='C'>C</option>";
                                                    }else{
                                                        echo "<option value='C'>C</option>";   
                                                    }
                                                }else{
                                                    echo "<option value='A'>A</option>";
                                                    echo "<option value='B'>B</option>";
                                                    echo "<option value='C'>C</option>";
                                                }
                                            ?>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Phone And Email Field -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputPhone">Phone</label>
                                        <input required type="tel" class="form-control" name="phone" id="inputPhone" placeholder="Enter Phone" value="<?php if($action!='add')echo $data['phone'];?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="inputEmail">Email</label>
                                        <input required type="email" class="form-control" name="email" id="inputEmail" placeholder="Enter Email" value="<?php if($action!='add')echo $data['email'];?>">
                                    </div>
                                </div>
                            </div>
                            <!-- Address Field -->
                            <div class="form-group">
                              <label for="inputAddress">Address</label>
                              <textarea class="form-control" name="address" id="inputAddress" rows="3"><?php if($action!="add") echo $data['alamat']?></textarea>
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
        try{        
            $action = $_GET['action'];
            $nis = $_POST['nis'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $jurusan = $_POST['jurusan'];
            $tingkat = $_POST['tingkat'];
            $kelas = $_POST['kelas'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            if(!empty($_FILES['image'])){
                $image_file = $_FILES['image']['name'];                
                $type = $_FILES['image']['type'];
                $size = $_FILES['image']['size'];
                $temp = $_FILES['image']['tmp_name'];    
                $path = "../upload/student_avatar/".$image_file;          

                if (!file_exists($path) || $action == "edit") {

                    if($action == "edit" && !file_exists($path)){
                        $oldImage = $_POST['oldImage'];
                        unlink("../upload/student_avatar/".$oldImage);
                    }
                    if ($size < 5000000) {                    
                        if ($action == "add"){
                            $sql = "INSERT INTO siswa values(:fnis,:fnama,:falamat,:fjurusan,:ftingkat,:fkelas,:fphone,:femail,:fimage)";                        
                        }
                        if ($action == "edit" && !file_exists($path)){
                            $sql = "UPDATE siswa SET nis=:fnis, nama=:fnama, alamat=:falamat, jurusan=:fjurusan, tingkat=:ftingkat, kelas=:fkelas, phone=:fphone, email=:femail, image=:fimage WHERE nis=".$_GET['nis'];
                        } else if ($action == "edit" && file_exists($path)){
                            $sql = "UPDATE siswa SET nis=:fnis, nama=:fnama, alamat=:falamat, jurusan=:fjurusan, tingkat=:ftingkat, kelas=:fkelas, phone=:fphone, email=:femail WHERE nis=".$_GET['nis'];
                        }                              
                        $query = $dbConn->prepare($sql);
                        $query ->bindParam(':fnis',$nis);     
                        $query ->bindParam(':fnama',$name);
                        $query ->bindParam(':falamat',$address);
                        $query ->bindParam(':fjurusan',$jurusan);
                        $query ->bindParam(':ftingkat',$tingkat);
                        $query ->bindParam(':fkelas',$kelas);       
                        $query ->bindParam(':fphone',$phone);
                        $query ->bindParam(':femail',$email);                        
                        if (!($action == "edit" && file_exists($path))){
                            $query ->bindparam(':fimage',$image_file);
                        }                                        
                        if ($query->execute()) {
                            move_uploaded_file($temp, "../upload/student_avatar/".$image_file);
                            if (headers_sent()) {
                                die("<script> location.replace('studentlist.php'); </script>");
                            }
                            else{
                                exit(header("Location: studentlist.php"));
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
        }catch (PDOEXCEPTION $e) {
            echo "<script> alert('That NIS Is Already Existed'); </script>";            
        }
    }
    if(isset($_POST['submit']) && $action == "delete"){
        $action = $_GET['action'];
        if($action == "delete") {
            $getImage = $dbConn ->prepare("SELECT image FROM siswa WHERE nis=".$_GET['nis']);
            $getImage ->execute();
            foreach($getImage->fetchAll() as $deletedRow){
                
            }
            $query = $dbConn->prepare("DELETE FROM siswa WHERE nis=".$_GET['nis']);
            if($query ->execute()){
                unlink("../upload/student_avatar/".$deletedRow['image']);
                if (headers_sent()) {
                    die("<script> location.replace('studentlist.php'); </script>");
                }
                else{
                    exit(header("Location: studentlist.php"));
                }
            }
        }
    }
?>