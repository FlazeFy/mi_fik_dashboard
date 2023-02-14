<style>
    #tagName_msg{
        text-decoration:none;
    }
</style>

<div class="position-relative">
    <h5 class="text-secondary fw-bold">Create New Tag</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button"
        data-bs-toggle="popover" title="Info" data-bs-content="Tag is ... ...."><i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <form class="p-2 mt-2" action="/event/tag/add" method="POST">
        @csrf
        <div class="form-floating">
            <input type="text" class="form-control nameInput" id="tagNameInput" name="tag_name" oninput="validateTagName()" maxlength="35" required>
            <label for="tagNameInput">Tag Name</label>
        </div>
        <a id="tagName_msg" class="text-danger"></a>
        <button class="btn btn-success mt-3" type="submit"><i class="fa-solid fa-plus"></i> Add Tag</button>
    </form>
</div>

<script>
    function validateTagName(){
        if($(".nameInput").val().length >= 35){ //Check again for the maximum length
            $("#tagName_msg").html("<i class='fa-solid fa-triangle-exclamation'></i> Error. Reaching maximum character length");
        } else {
            $("#tagName_msg").text("");
        }
    }
</script>