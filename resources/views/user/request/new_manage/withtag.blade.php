<div class="modal fade" id="accNewReqTagModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body p-4 pb-1">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5 class="text-success">Accept Request With Tag</h5><hr>
                <form class="d-inline" action="/user/request/accept_join/true" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <ol id="list_acc_holder_new_tag"></ol>   
                        </div>
                        <div class="col-6">
                            @include('user.request.new_manage.rolepicker')
                        </div>
                    </div>

                    <input hidden name="list_request" id="list_request_acc_new_tag" value="">
                    <button class='btn btn-submit-form' type='submit' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> {{ __('messages.submit') }}</button> 
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshListAccNewTag(){
        var holder = document.getElementById("list_acc_holder_new_tag");
        document.getElementById("list_request_acc_new_tag").value = JSON.stringify(selectedNewUser);
        $("#list_acc_holder_new_tag").empty();

        selectedNewUser.forEach(e => {
            var elmt = " " +
                "<li class='mb-1'>" + e.full_name + " want to <span class='text-success fw-bold'>Join</span> Mi-FIK</li>";
            
            $("#list_acc_holder_new_tag").append(elmt);
        });
    }
</script>

