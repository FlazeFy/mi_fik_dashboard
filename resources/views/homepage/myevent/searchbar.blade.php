<div style="max-width:300px; position:absolute; right:75px; top:15px;" class="row">
    <div class="col-2">
        <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetMyEventSearch()"><i class="fa-solid fa-xmark"></i></a>
    </div>
    <div class="col-10 position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:var(--darkColor);"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="myevent_search" placeholder="Search by event title" onblur="infinteLoadMyEvent(1)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    function resetMyEventSearch(){
        document.getElementById("myevent_search").value = null;
        infinteLoadMyEvent(1);
    }
</script>