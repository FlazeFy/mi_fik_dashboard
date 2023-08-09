<style>
    .answer_suggestion a{
        cursor: pointer;  
        padding: 4px;
    }
    .answer_suggestion a:hover{
        background: var(--primaryLightBG);  
        border-radius: var(--roundedMini);
    }
</style>

<script>
    let validation = [
        { id: "question_answer", req: true, len: 500 }
    ];
</script>

<div class="position-relative">
    <form class="p-2 mt-2" action="/social/faq/answer" method="POST">
        @csrf
        <div class="form-floating">
            <input hidden value="" name="question_id" id="question_id">
            <input hidden value="" name="question_status" id="question_status">
            <input hidden value="" name="question_owner" id="question_owner">
            <textarea class="form-control" style="height: 270px" id="question_answer" name="question_answer" oninput="infinteLoadSuggest()" maxlength="500"></textarea>
            <label for="question_answer">FAQ Answer</label>
            <a id="question_answer_msg" class="text-danger my-2" style="font-size:13px;"></a>
            <div class="form-check form-switch position-absolute" style="right: 60px; bottom: -40px;">
                <input class="form-check-input me-2" type="checkbox" id="suggestion_search" style="width:40px; height: 20px;">
                <label class="form-check-label" for="suggestion_search">{{ __('messages.show_suggest') }}</label>
            </div>
        </div>
        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
    </form>
    <button class="btn btn-danger float-end" data-bs-target="#deleteModal" data-bs-toggle="modal" style="margin-top:-60px; margin-right:10px;"><i class="fa-solid fa-trash"></i></button>

    <div class="answer_suggestion" id="answer_suggestion">
        <!-- Loading -->
        <div class="auto-load-question text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </div>
    </div>
    <div id="empty_suggest_holder"></div>
</div>

<script>
    window.addEventListener('beforeunload', function(event) {
        if(document.getElementById("question_answer").value.trim() != '' && !isFormSubmitted && document.getElementById("question_status").value.trim() == '' ){
            event.preventDefault();
            event.returnValue = '';
        }
    });

    function infinteLoadSuggest() {
        validateForm(validation);

        var search = $("#question_answer").val();
        var active = $("#suggestion_search").prop('checked');

        if(active){
            $("#empty_suggest_holder").empty();
            $("#load_more_suggest").empty();
            $("#answer_suggestion").empty();

            $.ajax({
                url: "/api/v1/faq/answer/like/" + search,
                datatype: "json",
                type: "get",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                    $('.auto-load_suggest').show();
                }
            })
            .done(function (response) {
                $('.auto-load-question').hide();
                var data =  response.data;
                var total = response.data.length;
                var last = response.data.last_page;

                if (total == 0) {
                    $('#empty_suggest_holder').html("<img src='{{ asset('/assets/nodata.png')}}' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No suggestion found</h6>");
                } else if (data.length == 0) {
                    $('.auto-load_suggest').html("<h5 class='text-primary'>Woah!, You have see all the suggest</h5>");
                    return;
                } else {
                    for(var i = 0; i < data.length; i++){
                        //Attribute
                        var questionAnswer = data[i].question_answer;
                        var username = data[i].username;

                        var elmt = " " +
                            '<a class="remove_suggest" onclick="" title="Remove this suggestion"> ' +
                                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                                '<a onclick="loadQuestion(' + "'" + questionAnswer + "'" + ')" title="Use this answer, from ' + username + '">' + questionAnswer + '</a>';

                        $("#answer_suggestion").append(elmt);
                    }   
                }
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                failResponse(jqXHR, ajaxOptions, thrownError, "#answer_suggestion", false, null, null);
            });
        }
    }

    function loadQuestion(question){
       document.getElementById('question_answer').value = question;
    }
</script>