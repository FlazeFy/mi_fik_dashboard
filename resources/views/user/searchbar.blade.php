<div style="" class="row mb-2">
    <div class="col-2">
        <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="col-10 position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:var(--darkColor);"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="title_search" placeholder="{{ __('messages.search_fname') }}" onchange="infinteLoadMoreUser(1)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    function resetTitleSearch(){
        document.getElementById("title_search").value = null;
        infinteLoadMoreUser(1);
    }
</script>