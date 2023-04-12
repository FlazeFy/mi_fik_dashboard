<h6 class="fst-italic" style="font-size:14px;">Last Updated By : <span class="text-primary" id="desc_updated"></span> </h6>
<div id="rich_box_desc" style="height: 40vh !important;">
    
</div>
<form class="d-inline" id="form-edit-desc" method="POST" action="">
    @csrf
    <input name="help_body" id="about_body" hidden>
    <input name="help_category" id="about_category" hidden>
    <button class="btn btn-success mt-3" onclick="getRichTextHelpDesc(id_body)"><i class="fa-solid fa-floppy-disk"></i> Save Chages</button>
</form>

<script>
    var desc = document.getElementById("about_body");
    var form = document.getElementById('form-edit-desc');
    var input_cat = document.getElementById("about_category");
    var id_body = " ";

    function loadRichTextDesc(desc, user, updated, cat){
        document.getElementById("desc_updated").innerHTML = user + " at " + getDateToContext(updated);
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

        //Remove quills element from raw text
        var cleanText = rawText.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','').replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">');
        //Check this clean text 2!!!
        cleanText = cleanText.replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','');
        
        //Pass html quilss as input value
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
        desc.value = modifiedString;

        form.addEventListener('submit', function(event) {
            event.preventDefault(); 
            form.action = '/about/help/edit/body/' + id;
            form.submit();
        });
    }

    var quill = new Quill('#rich_box_desc', {
        theme: 'snow'
    });
</script>