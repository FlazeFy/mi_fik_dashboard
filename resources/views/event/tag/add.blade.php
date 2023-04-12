<style>
    #tagName_msg{
        text-decoration:none;
    }
</style>

<script>
    let validation = [
        { id: "tag_name", req: true, len: 30 },
        { id: "tag_desc", req: false, len: 255 },
    ];
</script>

<div class="position-relative">
    <h5 class="text-secondary fw-bold">Create New Tag</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button"
        data-bs-toggle="popover" title="Info" data-bs-content="Tag is ... ...."><i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <form class="p-2 mt-2" action="/event/tag/add" method="POST">
        @csrf
        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="tag_name" name="tag_name" oninput="validateForm(validation)" maxlength="30" required>
            <label for="tag_name">Tag Name</label>
            <a id="tag_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating">
            <textarea class="form-control" style="height: 100px" id="tag_desc" name="tag_desc" oninput="validateForm(validation)" maxlength="255"></textarea>
            <label for="tag_desc">Tag Description</label>
            <a id="tag_desc_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
    </form>
</div>