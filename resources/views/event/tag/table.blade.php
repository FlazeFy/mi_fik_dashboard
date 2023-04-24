<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
    .input-custom{
        border-radius:6px;
        padding:4px 4px 4px 25px;
        border:none;
    }
    .input-custom:hover, .input-custom:focus{
        background:#f0f0f0;
    }
    
    /*Icon color must change on input focus*/
</style>

<div class="table-responsive">
    <table class="table table-paginate" id="tagTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Tag Name</th>
                <th scope="col">Category</th>
                @if(session()->get('role_key') == 1)
                    <th scope="col">Used</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Info</th>
                @else 
                    <th scope="col">Description</th>
                @endif  
            </tr>
        </thead>
        <tbody>
            @foreach($tag as $tg)
                <tr>
                    <td>
                        <div style="max-width:160px !important; word-break: break-all !important;">{{$tg->tag_name}}</div>
                    </td>
                    <td>
                        <form action="/event/tag/update/cat/{{$tg->id}}" method="POST">
                            @csrf
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
                        <td><button class="btn btn-danger" data-bs-target="#deleteModal-{{$tg->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button></td>
                        <td>
                            <div class="position-relative">
                                <button class="btn btn-primary px-3 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-tag-desc-{{$tg->tag_desc}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="section-more-tag-desc-{{$tg->tag_desc}}" style="width:250px !important;">
                                    <span class="dropdown-item">
                                        <label class="mb-2">Tag Description</label><br>
                                        <form class="form-custom" method="POST" action="/event/tag/update/desc/{{$tg->id}}">
                                            @csrf
                                            <i class="fa-solid fa-pencil position-absolute" style="top:3.5px; left:6px;"></i>
                                            <input name="tag_name" value="{{$tg->tag_name}}" hidden>
                                            <input name="update_type" value="desc" hidden>
                                            <input class="input-custom" name="tag_desc" value="{{$tg->tag_desc}}" onblur="this.form.submit()">
                                        </form>
                                    </span>
                                    <h6>Properties</h6>
                                    <p class="m-0">{{date("d M y", strtotime($tg->created_at))}} at {{date("h:i", strtotime($tg->created_at))}}</p>
                                    @if($tg->updated_at)
                                        <p>{{date("d M y", strtotime($tg->updated_at))}} at {{date("h:i", strtotime($tg->updated_at))}}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    @else
                        <td>{{$tg->tag_desc}}</td>
                    @endif
                </tr>

                @include('event.tag.delete')
            @endforeach
        </tbody>
    </table>
</div>