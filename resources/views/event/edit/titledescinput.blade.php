<form action="/event/edit/update/info/{{$c->slug_name}}" method="POST">
    @csrf
    <div class="form-floating mb-2">
        <input type="text" class="form-control" id="floatingTitle" name="content_title" placeholder="{{$c->content_title}}" value="{{$c->content_title}}" required>
        <label for="floatingTitle">Content Title</label>
    </div>
    <div id="rich_box" style="height:55vh !important;"><?php echo $c->content_desc; ?></div>
    <input name="content_desc" id="content_desc" hidden>
    <button class="btn btn-submit mt-2" type="submit" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
</form>

<script type="text/javascript">
    var desc = document.getElementById("content_desc");

    function deleteAfterCharacter(str, character) {
        var index = str.indexOf(character);
        if (index !== -1) {
            return str.slice(0, index);
        } else {
            return str;
        }
    }

    function getRichText(){
        var rawText = document.getElementById("rich_box").innerHTML;
        var cleanText = splitOutRichTag(rawText);        
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
        desc.value = modifiedString;
    }
</script>

<!-- Main Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#rich_box', {
        theme: 'snow'
    });
</script>