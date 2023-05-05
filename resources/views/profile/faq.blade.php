<div class="imessage">
    @foreach($faq as $fq)
        <p class="from-me">{{$fq->question_body}}</p>
        <a class="from-me-clock">{{date('H:i a', strtotime($fq->created_at))}}</a>

        @if($fq->question_answer)
            <p class="from-them">{{$fq->question_answer}}</p>
            <a class="from-them-clock">{{date('H:i a', strtotime($fq->updated_at))}}</a>
        @endif
    @endforeach
</div>