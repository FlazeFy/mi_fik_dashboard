<div class="text-nowrap table-responsive">
    <table class="table table-paginate" id="tagTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">ID</th>
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
                    <th scope="row">{{$tg->id}}</th>
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
                    <td>{{date("d/m/y h:i", strtotime($tg->updated_at))}}</td>
                    <td><button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>