@php($str = explode("__", session()->get('ordering_group_list')))
@if($str[0] == "created_at" && $str[1] == "ASC")
    <form class="d-inline" action="/user/group/ordered/DESC/created_at" title="{{ __('messages.sort_asc_cdate') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@elseif($str[0] == "created_at" && $str[1] == "DESC")
    <form class="d-inline" action="/user/group/ordered/ASC/created_at" title="{{ __('messages.sort_desc_cdate') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@else
    <form class="d-inline" action="/user/group/ordered/ASC/created_at" title="{{ __('messages.sort_desc_cdate') }}" method="POST">
        @csrf
        <button class="btn btn-icon px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@endif

<script>

</script>