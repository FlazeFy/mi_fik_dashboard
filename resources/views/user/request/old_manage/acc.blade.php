<div class="modal fade" id="accOldReqModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body p-4 pb-1">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5 class="text-success">Accept Request</h5><hr>
                <ol id="list_acc_holder"></ol>   
                <form class="d-inline" action="/user/request/accept_request/multi" method="POST">
                    @csrf
                    <input hidden name="list_request" id="list_request_acc" value="">
                    <button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button> 
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshListAcc(){
        var holder = document.getElementById("list_acc_holder");
        document.getElementById("list_request_acc").value = JSON.stringify(selectedOldUser);
        $("#list_acc_holder").empty();

        selectedOldUser.forEach(e => {
            if(e.request_type === "add"){
                var color = "text-success";
            } else {
                var color = "text-danger";
            }

            var elmt = " " +
                "<li class='mb-1'>" + e.full_name + " want to <span class='" + color + " fw-bold'>" + ucFirst(e.request_type) + "</span> " + getTag(JSON.parse(e.tag_list), 'px-2 py-1', '12.5px', 'mb-1') + "</li>";
            
            $("#list_acc_holder").append(elmt);
        });
    }
</script>

