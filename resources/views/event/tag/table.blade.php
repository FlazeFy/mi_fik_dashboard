<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
    .page-item.active .page-link{
        background:#F78A00 !important;
        border:none;
        color:white;
    }
    .page-item .page-link{
        color:#414141;
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
            </tr>
        </thead>
        <tbody>
            @foreach($tag as $tg)
                <tr>
                    <td>
                        <form class="form-custom" method="POST" action="/event/tag/update/{{$tg->id}}">
                            @csrf
                            <i class="fa-solid fa-pencil position-absolute" style="top:3.5px; left:6px;"></i>
                            <input class="input-custom" name="tag_name" required value="{{$tg->tag_name}}" onblur="this.form.submit()">
                        </form>
                    </td>
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
                    <td>{{date("d/m/y h:i", strtotime($tg->updated_at))}}</td>
                    <td><button class="btn btn-danger" data-bs-target="#deleteModal-{{$tg->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button></td>
                </tr>

                @include('event.tag.delete')
            @endforeach
        </tbody>
    </table>
</div>