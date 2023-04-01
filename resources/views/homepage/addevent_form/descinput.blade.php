<div id="rich_box"></div>
<input name="content_desc" id="content_desc" hidden>

<script>
    var desc = document.getElementById("content_desc");

    function getRichText(){
        var rawText = document.getElementById("rich_box").innerHTML;

        //Remove quills element from raw text
        var cleanText = rawText.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','')replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">');
        //Check this clean text 2!!!
        cleanText = cleanText.replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','');
        
        //Pass html quilss as input value
        desc.value = cleanText;
    }
</script>

<!-- Main Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#rich_box', {
        theme: 'snow'
    });
</script>