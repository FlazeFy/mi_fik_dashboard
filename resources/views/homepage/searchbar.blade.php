<div style="@if(!$isMobile) position:absolute; right:0; @else margin-top:var(--spaceMD); @endif" class="row">
    <div class="col-2">
        <button class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="col-10 position-relative">
        <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:var(--darkColor);"></i>
        <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="title_search" placeholder="{{ __('messages.search_event_title') }}" 
            onkeydown="return submitOnEnter(event)" onblur="checkTitleSearch()" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    var search = "";
    const search_storage = sessionStorage.getItem('search');

    search_storage == null ? sessionStorage.setItem('search', search) : document.getElementById('title_search').value = search_storage;

    function checkTitleSearch() {
        var input_search = document.getElementById('title_search').value;

        input_search == null || input_search.trim() === '' ? sessionStorage.setItem('search', '') : sessionStorage.setItem('search', input_search.trim());
        if(search_storage == null || input_search.trim() != search_storage.trim()){
            location.reload();
        }

        search = input_search.trim();
    }

    function resetTitleSearch(){
        sessionStorage.setItem('search', '');
        location.reload();
    }
    function submitOnEnter(event) {
        if (event.keyCode === 13) { 
            event.preventDefault(); 
            checkTitleSearch();
            return false; 
        }
        return true; 
    }
</script>