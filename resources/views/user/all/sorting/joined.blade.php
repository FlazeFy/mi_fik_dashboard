@php($str = explode("__", session()->get('ordering_user_list')))
@if($str[0] == "created_at" && $str[1] == "ASC")
    <form class="d-inline" action="/user/all/ordered/DESC/joined" title="Sort joined date by ascending" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@elseif($str[0] == "created_at" && $str[1] == "DESC")
    <form class="d-inline" action="/user/all/ordered/ASC/joined" title="Sort joined date by descending" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@else
    <form class="d-inline" action="/user/all/ordered/ASC/joined" title="Sort joined date by descending" method="POST">
        @csrf
        <button class="btn btn-icon px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@endif

<script>

</script>