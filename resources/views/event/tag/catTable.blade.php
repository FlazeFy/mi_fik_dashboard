<h5 class="section-title">Tag Category</h5>
<div class="@if(!$isMobile) table-responsive @endif ">
    <table class="table table-paginate" id="tagCatTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" style="min-width:var(--tcolMinSM);">Category</th>
                <th scope="col" style="min-width:var(--tcolMinSM);">Description</th>
                <th scope="col" style="min-width:var(--tcolMinXSM);">Delete</th>
            </tr>
        </thead>
        <tbody class="tabular-body">
            @foreach($dct_tag as $dtag)
                <tr class="tabular-item normal">
                    <td style="min-width:var(--tcolMinSM);">
                        <div style="max-width:160px !important; word-break: break-all !important;">{{$dtag->dct_name}}</div>
                    </td>
                    <td style="min-width:var(--tcolMinSM);">
                        {{$dtag->dct_desc}}
                    </td>
                    <td style="min-width:var(--tcolMinXSM);">
                        <button class="btn btn-danger" data-bs-target="#deleteCatModal-{{$dtag->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                @include("event.tag.deleteCat")
            @endforeach
        </tbody>
    </table>
</div>