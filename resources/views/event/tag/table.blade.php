<style>
    .form-custom{
        display: inline;
        position: relative;
    }
    .form-custom i{
        color: #9c9c9c;
    }
    .input-custom{
        padding: var(--spaceMini) var(--spaceMini) var(--spaceMini) 25px;
    }
    .input-custom:hover, .input-custom:focus{
        background: var(--hoverBG);
    }    
</style>

<h5 class="section-title">{{ __('messages.all_tag') }}</h5>
<div class="@if(!$isMobile) table-responsive @endif ">
    @include('event.tag.filterCategory')
    <table class="table table-paginate" id="tagTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.tag_name') }}</th>
                <th scope="col" style="min-width:var(--tcolMinSM);">{{ __('messages.cat') }}</th>
                @if(session()->get('role_key') == 1)
                    <th scope="col" style="min-width:var(--tcolMinXSM);">Total</th>
                    <th scope="col" style="min-width:var(--tcolMinXSM);">{{ __('messages.delete') }}</th>
                    <th scope="col" style="min-width:var(--tcolMinXSM);">Info</th>
                @else 
                    <th scope="col" style="min-width:240px;">{{ __('messages.description') }}</th>
                @endif  
            </tr>
        </thead>
        <tbody class="tabular-body">
            @foreach($tag as $tg)
                <tr class="tabular-item normal">
                    <td style="min-width:var(--tcolMinSM);">
                        <div style="@if(session()->get('role_key') == 1) max-width:160px @else max-width:220px @endif !important; word-break: break-all !important;">
                            {{$tg->tag_name}}
                            @if(session()->get('role_key') == 0)
                                @foreach($mytag as $mt)
                                    @if($mt['slug_name'] == $tg->slug_name)
                                        <div class="status-info bg-success d-inline-block py-1 mx-1">{{ __('messages.subscribed') }}</div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td style="min-width:var(--tcolMinSM);">
                        @foreach($dct_tag as $dtag)
                            @if($dtag->slug_name == $tg->tag_category)
                                {{$dtag->dct_name}}
                                @break
                            @endif
                        @endforeach
                    </td>
                    @if(session()->get('role_key') == 1)
                        <td style="min-width:var(--tcolMinXSM);">   
                            @php($tag_code = str_replace("-", "", $tg->id))
                            <button class="btn btn-info" data-bs-target="#infoTotalUsed-{{$tg->id}}" data-bs-toggle="modal" style="padding:8px 12px;" onclick="getTagTotal<?php echo $tag_code; ?>()"><i class="fa-solid fa-chart-pie"></i></button>
                            @include('event.tag.infoTotalUsed')
                        </td>
                        <td style="min-width:var(--tcolMinXSM);">
                            @if($tg->slug_name != "lecturer" && $tg->slug_name != "staff" && $tg->slug_name != "student")
                                <button class="btn btn-danger" data-bs-target="#deleteModal-{{$tg->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                            @else 
                                <button class="btn btn-info" data-bs-target="#infoDefaultTag-{{$tg->id}}" data-bs-toggle="modal" style="padding:8px 18px;"><i class="fa-solid fa-info"></i></button>
                                @include('event.tag.infoDefaultTag')
                            @endif
                        </td>
                        <td style="min-width:var(--tcolMinXSM);">
                            <div class="position-relative">
                                <button class="btn btn-primary px-3 position-absolute" style="right:10px; top:0;" type="button" id="section-more-tag-desc-{{$tg->tag_desc}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end shadow p-0" onclick="event.stopPropagation()" aria-labelledby="section-more-tag-desc-{{$tg->tag_desc}}" style="width:250px !important;">
                                    <span class="dropdown-item p-3">
                                        <h6>{{ __('messages.description') }}</h6>
                                        <form class="form-custom" method="POST" action="/event/tag/update/desc/{{$tg->id}}">
                                            @csrf
                                            <input name="update_type" value="desc" hidden>
                                            <input name="tag_name" value="{{$tg->tag_name}}" hidden>
                                            <textarea class="form-control" style="height: 100px" id="tag_desc" value="{{$tg->tag_desc}}" onblur="this.form.submit()" oninput="showSubmitMsg('{{$tg->id}}')" name="tag_desc" maxlength="255">{{$tg->tag_desc}}</textarea>
                                            <span class="warning-input" id="tag-desc-msg-{{$tg->id}}"></span>
                                        </form>
                                        <h6 class="my-2">{{ __('messages.cat') }}</h6>
                                        @if(session()->get('role_key') == 1)
                                            <form action="/event/tag/update/cat/{{$tg->id}}" method="POST">
                                                @csrf
                                                <input name="update_type" value="cat" hidden>
                                                <input name="tag_name" value="{{$tg->tag_name}}" hidden>
                                                <select class="form-select" aria-label="Default select example" name="tag_category" onchange="this.form.submit()">
                                                    @foreach($dct_tag as $dtag)
                                                        @if($dtag->slug_name == $tg->tag_category)
                                                            <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                                                        @else 
                                                            <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                    </span>
                                    <span class="dropdown-item properties-box">
                                        <h6 class="">{{ __('messages.props') }}</h6>
                                        <p>{{ __('messages.created_at') }} : <span class="date_holder_1">{{($tg->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></p>
                                        @if($tg->updated_at)
                                            <p>{{ __('messages.updated_at') }} : <span class="date_holder_2">{{($tg->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></p>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                    @else
                        <td style="min-width:var(--tcolMinSM);"> <div style="max-width:400px !important; word-break: break-all !important;">{{$tg->tag_desc}}</div></td>
                    @endif
                </tr>

                @include('event.tag.delete')
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const date_holder_1 = document.querySelectorAll('.date_holder_1');
    const date_holder_2 = document.querySelectorAll('.date_holder_2');

    date_holder_1.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    date_holder_2.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    function showSubmitMsg(id){
        document.getElementById("tag-desc-msg-"+id).innerHTML = '<i class="fa-solid fa-triangle-exclamation text-primary"></i>  or click outside the input to submit';
    }
</script>