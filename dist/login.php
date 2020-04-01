<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Admin Login";
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
        <!--Form-->
        <div class="row m-0 p-0 mt-5">
            <div class="col-md-4 offset-md-4 mt-3">
                <form method="post" action="login.php">
                    <legend class="display-4 center mb-5">Login</legend>
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input required type="text" class="form-control" name="inputUsername" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input required type="password" class="form-control" name="inputPassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="inputRole">Select Your Role</label>
                        <select class="form-control" name="inputRole">
                            <option>Librarian</option>
                            <option>Admin</option>
                        </select>
                    </div>
                    <input required type="submit" class="btn btn-dark mt-2" name="submit" value="Submit">
                </form>
            </div>
        </div>
        <!--End Of Form-->
    </body>
</html>
<?php
    if (isset($_POST['submit'])) {
        $username = $_POST['inputUsername'];
        $password = $_POST['inputPassword'];
        $role = $_POST['inputRole'];

        $check=$dbConn->prepare("SELECT * FROM login WHERE username=:uname AND password=:upassword");
        $check->execute(array(':uname'=>$username, ':upassword'=>$password));
        $row=$check->fetch(PDO::FETCH_ASSOC);
        if($check->rowCount() > 0){
            if($role=='Admin'){
                header("Location: admin.php");
            }else{
                header("Location: librarian.php");
            }
        }else{
            echo "Gaada akun itu";
        }
    }
?>