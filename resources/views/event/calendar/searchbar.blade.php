<div style="@if(!$isMobile) position:absolute; right:200px; top:-5px; @else margin-inline: var(--spaceMD); @endif" class="row @if($isMobile) mt-3 @endif">
    <div class="col-2">
        <button class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="col-10 position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:#414141;"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="title_search" placeholder="Search by event title" onblur="checkTitleSearch()"
            onkeydown="return submitOnEnter(event)" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    var search = "";
    const search_storage = sessionStorage.getItem('search_finished')

    if (search_storage == null) {
        sessionStorage.setItem('search_finished', search)
    } else {
        document.getElementById('title_search').value = search_storage
    }

    function checkTitleSearch() {
        var input_search = document.getElementById('title_search').value

        if(input_search == null || input_search.trim() === ''){
            sessionStorage.setItem('search_finished', '')
        } else {
            sessionStorage.setItem('search_finished', input_search.trim())
        }
        if(input_search.trim() != search_storage.trim()){
            location.reload()
        }

        search = input_search.trim()

    }

    function resetTitleSearch(){
        sessionStorage.setItem('search_finished', '')
        location.reload()
    }
</script>