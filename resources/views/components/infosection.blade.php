<button class="btn btn-transparent px-2 py-0 position-absolute" style="right:65px; top:2px;" type="button"
    data-bs-toggle="popover" title="Info" 
        data-bs-content="<?php 
            if($st == "question"){
                echo "This section show all question from user, whether they have been answered by admin or not.";
            } else if($st == "answer"){
                echo "In this section, you can answer the user question by directly answering or using answer suggestion.";
            }
        ?>"><i class="fa-solid fa-ellipsis-vertical more"></i>
</button>