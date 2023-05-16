<div style="" class="">
    <div class="d-inline-block">
        <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearchNew()"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="d-inline-block position-relative w-75">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 10px; color:#414141;"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="fullname_search_new" placeholder="Search by fullname" onchange="infinteLoadMore_new_req(1)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    function resetTitleSearchNew(){
        document.getElementById("fullname_search_new").value = null;
        infinteLoadMore_new_req(1);
    }
</script>