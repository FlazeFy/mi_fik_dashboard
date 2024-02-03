<div style="white-space:nowrap;">
    <a class="btn btn-danger navigator-right rounded-pill px-4" style="right:0" title="Delete event" data-bs-toggle="modal" data-bs-target="#deleteEvent-{{$attd->id}}"><i class="fa-solid fa-trash"></i></a>
    @if(session()->get('toogle_edit_attendance') == 'false')
        <form class="d-inline" method="POST" action="/attendance/toogle/attendance/true">
            @csrf
            <button class="btn btn-info rounded-pill px-3 py-2" title="Switch to edit mode" style="font-size: var(--textLG) !important;" type="submit"><i class="fa-solid fa-edit"></i></button>
        </form>
    @else 
        <form class="d-inline" method="POST" action="/attendance/toogle/attendance/false">
            @csrf
            <button class="btn btn-danger rounded-pill px-3 py-2" title="Back to view mode" style="font-size: var(--textLG) !important;" type="submit"><i class="fa-solid fa-xmark"></i></button>
        </form>
    @endif   
</div>