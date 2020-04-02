<?php
    class CNavigation {
        public static function GenerateMenu($pagetitle) {
            $html = "<nav class='navbar navbar-expand-md navbar-dark bg-gray mb-4'>
                        <div>
                            <a class='navbar-brand' href='../index.html'>
                                <img src='../assets/images/heavensdoor-icon.png' class='img-fluid'>
                            </a>
                        </div>
                        <div>
                            <div class='display-5 ml-2'>$pagetitle</div>
                        </div>
                        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#collapsibleNavId' aria-controls='collapsibleNavId'
                            aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>
                        <div class='collapse navbar-collapse' id='collapsibleNavId'>";
            if(basename($_SERVER['PHP_SELF']) == 'booklist.php') {
                $html .="   <form method='get' action='".basename($_SERVER['PHP_SELF'])."' class='form-inline ml-auto mt-md-0'>
                                <input class='form-control mr-sm-2' type='text' name='search' placeholder='Search'>
                                <button class='btn btn-outline-warning my-2 my-sm-0' type='submit'>Search</button>
                            </form>";
                }
            if(basename($_SERVER['PHP_SELF']) == 'admin.php' || basename($_SERVER['PHP_SELF']) == 'librarian.php') {
                if (basename($_SERVER['PHP_SELF']) == 'admin.php') {
                    $role = 'admin';
                }else {
                    $role = 'librarian';
                }
                $id = $_GET['id'];
                $html .="   <div class='ml-auto mt-md-0'>
                                <a role='button' href='profile.php?role=$role&id=$id' class='btn btn-outline-warning my-2 my-sm-0' type='submit'>Edit Profile</a>
                                <a role='button' href='login.php' class='btn btn-danger my-2 my-sm-0' type='submit'>Logout</a>
                            </div>";
            }                 
            $html .= "  </div>
                    </nav>\n";
            return $html;
        }
    };
?>