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

        <!--Form-->
        <div class="row m-0 p-0 mt-5">
            <div class="col-md-4 offset-md-4 mt-3">
                <form>
                    <legend class="display-4 center mb-5">Login</legend>
                    <div class="form-group">
                        <label for="inputUsername">Email address</label>
                        <input type="email" class="form-control" id="inputUsername" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="inputRole">Select Your Role</label>
                        <select class="form-control" id="inputRole">
                            <option>Librarian</option>
                            <option>Admin</option>
                        </select>
                    </div>
                    <input required type="submit" class="btn btn-dark mt-2" value="Submit">
                </form>
            </div>
        </div>
        <!--End Of Form-->

    </body>
</html>