<?php
    class CNavigation {
        public static function GenerateMenu($pagetitle, $items) {
            $html = "<nav class=\"navbar navbar-expand-sm navbar-dark bg-dark\">
                        <div>
                            <a href=\"../index.html\">
                                <img src=\"../assets/images/heavensdoor-icon.png\" class=\"img-fluid\">
                            </a>
                        </div>
                        <div>
                            <div class=\"display-5 ml-2\">$pagetitle</div>
                        </div>
                        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapsibleNavId\" aria-controls=\"collapsibleNavId\"
                            aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                            <span class=\"navbar-toggler-icon\"></span>
                        </button>
                        
                        <div class=\"collapse navbar-collapse\" id=\"collapsibleNavId\">
                            <ul class=\"navbar-nav ml-auto\">";
                                foreach($items as $item) {
                                        $html .= "<li class='nav-item active'><a class=\"nav-link active\" href='{$item['url']}'>{$item['text']}</a></li>\n";
                                        // $html .= "<li class='nav-item'><a class=\"nav-link\" href='{$item['url']}'>{$item['text']}</a></li>\n";      
                                }
            $html .= "      </ul>
                        </div>";
            $html .= "</nav>\n";
            return $html;
        }
    };
?>