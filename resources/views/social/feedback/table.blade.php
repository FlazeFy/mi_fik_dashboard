<div class="table-responsive position-relative">
    @include('social.feedback.filterCategory')
    <table class="table tabular table-paginate " id="feedbackTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">{{ __('messages.feedback') }}</th>
                <th scope="col">{{ __('messages.rating') }}</th>
                <th scope="col">{{ __('messages.suggestion') }}</th>
                <th scope="col">{{ __('messages.created_at') }}</th>
                <th scope="col">{{ __('messages.delete') }}</th>
            </tr>
        </thead>
        <tbody class="tabular-body">
            @foreach($feedback as $fb)
                <tr class="tabular-item normal">
                    <td><div style="word-break: break-all; width: 360px;">{{$fb->feedback_body}}</div></td>
                    <td style="width:140px;">
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