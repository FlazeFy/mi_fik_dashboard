<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
    .input-custom{
        padding:4px 4px 4px 25px;
    }
    .input-custom:hover, .input-custom:focus{
        background:#f0f0f0;
    }    
</style>

<h5 class="section-title">All Tag</h5>
<div class="@if(!$isMobile) table-responsive @endif ">
    @include('event.tag.filterCategory')
    <table class="table table-paginate" id="tagTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" style="min-width:120px;">Tag Name</th>
                <th scope="col" style="min-width:120px;">Category</th>
                @if(session()->get('role_key') == 1)
                    <th scope="col">Used</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Info</th>
                @else 
                    <th scope="col" style="min-width:240px;">Description</th>
                @endif  
            </tr>
        </thead>
        <tbody class="tabular-body">
            @foreach($tag as $tg)
                <tr class="tabular-item normal">
                    <td style="min-width:120px;">
                        <div style="max-width:160px !important; word-break: break-all !important;">{{$tg->tag_name}}</div>
                    </td>
                    <td style="min-width:120px;">
                        @foreach($dct_tag as $dtag)
                            @if($dtag->slug_name == $tg->tag_category)
                                {{$dtag->dct_name}}
                                @break
                            @endif
                        @endforeach
                    </td>
                    @if(session()->get('role_key') == 1)
                        <td>
                            @php($count = 0)

                            @foreach($mostTag as $mt)
                                @php($tagJson = json_decode($mt->content_tag))
                                
                                @foreach($tagJson as $tj)
                                    @if($tj->tag_name == $tg->tag_name)
                                        @php($count++)
                                    @endif
                                @endforeach   
                            @endforeach

                            {{$count}}
                        </td>
                        <td>
                            @if($tg->slug_name != "lecturer" && $tg->slug_name != "staff" && $tg->slug_name != "student")
                                <button class="btn btn-danger" data-bs-target="#deleteModal-{{$tg->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                            @else 
                                <button class="btn btn-info" data-bs-target="#infoDefaultTag-{{$tg->id}}" data-bs-toggle="modal" style="padding:8px 18px;"><i class="fa-solid fa-info"></i></button>
                                @include('event.tag.infoDefaultTag')
                            @endif
                        </td>
                        <td>
                            <div class="position-relative">
                                <button class="btn btn-primary px-3 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-tag-desc-{{$tg->tag_desc}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-more-tag-desc-{{$tg->tag_desc}}" style="width:250px !important;">
                                    <span class="dropdown-item p-3">
                                        <h6>Tag Description</h6>
                                        <form class="form-custom" method="POST" action="/event/tag/update/desc/{{$tg->id}}">
                                            @csrf
                                            <input name="update_type" value="desc" hidden>
                                            <input name="tag_name" value="{{$tg->tag_name}}" hidden>
                                            <textarea class="form-control" style="height: 100px" id="tag_desc" value="{{$tg->tag_desc}}" onblur="this.form.submit()" oninput="showSubmitMsg('{{$tg->id}}')" name="tag_desc" maxlength="255">{{$tg->tag_desc}}</textarea>
                                            <span class="warning-input" id="tag-desc-msg-{{$tg->id}}"></span>
                                        </form>
                                        <h6 class="my-2">Tag Category</h6>
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
                                        <h6 class="">Properties</h6>
                                        <p>Created At : <span class="date_holder_1">{{($tg->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></p>
                                        @if($tg->updated_at)
                                            <p>Updated At : <span class="date_holder_2">{{($tg->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</span></p>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                    @else
                        <td style="min-width:240px;"> <div style="max-width:400px !important; word-break: break-all !important;">{{$tg->tag_desc}}</div></td>
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
        document.getElementById("tag-desc-msg-"+id).innerHTML = '<i class="fa-solid fa-triangle-exclamation text-primary"></i> Press esc or click outside the input to submit';
    }
</script>