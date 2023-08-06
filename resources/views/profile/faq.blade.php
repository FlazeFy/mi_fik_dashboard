<script>
    let validation2 = [
        { id: "question_body", req: true, len: 255 }
    ];
    var dateBefore = "";
    var offsetHours = getUTCHourOffset();
    var now = new Date();
    var yesterday = now.setDate(now.getDate() - 1);
</script>

<div class="imessage" id="imessage">
    @if(count($faq) > 0)

        @foreach($faq as $fq)
            
            <span id="{{$fq['id']}}-date-chip-holder"></span>
            <script>
                var check = '<?= date('Y-m-d H:i:s', strtotime($fq['created_at'])); ?>';
                check = new Date(check);
                check.setUTCHours(check.getUTCHours() + offsetHours);
                check = getDateToContext(check, "date");

                if(dateBefore != check){
                    dateBefore = getDateToContext(check, "date");
                    if(getDateToContext(yesterday, "date") == check){
                        $("#{{$fq['id']}}-date-chip-holder").append(`
                            <div class="date-chip">Yesterday</div>
                        `);
                    } else if(getDateToContext(new Date(), "date") == check){
                        $("#{{$fq['id']}}-date-chip-holder").append(`
                            <div class="date-chip">Today</div>
                        `);
                    } else {
                        $("#{{$fq['id']}}-date-chip-holder").append(`
                            <div class="date-chip">
                                ${getDateToContext(check, "date")}
                            </div>
                        `);
                    }                    
                }
            </script>
        
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
    @else 
        <div class="text-center p-2">
            <img src="{{ asset('/assets/nodata3.png')}}" alt="{{ asset('/assets/nodata.png')}}" width="200" class="img-fluid d-block mx-auto mt-3">
            <h3 class="text-primary mt-3">You have no question yet</h3>
            <p class="text-secondary">You can ask about our app, and our admin will be respond your question. Your question may be public to other user, but it will anonym</p>
        </div>
    @endif

    <span id="toogle-msg-btn">
        <button class="btn btn-msg text-success" onclick="openForm()" title="Make a new question"><i class="fa-solid fa-paper-plane"></i></button>
    </span>

    <div class="msg-box d-none" id="msg-box">
        <form class="p-2 mt-2" action="/profile/faq" method="POST">
            @csrf
            <h5 class="text-secondary">Ask a Question</h5>
            <div class="form-floating my-2">
                <select class="form-select" id="floatingSelect" name="question_type" aria-label="Floating label select example">
                    @php($i = 0)

                    @foreach($dictionary as $dct)
                        @if($i == 0)
                            <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                        @else
                            <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                        @endif
                        @php($i++)
                    @endforeach
                </select>
                <label for="floatingSelect">Question Type</label>
            </div>
            <div class="form-floating">
                <textarea class="form-control" style="height: 100px" id="question_body" name="question_body" oninput="validateFormSecond(validation2)" maxlength="255"></textarea>
                <label for="question_body">Question Body</label>
                <a id="question_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <span id="submit_holder_second"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
        </form>
    </div>
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

    var btn_holder = document.getElementById("toogle-msg-btn");
    var msg_holder = document.getElementById("msg-box");
    function openForm(){
        btn_holder.innerHTML = `<button class="btn btn-msg text-danger" onclick="closeForm()" title="Cancel"><i class="fa-solid fa-xmark"></i></button>`;
        msg_holder.classList.remove("d-none");
    }

    function closeForm(){
        btn_holder.innerHTML = `<button class="btn btn-msg text-success" onclick="openForm()" title="Make a new question"><i class="fa-solid fa-paper-plane"></i></button>`;
        msg_holder.classList.add("d-none");
    }

    window.addEventListener('beforeunload', function(event) {
        if(document.getElementById("question_body").value.trim() != "" && !isFormSubmitted){
            event.preventDefault();
            event.returnValue = '';
        }
    });
</script>