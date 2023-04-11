<button type="button" class="btn btn-success mt-2" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#addHelpType">
    <i class="fa-solid fa-plus"></i> Add New Type
</button>

<div class="modal fade" id="addHelpType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Type</h5>
                
                <form action="/about/help/add/type" method="POST">
                    @csrf 
                    <div class="form-floating">
                        <input type="text" class="form-control nameInput" id="help_type" name="help_type" maxlength="75" oninput="lengValidator(75)" required>
                        <label for="titleInput_event">Help Type</label>
                    </div>
                    <a id="help_msg" class="input-warning text-danger"></a>
                    <span id="btn-submit-holder-group"></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //Validator
    function lengValidator(len){        
        
        if($("#help_type").val().length >= len){
            $("#help_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> You reaches the maximum character");
            $("#btn-submit-holder-group").html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
        } else {
            $("#help_msg").text("");
            $("#btn-submit-holder-group").html('<button type="submit" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        }
    }
</script>