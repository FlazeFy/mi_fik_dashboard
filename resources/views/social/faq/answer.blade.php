<style>
    .answer_suggestion a{
        cursor: pointer;  
        padding: 4px;
    }
    .answer_suggestion a:hover{
        background: #FFDeB4;  
        border-radius: 6px;
    }
    .remove_suggest{
        padding-right: 0 !important;
        border-radius: 6px;
        color: #D5534C;
        cursor: pointer;
    }
    .remove_suggest:hover{
        background: #D5534C !important;
        color: white;
    }
</style>

<script>
    let validation = [
        { id: "question_answer", req: true, len: 500 }
    ];
</script>

<div class="position-relative">
    <form class="p-2 mt-2" action="/event/tag/add" method="POST">
        @csrf
        <div class="form-floating">
            <textarea class="form-control" style="height: 270px" id="question_answer" name="question_answer" oninput="infinteLoadSuggest()" maxlength="500"></textarea>
            <label for="question_answer">FAQ Answer</label>
            <a id="question_answer_msg" class="text-danger my-2" style="font-size:13px;"></a>
            <div class="form-check form-switch position-absolute" style="right: 0; bottom: -40px;">
                <input class="form-check-input me-2" type="checkbox" id="suggestion_search" style="width:40px; height: 20px;">
                <label class="form-check-label" for="suggestion_search">Show Suggestion</label>
            </div>
        </div>
        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
    </form>

    <div class="answer_suggestion" id="answer_suggestion">
        <!-- Loading -->
        <div class="auto-load-question text-center">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>
    </div>
    <div id="empty_suggest_holder"></div>
</div>

<script>
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

                $('#total').text(total);

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
                console.log('Server error occured');
            });
        }
    }

    function loadQuestion(question){
       document.getElementById('question_answer').value = question;
    }
</script>