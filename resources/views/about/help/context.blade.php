<h6 class="fst-italic" style="font-size:14px;">Last Updated By : <span class="text-primary" id="desc_updated"></span> </h6>
<div id="rich_box_desc" style="height: 30vh !important;">
    
</div>
<form class="d-inline" method="POST" action="/about/edit/app">
    @csrf
    <input name="help_body" id="about_body" hidden>
    <button class="btn btn-success mt-3" onclick="getRichTextHelpDesc()"><i class="fa-solid fa-floppy-disk"></i> Save Chages</button>
</form>

<script>
    var desc = document.getElementById("about_body");

    function loadRichTextDesc(desc, user, updated){
        document.getElementById("desc_updated").innerHTML = user + " at " + getDateToContext(updated);
        var parent = document.getElementById("rich_box_desc");
        var child = parent.getElementsByClassName("ql-editor")[0];
        child.innerHTML = desc;
    }

    function getRichTextHelpDesc(){
        var rawText = document.getElementById("rich_box_desc").innerHTML;

        //Remove quills element from raw text
        var cleanText = rawText.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','').replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">');
        //Check this clean text 2!!!
        cleanText = cleanText.replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','');
        
        //Pass html quilss as input value
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
        desc.value = modifiedString;
    }

    var quill = new Quill('#rich_box_desc', {
        theme: 'snow'
    });
</script>