<h6 class="text-secondary text-center">Sorry your <a class="text-danger" title="Awaiting Request" data-bs-toggle="popover" title="Popover title" style="cursor: pointer;"
    data-bs-content="You have requested to {{$myreq[0]['request_type']}} 
        <?php 
            $tag = $myreq[0]['tag_slug_name'];
            $count = count($tag);

            for($i = 0; $i < $count; $i++){
                if($i == $count - 1){
                    echo "#".$tag[$i]['tag_name'];
                } else {
                    echo "#".$tag[$i]['tag_name'].", ";
                }
            }
        ?>
    "> request</a> is still in process by our Admin. Please wait some moment or try to contact the 
    <a class="text-primary text-decoration-none" title="Send email" href="mailto:hello@mifik.id">Admin</a>
</h6>