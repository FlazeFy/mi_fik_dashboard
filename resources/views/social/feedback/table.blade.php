<div class="text-nowrap table-responsive">
    <table class="table table-paginate" id="feedbackTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Feedback</th>
                <th scope="col">Rating</th>
                <th scope="col">Suggestion</th>
                <th scope="col">Created At</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feedback as $fb)
                <tr>
                    <td>{{$fb->feedback_body}}</td>
                    <td>
                        @php($total = $fb->feedback_rate)
                        @for($i = 0; $i < $total; $i++)
                            <i class="fa-solid fa-star" title="{{$i+1}} Stars" style="color: #ffd43b;"></i>
                        @endfor
                    </td>
                    <td>{{$fb->type}}</td>
                    <td>{{date("d/m/y h:i", strtotime($fb->created_at))}}</td>
                    <td><button class="btn btn-danger" data-bs-target="#deleteModal-{{$fb->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button></td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>