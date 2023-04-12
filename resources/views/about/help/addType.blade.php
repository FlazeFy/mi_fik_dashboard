<button type="button" class="btn btn-success mt-2" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#addHelpType">
    <i class="fa-solid fa-plus"></i> Add New Type
</button>

<script>
    let validation = [
        { id: "help_type", req: true, len: 30 },
    ];
</script>

<div class="modal fade" id="addHelpType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Type</h5>
                
                <form action="/about/help/add/type" method="POST">
                    @csrf 
                    <div class="form-floating">
                        <input type="text" class="form-control nameInput" id="help_type" name="help_type" maxlength="30" oninput="validateForm(validation)" required>
                        <label for="help_type">Help Type</label>
                        <a id="help_type_msg" class="input-warning text-danger"></a>
                    </div>
                    <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </form>
            </div>
        </div>
    </div>
</div>