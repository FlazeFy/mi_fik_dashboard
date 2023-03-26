<button class="btn btn-submit position-absolute" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Group</button>
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Grouping</h5>
                
                <form action="/user/group/add" method="POST">
                    @csrf 
                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-floating">
                                <input type="text" class="form-control nameInput" id="titleInput_group" name="group_name" maxlength="75" oninput="lengValidatorGroup(75)" required>
                                <label for="titleInput_event">Group Name</label>
                            </div>
                            <div class="form-floating mt-2">
                                <textarea class="form-control" id="floatingTextarea2" name="group_desc" style="height: 140px"></textarea>
                                <label for="floatingTextarea2">Description (Optional)</label>
                            </div>
                            <a id="title_msg_group" class="input-warning text-danger"></a>

                            @foreach($info as $in)
                                @if($in->info_location == "add_group")
                                    <div class="info-box {{$in->info_type}}">
                                        <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                        <?php echo $in->info_body; ?>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            
                        </div>
                    </div>
                    <span id="btn-submit-holder-group"></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //Validator
    function lengValidatorGroup(len){        
        
        if($("#titleInput_group").val().length >= len){
            $("#title_msg_group").html("<i class='fa-solid fa-triangle-exclamation'></i> You reaches the maximum character");
            $("#btn-submit-holder-group").html('<button disabled class="custom-submit-modal"><i class="fa-solid fa-lock"></i> Locked</button>');
        } else {
            $("#title_msg_group").text("");
            $("#btn-submit-holder-group").html('<button type="submit" class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> Submit</button>');
        }
    }
</script>

