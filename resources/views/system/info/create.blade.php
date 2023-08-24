<script>
    let validationAdd = [
        { id: "info_location", req: true, len: 75 },
    ];
</script>

@if(!$isMobile)
    <button class="btn btn-submit mt-4" data-bs-toggle="modal" style="height:40px; padding:0 15px !important;" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> {{ __('messages.add') }} Info</button>
@else 
    <button type="button" class="btn btn-mobile-control bg-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i></button>
@endif
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Info</h5>
                
                <form action="/system/info/create" method="POST" onsubmit="getRichTextCreate()" id="form-add-info">
                    @csrf 
                    <div class="row my-2">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-floating">
                                <select class="form-select" id="info_type" title="Info Type" name="info_type" aria-label="Floating label select example" required> 
                                    @foreach($dictionary as $dct)
                                        <option value="{{strtolower($dct->dct_name)}}">{{$dct->dct_name}}</option>
                                    @endforeach
                                </select> 
                                <label for="floatingSelect">Type</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-floating">
                                <select class="form-select" id="info_page" title="Info Page" name="info_page" aria-label="Floating label select example" required> 
                                    @foreach($menu as $mn)
                                        <option value="{{substr($mn->menu_url,1)}}">{{substr($mn->menu_url,1)}}</option>
                                    @endforeach
                                    <option value="register">register</option>
                                    <option value="landing">landing</option>
                                    <option value="event/detail">event/detail</option>
                                    <option value="event/edit">event/edit</option>
                                </select> 
                                <label for="titleInput_event">{{ __('messages.info_page') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control nameInput" id="info_location" name="info_location" maxlength="75" oninput="validateForm(validationAdd)" required>
                        <label for="titleInput_event">{{ __('messages.info_loc') }}</label>
                        <a id="info_location_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div id="rich_box_create"></div>
                    <input name="info_body" id="info_body" hidden>
                    @include('components.infobox',['info'=>$info, 'location'=> 'add_info'])           
                    <span id="submit_holder" class="float-end"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var infobody = document.getElementById("info_body");

    function deleteAfterCharacterCreate(str, character) {
        var index = str.indexOf(character);
        if (index !== -1) {
            return str.slice(0, index);
        } else {
            return str;
        }
    }

    function getRichTextCreate(){
        var rawText = document.getElementById("rich_box_create").innerHTML;
        var cleanText = splitOutRichTag(rawText);
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacterCreate(cleanText, characterToDeleteAfter);
        infobody.value = modifiedString;
    }
</script>

<!-- Main Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#rich_box_create', {
        theme: 'snow'
    });

    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-info');
            const inputs = form.querySelectorAll('input');
            var parentDiv = document.querySelector('#rich_box_create');
            var richText = parentDiv.querySelector('.ql-editor');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token") {
                    is_editing = true;
                    break;
                }
            }
            if (richText) {
                for (var i = 0; i < richText.childNodes.length; i++) {
                    var cNode = richText.childNodes[i];
                    if (cNode.nodeType === Node.ELEMENT_NODE && cNode.textContent.trim() !== '') {
                        is_editing = true;
                        break;
                    }
                }
            }

            if(is_editing){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });
</script>