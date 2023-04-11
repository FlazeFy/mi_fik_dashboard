<style>

</style>

@if(session()->get('ordering_trash') == "ASC")
    <form class="d-inline" action="/trash/ordered/DESC" title="Sort content by ascending" method="POST">
        @csrf
        <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> Ascending</button>
    </form>
@else
    <form class="d-inline" action="/trash/ordered/ASC" title="Sort content by descending" method="POST">
        @csrf
        <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> Descending</button>
    </form>
@endif

<script>

</script>