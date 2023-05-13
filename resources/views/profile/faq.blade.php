<div class="imessage" id="imessage">
    @php($datebefore = "")

    @foreach($faq as $fq)
        @php($check = date('Y-m-d', strtotime($fq['created_at'])))
        @if($datebefore != $check)
            @php($datebefore = $check)
            <div class="date-chip">
                {{date('Y-m-d', strtotime($datebefore))}}
            </div>
        @endif

    
        <div class="from-{{$fq['question_from']}}">
            @if($fq['question_from'] == "them")
                <div class="box-replied">
                    {{$fq['msg_reply']}}
                </div>
            @endif
            {{$fq['msg_body']}}
        </div>
        <a class="from-{{$fq['question_from']}}-clock">{{($fq['created_at'])->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</a>
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

    var objDiv = document.getElementById("imessage");
    objDiv.scrollTop = objDiv.scrollHeight;
</script>