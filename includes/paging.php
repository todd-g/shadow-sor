    <ul class="pagination">
        
            <?php
                if(isset($pages))
                {  
                    if($pages > 1)        
                    {    if($cur_page > $num_links)     // for taking to page 1 //
                        {   $dir = "&larr;";
                            echo '<li class="arrow"> <a href="'.$_SERVER['PHP_SELF'].'?'. $qstring .'&x=x&page='.(1).'">'.$dir.'</a> </li>';
                        }
                       if($cur_page > 1) 
                        {
                            $dir = "&laquo;";
                            echo '<li class="arrow"> <a href="'.$_SERVER['PHP_SELF'].'?'. $qstring .'&x=x&page='.($cur_page-1).'">'.$dir.'</a> </li>';
                        }                 
                        
                        for($x=$start ; $x<=$end ;$x++)
                        {
                            
                            echo ($x == $cur_page) ? '<li class="current"><a href="">'.$x.'</a></li> ':'<li><a href="'.$_SERVER['PHP_SELF'].'?'. $qstring .'&x=x&page='.$x.'">'.$x.'</a></li> ';
                        }
                        if($cur_page < $pages )
                        {   $dir = "&raquo;";
                            echo '<li class="arrow"> <a href="'.$_SERVER['PHP_SELF'].'?'. $qstring .'&x=x&page='.($cur_page+1).'">'.$dir.'</a> </li>';
                        }
                        if($cur_page < ($pages-$num_links) )
                        {   $dir = "&rarr;";
                       
                            echo '<li class="arrow"><a href="'.$_SERVER['PHP_SELF'].'?'. $qstring .'&x=x&page='.$pages.'">'.$dir.'</a></li> '; 
                        }   
                    }
                }
            ?>
        
    </ul>