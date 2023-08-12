<h5 class="section-title">{{ __('messages.tag_cat')}}</h5>
<div class="@if(!$isMobile) table-responsive @endif ">
    <table class="table table-paginate" id="tagCatTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.cat') }}</th>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.description') }}</th>
                <th scope="col" style="min-width:var(--tcolMinXSM);">{{ __('messages.delete') }}</th>
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
                        @if($dtag->slug_name != 'general-role')
                            <button class="btn btn-danger" data-bs-target="#deleteCatModal-{{$dtag->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                        @else 
                            <button class="btn btn-info" data-bs-target="#infoDefaultTag-{{$dtag->id}}" data-bs-toggle="modal" style="padding:8px 18px;"><i class="fa-solid fa-info"></i></button>
                                @include('event.tag.infoDefaultCat')
                        @endif
                    </td>
                </tr>
                @include("event.tag.deleteCat")
            @endforeach
        </tbody>
    </table>
</div>