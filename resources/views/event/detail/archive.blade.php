<div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="section-select-archive">
    <span class="dropdown-item py-2 px-0">
        <label class="fw-bold ms-2">My Archive</label><br>
        <div class="archive-holder">
            @php($i = 0)
            @foreach($archive as $ar)
                @php($found = false)
                @foreach($archive_relation as $arl)
                    @if($arl->archive_id == $ar->id)
                        @php($found = true)
                        @php($id = $arl->id)
                    @endif
                @endforeach

                @if($found)
                    <form class="d-inline" action="/event/detail/delete_relation/{{$id}}" method="POST">
                        @csrf
                        <button class="btn archive-box active shadow text-start" type="submit" title="Remove event from {{$ar->archive_name}}">
                            <div class="icon-holder">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                            <h6 class="text-secondary" id="archive-title-{{$i}}">{{$ar->archive_name}}</h6>
                            <h6 class="archive-count"><span>Event : </span>&nbsp<span>Task : </span></h6>
                        </button>
                    </form>
                @else 
                    <form class="d-inline" action="/event/detail/add_relation/{{$c->slug_name}}" method="POST">
                        @csrf
                        <input hidden value="{{$ar->id}}" name="archive_id">
                        <button class="btn archive-box shadow text-start" type="submit" title="Add event to {{$ar->archive_name}}">
                            <div class="icon-holder">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <h6 class="text-secondary" id="archive-title-{{$i}}">{{$ar->archive_name}}</h6>
                            <h6 class="archive-count"><span>Event : </span>&nbsp<span>Task : </span></h6>
                        </button>
                    </form>
                @endif
                @php($i++)
            @endforeach

            <span id="add-archive-form-holder"></span>
            <button class="btn btn-add-archive" onclick="add_archive_form()"><i class="fa-solid fa-plus"></i> Add new archive</button>
        </div>
    </span>
</div>

<script>
    function add_archive_form(){
        if(j == 0){
            document.getElementById('add-archive-form-holder').innerHTML = " " +
            '<form class="d-inline" action="/event/detail/add_archive" method="POST"> ' +
                '@csrf ' +
                '<div class="container-fluid rounded p-1 mt-2"> ' +
                    '<div class="form-floating"> ' +
                        '<input type="text" name="archive_name" class="form-control" id="floatingInput" placeholder="ex: my list" onblur="this.form.submit()" required> ' +
                        '<label for="floatingInput">Archive Name</label> ' +
                    '</div> ' +
                '</div> ' +
            '</form>';
        }
        j++;
    }
</script>