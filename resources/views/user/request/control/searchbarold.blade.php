<div style="" class="">
    <div class="d-inline-block">
        <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearchOld()"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="d-inline-block position-relative w-75">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 10px; color:#414141;"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="fullname_search_old" placeholder="Search by fullname" onchange="infinteLoadMore_old_req(1)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    function resetTitleSearchOld(){
        document.getElementById("fullname_search_old").value = null;
        infinteLoadMore_old_req(1);
    }
</script>