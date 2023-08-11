@php($str = explode("__", session()->get('ordering_user_list')))
@if($str[0] == "first_name" && $str[1] == "ASC")
    <form class="d-inline" action="/user/all/ordered/DESC/first_name" title="{{ __('messages.sort_asc_fname') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@elseif($str[0] == "first_name" && $str[1] == "DESC")
    <form class="d-inline" action="/user/all/ordered/ASC/first_name" title="{{ __('messages.sort_desc_fname') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@else
    <form class="d-inline" action="/user/all/ordered/ASC/first_name" title="{{ __('messages.sort_desc_fname') }}" method="POST">
        @csrf
        <button class="btn btn-icon px-2 py-0 ms-2" type="submit"><i class="fa-solid fa-sort fa-sm"></i></button>
    </form>
@endif

<script>

</script>