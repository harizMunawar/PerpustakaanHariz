<?php
    class CNavigation {
        public static function GenerateMenu($pagetitle, $items) {
            $html = "<nav class='navbar navbar-expand-md navbar-dark bg-dark mb-4'>
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
                        <div class='collapse navbar-collapse' id='collapsibleNavId'>
                            <ul class='navbar-nav ml-auto'>";
                                foreach($items as $item) {
                                        $html .= "<li class='nav-item active'><a class='nav-link active' href='{$item['url']}'>{$item['text']}</a></li>\n";
                                        // $html .= "<li class='nav-item'><a class='nav-link' href='{$item['url']}'>{$item['text']}</a></li>\n";      
                                }
            $html .= "      </ul>";
            if(basename($_SERVER['PHP_SELF']) == 'booklist.php') {
                $html .="   <form method='get' action='".basename($_SERVER['PHP_SELF'])."' class='form-inline mt-2 mt-md-0'>
                                <input class='form-control mr-sm-2' type='text' name='search' placeholder='Search'>
                                <button class='btn btn-outline-warning my-2 my-sm-0' type='submit'>Search</button>
                            </form>";
            }                   
            $html .= "  </div>
                    </nav>\n";
            return $html;
        }
    };
?>