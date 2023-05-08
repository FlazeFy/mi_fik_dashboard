<div class="imessage">
    @foreach($faq as $fq)
        <p class="from-me">{{$fq->question_body}}</p>
        <a class="from-me-clock">{{($fq->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</a>

        @if($fq->question_answer)
            <p class="from-them">{{$fq->question_answer}}</p>
            <a class="from-them-clock">{{($fq->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</a>
        @endif
    @endforeach
</div>

<script>
    const date_holder_1 = document.querySelectorAll('.from-me-clock');
    const date_holder_2 = document.querySelectorAll('.from-them-clock');

    date_holder_1.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "24h");
    });

    date_holder_2.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "24h");
    });
</script>