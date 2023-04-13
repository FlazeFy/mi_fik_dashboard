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

<div class="text-nowrap table-responsive">
    <table class="table table-paginate" id="tagTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Tag Name</th>
                <th scope="col">Used</th>
                <th scope="col">Created At</th>
                <th scope="col">Updated At</th>
                <th scope="col">Delete</th>
                <th scope="col">Info</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tag as $tg)
                <tr>
                    <td>{{$tg->tag_name}}</td>
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
                    <td>{{date("d/m/y h:i", strtotime($tg->created_at))}}</td>
                    <td>
                        @if($tg->updated_at)
                            {{date("d/m/y h:i", strtotime($tg->updated_at))}}
                        @else
                            -
                        @endif
                    </td>
                    <td><button class="btn btn-danger" data-bs-target="#deleteModal-{{$tg->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button></td>
                    <td>
                        <div class="position-relative">
                            <button class="btn btn-primary px-3 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-tag-desc-{{$tg->tag_desc}}" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical more"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-tag-desc-{{$tg->tag_desc}}">
                                <span class="dropdown-item">
                                    <label class="mb-2">Tag Description</label><br>
                                    <form class="form-custom" method="POST" action="/event/tag/update/{{$tg->id}}">
                                        @csrf
                                        <i class="fa-solid fa-pencil position-absolute" style="top:3.5px; left:6px;"></i>
                                        <input name="tag_name" value="{{$tg->tag_name}}" hidden>
                                        <input name="update_type" value="desc" hidden>
                                        <input class="input-custom" name="tag_desc" value="{{$tg->tag_desc}}" onblur="this.form.submit()">
                                    </form>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>

                @include('event.tag.delete')
            @endforeach
        </tbody>
    </table>
</div>