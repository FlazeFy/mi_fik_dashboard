<div style="top:0; left:160px;" class="position-absolute">
    <div class="d-inline-block">
        <button class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="d-inline-block" style="width:240px;">
        <input type="text" class="form-control rounded-pill" id="title_search" placeholder="Search by event title" onkeydown="return submitOnEnter(event)"
            onblur="checkTitleSearch()" maxlength="150">
    </div>
</div>

<script type="text/javascript">
    var search = "";
    var count_plc = 0;
    const search_storage = sessionStorage.getItem('search_trash');
    var role_key = <?= session()->get("role_key") ?>;

    $(document).ready(function() {
        clear();
    });
    
    function clear() {
        setTimeout(function() {
            generateSearchPlaceholder();
            clear();
        }, 2000); 
    }

    function generateSearchPlaceholder(){
        var title_search = document.getElementById("title_search");
        if(role_key == 0){
            if(count_plc % 2 == 0){
                title_search.placeholder = "Search by event title";
            } else {
                title_search.placeholder = "Search by task title";
            }
            count_plc++;
        } else {
            if(count_plc == 0){
                title_search.placeholder = "Search by event title";
            } else if(count_plc == 1){
                title_search.placeholder = "Search by tag name";
            } else if(count_plc == 2){
                title_search.placeholder = "Search by group name";
            } else if(count_plc == 3){
                title_search.placeholder = "Search by info type";
            } else if(count_plc == 4){
                title_search.placeholder = "Search by feedback rate";
            } else if(count_plc == 5){
                title_search.placeholder = "Search by question type";
            } else if(count_plc == 6){
                title_search.placeholder = "Search by dictionary name";
            }
            count_plc++;

            if(count_plc == 7){
                count_plc = 0;
            }
        }   
    }

    if (search_storage == null) {
        sessionStorage.setItem('search_trash', search)
    } else {
        document.getElementById('title_search').value = search_storage
    }

    function checkTitleSearch() {
        var input_search = document.getElementById('title_search').value

        if(input_search == null || input_search.trim() === ''){
            sessionStorage.setItem('search_trash', '')
        } else {
            sessionStorage.setItem('search_trash', input_search.trim())
        }
        if(search_storage == null || input_search.trim() != search_storage.trim()){
            location.reload()
        }

        search = input_search.trim()

    }

    function resetTitleSearch(){
        sessionStorage.setItem('search_trash', '')
        location.reload()
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