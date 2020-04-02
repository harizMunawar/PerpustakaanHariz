<?php
    require_once("config.php");
    require("../snippets/navbar.php");
    $page = "Admin Site";
    $sql = "SELECT * FROM pustakawan WHERE pustakawan.idPustakawan=".$_GET['id'];
    $result = $dbConn -> prepare($sql);
    $result -> execute();
    $admin=$result->fetch(PDO::FETCH_ASSOC);
    $id = $admin['idPustakawan'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo file_get_contents("../snippets/header.html"); ?>
    </head>
    <body>
        <!--Navbar-->
        <?php echo CNavigation::GenerateMenu($page, $id); ?>
        <!--End Of Navbar-->

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="login.php">Login</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <!-- End Of Breadcrumbs -->

        <!-- Main Content -->
        <div class="pricing-header px-3 py-3 pt-md-3 pb-md-3 mx-auto text-center">
        <h1 class="display-4">Hello, <?php echo $admin['nama'];?></h1>
        <p class="lead">Welcome To Admin Dashboard<br>Please Select One Of The Available Action Below</p>
        </div>

        <div class="container-fluid">
            <div class="card-deck text-center">

                <div class="card box-shadow data-href='../index.html'">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Books</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$15 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                            <li>10 users included</li>
                            <li>2 GB of storage</li>
                            <li>Email support</li>
                            <li>Help center access</li>
                        </ul>                        
                    </div>
                </div>

                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Librarian</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$15 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>20 users included</li>
                        <li>10 GB of storage</li>
                        <li>Priority email support</li>
                        <li>Help center access</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Publisher</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$29 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Student</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$29 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

                <div class="card box-shadow">
                    <div class="card-header bg-gray text-white">
                        <a href="login.php" class="text-white display-5 my-0 font-weight-normal stretched-link">Manage Report</a>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$29 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-2 mb-2">
                        <li>30 users included</li>
                        <li>15 GB of storage</li>
                        <li>Phone and email support</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Of Main Content -->
    </body>
</html>

<script>
    $(document).ready(function() {
        $(document.body).on("click", "div[data-href]", function () {
            window.location.href = this.dataset.href;
        });
    });
</script>