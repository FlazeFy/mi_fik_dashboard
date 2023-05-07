<div class="table-responsive">
    <table class="table tabular table-paginate" id="feedbackTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Feedback</th>
                <th scope="col">Rating</th>
                <th scope="col">Suggestion</th>
                <th scope="col">Created At</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody class="tabular-body">
            @foreach($feedback as $fb)
                <tr>
                    <td><div style="word-break: break-all; width: 360px;">{{$fb->feedback_body}}</div></td>
                    <td>
                        @php($total = $fb->feedback_rate)
                        @for($i = 0; $i < $total; $i++)
                            <i class="fa-solid fa-star" title="{{$i+1}} Stars" style="color: #ffd43b;"></i>
                        @endfor
                    </td>
                    <td>{{$fb->type}}</td>
                    <td>{{date("d/m/y H:i", strtotime($fb->created_at))}}</td>
                    <td><button class="btn btn-danger" data-bs-target="#deleteModal-{{$fb->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button></td>
                </tr>

                @include("social.feedback.delete")
            @endforeach
        </tbody>
    </table>
</div>