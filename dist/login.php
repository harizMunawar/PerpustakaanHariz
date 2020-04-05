<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Login";
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
                <li class="breadcrumb-item active" aria-current="page">Login</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!--Form-->
        <div class="row m-0 p-0 mt-3">
            <div class="col-md-4 offset-md-4 mt-2">
                <form class="bg-light p-5" method="POST" id="loginForm">
                    <legend class="display-4 center mb-2">Login</legend>
                    <?php
                        if (isset($_POST['submit'])){
                            echo "  <div class='alert alert-danger' role='alert'>
                                        Wrong Username or Password!
                                    </div>";
                        }
                    ?>
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input required type="text" class="form-control" name="username" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input required type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <input required type="submit" class="btn btn-dark mt-2" name="submit" value="Submit">
                    <div class="mt-2"><a class="" href="../index.html">Back To Home</a></div>
                </form>
            </div>
        </div>
        <!--End Of Form-->
    </body>
</html>

<?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $check=$dbConn->prepare("SELECT * FROM login WHERE username=:uname AND password=:upassword");
        $check->execute(array(':uname'=>$username, ':upassword'=>$password));
        $row=$check->fetch(PDO::FETCH_ASSOC);
        if($check->rowCount() > 0){
            session_start();
            $_SESSION["role"] = $row['hakUser'];
            $_SESSION["id"] = $row['idPustakawan'];
            $_SESSION["login"] = TRUE;
            header("Location: dashboard.php");
        }
    }
?>