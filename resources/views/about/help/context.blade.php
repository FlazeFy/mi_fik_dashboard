
@if(session()->get('toogle_edit_help') == "true")
    <div id="rich_box_desc" style="height: 40vh !important;"></div>
    <h6 class="fst-italic mt-2" style="font-size:14px;">{{ __('messages.last_updated') }} : <span class="text-primary" id="desc_updated"></span> </h6>

    <form class="d-inline" method="POST" action="/about/toogle/help/false">
        @csrf
        <button class="btn btn-danger rounded-pill mt-3 me-2 px-3 py-2" type="submit"><i class="fa-regular fa-pen-to-square"></i> {{ __('messages.close') }}</button>
    </form>
    <form class="d-none" id="form-edit-desc" method="POST" action="">
        @csrf
        <input name="help_body" id="about_body_help" hidden>
        <input name="help_category" id="about_category" hidden>
        <button class="btn btn-success rounded-pill mt-3 px-3 py-2" onclick="getRichTextHelpDesc(id_body)"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>
    </form>
    <form class="d-none" id="form-delete-cat" method="POST" action="">
        @csrf
        <input name="help_category" id="about_category" hidden>
        <button class="btn btn-danger rounded-pill mt-3 px-3 py-2" onclick="deleteCategory(id_body)"><i class="fa-solid fa-trash"></i> {{ __('messages.delete') }}</button>
    </form>
@else
    <div class="px-2 position-relative">
        <form class="d-inline position-absolute" style="right:0; top:-25px;" method="POST" action="/about/toogle/help/true">
            @csrf
            <button class="btn btn-info rounded-pill mt-3 px-3 py-2" type="submit"><i class="fa-regular fa-pen-to-square"></i> {{ __('messages.edit') }}</button>
        </form>
        <div class="position-absolute text-center" id="no_cat_selected" style="top:100px; left:25%;">
            <img src="{{ asset('/assets/editor.png')}}" class='img nodata-icon-req' style="width:30vh; height:30vh;">
            <h6 class='text-secondary text-center'>{{ __('messages.see_help_type') }}</h6>
        </div>
        <div id="desc_holder_view"></div>
    </div>
@endif

<script>
    var desc = document.getElementById("about_body_help");
    var form = document.getElementById('form-edit-desc');
    var form_del = document.getElementById('form-delete-cat');
    var input_cat = document.getElementById("about_category");
    var id_body = " ";

    function loadRichTextDesc(desc, user, updated, cat){
        if(user != "null"){
            document.getElementById("desc_updated").innerHTML = user + " at " + getDateToContext(updated, "full");
        } else {
            document.getElementById("desc_updated").innerHTML = "-";
        }

        form_del.setAttribute("class","d-inline");
        form.setAttribute("class","d-inline");

        var parent = document.getElementById("rich_box_desc");
        var child = parent.getElementsByClassName("ql-editor")[0];
        input_cat.value = cat;
        if(desc != "null"){
            child.innerHTML = desc;
        } else {
            child.innerHTML = " ";
        }
    }

    function getRichTextHelpDesc(id){
        var rawText = document.getElementById("rich_box_desc").innerHTML;
        var cleanText = splitOutRichTag(rawText);
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
        desc.value = modifiedString;

        form.addEventListener('submit', function(event) {
            event.preventDefault(); 
            form.action = '/about/help/edit/body/' + id;
            form.submit();
        });
    }

    function deleteCategory(id){
        form_del.addEventListener('submit', function(event) {
            event.preventDefault(); 
            form.action = '/about/help/delete/cat/' + id;
            form.submit();
        });
    }

    <?php
        if(session()->get('toogle_edit_help') == "true"){
            echo "var quill = new Quill('#rich_box_desc', {
                theme: 'snow'
            });";
        }
    ?>
</script>