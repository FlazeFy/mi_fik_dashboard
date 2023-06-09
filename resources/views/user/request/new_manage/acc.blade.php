<div class="modal fade" id="accNewReqModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body p-4 pb-1">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5 class="text-success">Accept Request</h5><hr>
                <ol id="list_acc_holder_new"></ol>   
                <form class="d-inline" action="/user/request/accept_join/false" method="POST">
                    @csrf
                    <input hidden name="list_request" id="list_request_acc_new" value="">
                    <button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</button> 
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshListAccNew(){
        var holder = document.getElementById("list_acc_holder_new");
        document.getElementById("list_request_acc_new").value = JSON.stringify(selectedNewUser);
        $("#list_acc_holder_new").empty();

        selectedNewUser.forEach(e => {
            var elmt = " " +
                "<li class='mb-1'>" + e.full_name + " want to <span class='text-success fw-bold'>Join</span> Mi-FIK</li>";
            
            $("#list_acc_holder_new").append(elmt);
        });
    }
</script>

