<script>
    let validationAdd = [
        { id: "dct_name", req: true, len: 35 },
        { id: "dct_desc", req: false, len: 255 },
    ];
</script>

@if(!$isMobile)
    <button class="btn btn-submit mt-4" data-bs-toggle="modal" style="height:40px; padding:0 15px !important;" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Dictionary</button>
@else 
    <button type="button" class="btn btn-mobile-control bg-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i></button>
@endif
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Dictionary</h5>
                
                <form action="/system/dictionary/create" method="POST" id="form-add-dct">
                    @csrf 
                    <div class="form-floating">
                        <input type="text" class="form-control nameInput" id="dct_name" name="dct_name" maxlength="35" oninput="validateForm(validationAdd)" required>
                        <label for="titleInput_event">Dictionary Name</label>
                        <a id="dct_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div class="form-floating mt-2">
                        <textarea class="form-control" id="dct_desc" name="dct_desc" style="height: 120px" maxlength="255" oninput="validateForm(validationAdd)"></textarea>
                        <label for="floatingTextarea2">Description (Optional)</label>
                        <a id="dct_desc_msg" class="input-warning text-danger"></a>
                    </div>
                    <div class="form-floating mt-2">
                        <select class="form-select" id="dct_type" title="Tag Category" name="dct_type" aria-label="Floating label select example" required> 
                            @foreach($dictionaryType as $dtype) 
                                <option value="{{$dtype->app_code}}">{{$dtype->type_name}}</option> 
                            @endforeach 
                        </select> 
                        <label for="floatingSelect">Type</label>
                    </div>
                    <span id="submit_holder" class="float-end"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-dct');
            const inputs = form.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token") {
                    is_editing = true;
                    console.log(input.name)
                    break;
                }
            }

            if(is_editing){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });
</script>