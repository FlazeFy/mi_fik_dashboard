<style>
    #tagName_msg{
        text-decoration:none;
    }
</style>

<script>
    let validation2 = [
        { id: "dct_name", req: true, len: 35 },
        { id: "dct_desc", req: false, len: 255 },
    ];
</script>

<div class="position-relative">
    <h5 class="text-secondary fw-bold">Create New Tag Category</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button"
        data-bs-toggle="popover" title="Info" data-bs-content="Tag is ... ...."><i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <form class="p-2 mt-2" action="/event/tag/add_category" method="POST">
        @csrf
        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="dct_name" name="dct_name" oninput="validateFormSecond(validation2)" maxlength="35" required>
            <label for="dct_name">Tag Category Name</label>
            <a id="dct_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating">
            <textarea class="form-control" style="height: 100px" id="dct_desc" name="dct_desc" oninput="validateFormSecond(validation2)" maxlength="255"></textarea>
            <label for="dct_desc">Category Description</label>
            <a id="dct_desc_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <span id="submit_holder_second"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
    </form>
</div>