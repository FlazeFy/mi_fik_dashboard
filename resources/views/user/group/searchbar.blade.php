<div style="max-width:300px; margin-left:10px;" class="row mb-2">
    <div class="col-2">
        <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetGroupSearch()"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="col-10 position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:#414141;"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="group_search" placeholder="Search by group name" onchange="infinteLoadMore(1)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    function resetGroupSearch(){
        document.getElementById("group_search").value = null;
        infinteLoadMore(1);
    }
</script>