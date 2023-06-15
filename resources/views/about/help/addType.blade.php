<button type="button" class="btn btn-success mb-2 w-100" style="border-radius:10px;" data-bs-toggle="modal" data-bs-target="#addHelpType">
    <i class="fa-solid fa-plus"></i> Add New Type
</button>

<script>
    let validation = [
        { id: "help_type", req: true, len: 30 },
    ];
    function validateFormAddType(rules){
        var input, msg;
        var res = true
        var btn = document.getElementById("submit_holder"); 

        rules.forEach(e => {
            input = document.getElementById(e.id);
            msg = document.getElementById(e.id+"_msg");

            if(input.value.trim().length >= e.len){
                msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Reaching maximum character length";
                res = false
            } else if(input.value.trim().length == 0 && e.req === true){
                msg.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Failed. Field can't be empty";
                res = false
            } else {
                msg.innerHTML = " "
            }
        });

        if(res){
            if(typeof val1 !== 'undefined'){ 
                val1 = true;
                validate("profiledata");
            } else {
                btn.innerHTML = " ";
                btn.innerHTML = "<a class='btn btn-submit-form' onclick='addType()' id='btn-submit'><i class='fa-solid fa-paper-plane'></i> Submit</a>";
            }
        } else {
            if(typeof val1 === 'undefined'){ 
                btn.innerHTML = "<button disabled class='btn btn-submit-form'><i class='fa-solid fa-lock'></i> Locked</button>";
            } else {
                val1 = false;
                validate("profiledata");
            }
        }
    }
</script>

<div class="modal fade" id="addHelpType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Type</h5>
                
                <form id="formAddType">
                    @csrf 
                    <div class="form-floating">
                        <input type="text" class="form-control nameInput" id="help_type" name="help_type" maxlength="30" oninput="validateFormAddType(validation)" onkeydown="return submitTypeOnEnter(event)" required>
                        <label for="help_type">Help Type</label>
                        <a id="help_type_msg" class="input-warning text-danger"></a>
                    </div>
                    <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('beforeunload', function(event) {
        if(document.getElementById("help_type").value.trim() != "" && !isFormSubmitted){
            event.preventDefault();
            event.returnValue = '';
        }
    });

    function addType(){
        $('#help_type_msg').html("");

        $.ajax({
            url: '/api/v1/help/type',
            type: 'POST',
            data: $('#formAddType').serialize(),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response) {
                $("#addHelpType").modal('hide');
                document.getElementById("help_type").value = "";
                loadType();
            },
            error: function(response, jqXHR, textStatus, errorThrown) {
                var errorMessage = "Unknown error occurred";
                var typeMsg = null;
                var icon = "<i class='fa-solid fa-triangle-exclamation'></i> ";
                console.log(response.responseJSON)

                if (response && response.responseJSON && response.responseJSON.hasOwnProperty('result')) {   
                    //Error validation
                    if(typeof response.responseJSON.result === "string"){
                        allMsg = response.responseJSON.result;
                    } else {
                        if(response.responseJSON.result.hasOwnProperty('help_type')){
                            allMsg = response.responseJSON.result.help_type[0];
                        }
                    }
                } else if(response && response.responseJSON && response.responseJSON.hasOwnProperty('errors')){
                    allMsg = response.responseJSON.errors.result[0]
                } else {
                    allMsg = errorMessage
                }

                //Set to html
                if(allMsg){
                    $('#help_type_msg').html(icon + allMsg);
                }
            }
        });
    }

    function submitTypeOnEnter(event) {
        if (event.keyCode === 13) { 
            addType();
            return false; 
        }
        return true; 
    }
</script>
