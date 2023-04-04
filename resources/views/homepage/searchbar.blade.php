<div style="position:absolute; right:0;" class="row">
    <div class="col-2">
        <button class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="col-10">
        <input type="text" class="form-control rounded-pill" id="title_search" placeholder="Search by event title" onblur="checkTitleSearch()" maxlength="75">
    </div>
</div>

<script type="text/javascript">
    var search = " ";
    const search_storage = sessionStorage.getItem('search')

    if (search_storage == null) {
        sessionStorage.setItem('search', search)
    } else {
        document.getElementById('title_search').value = search_storage
    }

    function checkTitleSearch() {
        var input_search = document.getElementById('title_search').value

        if(input_search == null || input_search.trim() === ''){
            sessionStorage.setItem('search', ' ')
        } else {
            sessionStorage.setItem('search', input_search.trim())
        }
        if(input_search.trim() != search_storage.trim()){
            location.reload()
        }

        search = input_search.trim()

    }

    function resetTitleSearch(){
        sessionStorage.setItem('search', ' ')
        location.reload()
    }
</script>