<style>

</style>

@if(session()->get('ordering_trash') == "ASC")
    <form class="d-inline" action="/trash/ordered/DESC" title="{{ __('messages.sort_asc_des') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> {{ __('messages.asc') }}</button>
    </form>
@else
    <form class="d-inline" action="/trash/ordered/ASC" title="{{ __('messages.sort_desc_des') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> {{ __('messages.desc') }}</button>
    </form>
@endif

<script>

</script>