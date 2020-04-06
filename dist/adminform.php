<?php
    require_once("config.php");
    session_start();
    if (!$_SESSION['login']) {
        header("Location: login.php");
    }
    require("../snippets/navbar.php");    
    $role = $_SESSION['role'];
    $id = $_SESSION['id'];
    $action = $_GET['action'];
    $page="Administration Form";

    if($action != "add"){     
        $userid = $_GET['userid'];
        if($action == "edit"){
            $buttonText = "Edit";
        } else {
            $buttonText = "Delete";
        }
        $getData = $dbConn -> prepare("SELECT * FROM login, pustakawan WHERE pustakawan.idPustakawan = login.idPustakawan AND pustakawan.idPustakawan = $userid");
        $getData -> execute();
        $data = $getData -> fetchAll();
        foreach ($data as $data){
            
        }
    }

    $countId = $dbConn -> prepare("SELECT MAX(idPustakawan) AS id FROM login");
    $countId -> execute();
    $newId = $countId -> fetchAll();
    foreach ($newId as $newIdrow){
        $newIdrow['id']++;
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html");?>
    </head>
    <body>
        <!-- Navbar -->
        <?php echo CNavigation::GenerateMenu($page); ?>
        <!-- End Of Navbar -->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item" aria-current="page"><a class="text-capitalize" <?php echo "href='dashboard.php?role=$role&id=$id'"?>>Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="admin.php">Administrator List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Administrator Form</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="container">
            <?php if($action == "add"){?>
            <form action="adminform.php?action=<?php echo $action;?>" method="post" enctype="multipart/form-data" id="form">
            <?php }else{?>
            <?php   if($action=="delete")echo"<fieldset disabled>";?>
            <form action="adminform.php?action=<?php echo $action;?>&userid=<?php echo $userid?>" method="post" enctype="multipart/form-data" id="form">
            <?php }?>
                <!-- Hidden Id Field -->
                <input hidden type="text" name="id" id="inputId" value="<?php if($action != "edit")echo $newIdrow['id'];else echo $_GET['userid'];?>">
                <div class="row">
                    <div class="col-4">
                        <!-- Image Field -->
                        <div class="card">
                            <img src="<?php if($action!='add')echo "../upload/admin_avatar/".$data['image'];else echo "../assets/images/default.jpg";?>" <?php if($action!='add')echo "style=height:400px";?> class="card-img-top" id="imageCard" name="imagecard">
                            <?php if($action=='edit'){?>
                                <input hidden type="text" name="oldImage" value="<?php echo $data['image'];?>">
                            <?php }?>
                            <div class="card-body p-0">
                                <div class="custom-file">
                                    <input <?php if($action=="add") echo"required";?> type="file" name="imageUser" class="custom-file-input" id="customImage" onchange = "document.getElementById('imageCard').src = window.URL.createObjectURL(this.files[0]); document.getElementById('imageHelp').innerHTML = this.value;" aria-describedby="imageHelp">
                                    <label class="custom-file-label" for="customImage">Choose Picture</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small id="imageHelp" class="form-text text-muted hidden"><?php if($action=='add')echo "Please Select An Image";?></small>
                        </div>                                                
                    </div>
                    <div class="col">
                        <!-- Name And Username Field -->
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputName">User's Name</label>
                                    <input required type="text" class="form-control" name="name" id="inputName" placeholder='Enter Name' value="<?php if($action!='add')echo $data['nama'];?>">
                                </div>
                            </div>
                            <!-- Username Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputUsername">User's Username</label>
                                    <input required type="text" class="form-control" name="username" id="inputUsername" placeholder='Enter Username' value="<?php if($action!='add')echo $data['username'];?>">
                                    <small id="usernameHelp" class="form-text text-muted hidden d-none d-md-block">This Data Will Be Used As Login Requirement</small>
                                </div>
                            </div>
                        </div>

                        <!-- Password And Confirmation Field -->
                        <div class="row">
                            <!-- Password Field -->
                            <?php if($action =="delete") $type="text";else $type="password";?>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPassword">User's Password</label>
                                    <input required type="<?php echo $type?>" class="form-control" name="password" id="inputPassword" placeholder='Enter Password' value="<?php if($action!='add')echo $data['password'];?>">
                                    <small id="passwordHelp" class="form-text text-muted hidden d-none d-md-block">This Data Will Be Used As Login Requirement</small>
                                </div>
                            </div>
                            <?php if($action !="delete"){?>
                            <!-- Confirmation Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputConfirm">Confirmation</label>
                                    <input required type="password" class="form-control " name="confirm" id="inputConfirm" placeholder='Confirm Your Password'>
                                    <small id="passwordHelp" class="form-text text-muted hidden d-none d-md-block">Make Sure This Field Is Same As Password</small>
                                </div>
                            </div>
                            <?php }?>
                        </div>

                        <!-- Role Select And Email Field -->
                        <div class="row">
                            <!-- Role Select -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputRole">User's Role</label>
                                    <select class="custom-select" name="role">
                                        <?php
                                            if($data['hakUser'] == "Librarian"){
                                                echo "<option value='Admin'>Admin</option>";
                                                echo "<option selected value='Librarian'>Librarian</option>";
                                            }else{
                                                echo "<option selected value='Admin'>Admin</option>";
                                                echo "<option value='Librarian'>Librarian</option>";
                                            }                                       
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Email Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputEmail">User's Email</label>
                                    <input required type="email" class="form-control" name="email" id="inputEmail" placeholder='Enter Email' value="<?php if($action!='add')echo $data['email'];?>">
                                    <small id="usernameHelp" class="form-text text-muted hidden d-none d-md-block">&nbsp;</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phone Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhone">User's Phone</label>
                                    <input required type="tel" class="form-control" name="phone" id="inputPhone" placeholder='Enter Phone Number' value="<?php if($action!='add')echo $data['phone'];?>">
                                </div>
                            </div>
                            <!-- Address Field -->
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputAddress">User's Address</label>
                                    <textarea required class="form-control" name="address" id="inputAddress"><?php if($action!='add')echo $data['alamat'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <?php   if($action=="delete")echo"</fieldset>";?>
                <div class="row">
                    <div class="col-4">
                        <!-- Submit Button -->
                        <?php if($action == "delete") $submitstyle='danger';else $submitstyle='primary';?>
                        <input required type="submit" class="btn btn-<?php echo $submitstyle;?> text-capitalize" name="submit" value="<?php echo $action;?>">
                    </div>
                </div>
            </form>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>

<?php
    if(isset($_POST['submit']) && $action != "delete"){        
        if($_POST['password'] == $_POST['confirm']){ 
            try{
                $action = $_GET['action'];
                $id = $_POST['id'];
                $name = $_POST['name'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $role = $_POST['role'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                if(!empty($_FILES['imageUser'])){
                    $image_file = $_FILES['imageUser']['name'];                
                    $type = $_FILES['imageUser']['type'];
                    $size = $_FILES['imageUser']['size'];
                    $temp = $_FILES['imageUser']['tmp_name'];    
                    $path = "../upload/admin_avatar/".$image_file;
                    if (!file_exists($path) || $action == "edit") {
                        if($action == "edit" && !file_exists($path)){
                            $oldImage = $_POST['oldImage'];
                            unlink("../upload/admin_avatar/".$oldImage);
                        }
                        if ($size < 5000000) {                    
                            if ($action == "add"){
                                $sql = "INSERT INTO login values(:fid,:fusername,:fpassword,:frole)";       
                                $sql2 = "INSERT INTO pustakawan values(:fid,:fname,:faddress,:fphone,:femail,:fimage)";
                            }
                            if ($action == "edit" && !file_exists($path)){
                                $sql = "UPDATE login SET username=:fusername, password=:fpassword, hakUser=:frole WHERE idPustakawan=:fid";
                                $sql2 = "UPDATE pustakawan SET nama=:fname, alamat=:faddress, phone=:fphone, email=:femail, image=:fimage WHERE idPustakawan=:fid";
                            } else if ($action == "edit" && file_exists($path)){
                                $sql = "UPDATE login SET username=:fusername, password=:fpassword, hakUser=:frole WHERE idPustakawan=:fid";
                                $sql2 = "UPDATE pustakawan SET nama=:fname, alamat=:faddress, phone=:fphone, email=:femail WHERE idPustakawan=:fid";
                            }                                                                 
                            $query = $dbConn->prepare($sql);
                            $query ->bindParam(':fid',$id);     
                            $query ->bindParam(':fusername',$username);
                            $query ->bindParam(':fpassword',$password);
                            $query ->bindParam(':frole',$role);                                                            
                            if ($query->execute()) {
                                echo "query login execute";
                                $query2 = $dbConn->prepare($sql2);
                                $query2 ->bindParam(':fid',$id);     
                                $query2 ->bindParam(':fname',$name);
                                $query2 ->bindParam(':faddress',$address);
                                $query2 ->bindParam(':fphone',$phone);
                                $query2 ->bindParam(':femail',$email);
                                if (!($action == "edit" && file_exists($path))){
                                    $query2 ->bindparam(':fimage',$image_file);
                                }
                                if($query2->execute()){
                                    move_uploaded_file($temp, "../upload/admin_avatar/".$image_file);
                                    if (headers_sent()) {
                                        die("<script> location.replace('admin.php'); </script>");
                                    }
                                    else{
                                        exit(header("Location: admin.php"));
                                    }
                                }
                                else {
                                    echo "<script> alert('There's An Error While Inserting Data. Please Try Again'); </script>";
                                }                      
                            }
                            else {
                                echo "<script> alert('There's An Error While Inserting Data. Please Try Again'); </script>";
                            }
                        }
                    }
                    else {
                        echo "<script> alert('Image File With That Name Is Already Existed'); </script>";
                    }
                }
                else{
                    echo "<script> alert('Kosong Gambarnya'); </script>";
                }
            }
            catch(PDOEXCEPTION $e){
                echo $e->getMessage();
            }
        }
        else{
            echo "<script> alert('Your Password Didn't Match'); </script>";
        }
    }
    if(isset($_POST['submit']) && $action == "delete"){
        $action = $_GET['action'];
        if($action == "delete") {
            $getImage = $dbConn ->prepare("SELECT image FROM pustakawan WHERE idPustakawan=".$_GET['userid']);
            $getImage ->execute();
            foreach($getImage->fetchAll() as $deletedRow){
                unlink("../upload/admin_avatar/".$deletedRow['image']);
            }
            $query = $dbConn->prepare("DELETE FROM pustakawan WHERE idPustakawan=".$_GET['userid']);
            $query2 = $dbConn->prepare("DELETE FROM login WHERE idPustakawan=".$_GET['userid']);
            if($query ->execute()){
                if($query2 ->execute()){
                    if (headers_sent()) {
                        die("<script> location.replace('admin.php'); </script>");
                    }
                    else{
                        exit(header("Location: admin.php"));
                    }
                }
            }
        }
    }
?>

<script>    
    function CustomValidation(input) {
        this.invalidities = [];
    }

    CustomValidation.prototype = {
        addInvalidity: function(message) {
            this.invalidities.push(message);
        },  
        getInvalidities: function() {
            return this.invalidities.join('. \n');
        },
        checkValidity: function(input) {
            if(confirm.value == password.value){
                var element = document.querySelector('#inputConfirm');
                element.classList.add("is-valid");
                element.classList.remove("is-invalid");
            } else {
                var element = document.querySelector('#inputConfirm');
                element.classList.add("is-invalid");
                element.classList.remove("is-valid");
            }
        },
    };
    var confirm = document.getElementById('inputConfirm');
    var password = document.getElementById('inputPassword');
    confirm.CustomValidation = new CustomValidation();
    confirm.addEventListener("keyup", function(){
        confirm.CustomValidation.checkValidity(this);
    })
    password.CustomValidation = new CustomValidation();
    password.addEventListener("keyup", function(){
        password.CustomValidation.checkValidity(this);
    })
</script>