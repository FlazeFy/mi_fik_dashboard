<style>

</style>

<div style="position:absolute; right: 60px; top:-5px;">
    @if(session()->get('ordering_finished') == "ASC")
        <form class="d-inline" action="/event/calendar/ordered/DESC" title="Sort content by ascending" method="POST">
            @csrf
            <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> Ascending</button>
        </form>
    @else
        <form class="d-inline" action="/event/calendar/ordered/ASC" title="Sort content by descending" method="POST">
            @csrf
            <button class="btn btn-primary px-3" type="submit"><i class="fa-solid fa-sort"></i> Descending</button>
        </form>
    @endif
</div>

<script>

</script>