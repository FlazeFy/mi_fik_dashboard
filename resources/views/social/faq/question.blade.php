<style>
    .question_holder .question_box{
        text-align: left;
        padding: 10px;
        cursor: pointer;
        border: 1.5px solid #F78A00;
        border-radius:10px;
        margin-bottom: 10px;
        text-decoration: none;
        position: relative;
        width: 100%;
    }
    .question_holder .question_box.answered{
        border: 1.5px solid #00C363;
    }
    .question_holder .question_box:hover{
        background: #F78A00;
        color: #FFFFFF !important;
    }
    .question_holder .question_box:hover p{
        color: #FFFFFF;
    }
    .question_holder .question_box.answered:hover{
        background: #00C363;
    }
    .question_holder .question_box h6{
        color: #F78A00;
        font-size: 18px;
    }
    .question_holder .question_box.answered h6{
        color: #00C363;
    }
    .question_holder .question_box p{
        color: #414141;
        font-size: 14px;
    }
    .question_holder .question_box:hover h6, .question_holder .question_box:hover p{
        color: #F5F5F5;
    }
</style>

<div class="question_holder mb-3" id="question_holder">
    <!-- Loading -->
    <div class="auto-load-question text-center">
        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
    </div>
</div>
<div id="empty_question_holder"></div>
<span id="load_more_question" style="display: flex; justify-content:center;"></span>

<script>
    var page = 1;

    //Fix the sidebar & content page FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page++;
    //         infinteLoadMore(page);
    //     } 
    // };
    infinteLoadMore(page);

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

    function infinteLoadMore(page) {  
        $("#empty_question_holder").empty();
        $("#load_more_question").empty();
        $("#holder_question").empty();

        $.ajax({
            url: "/api/v1/faq/question/10?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load_question').show();
            }
        })
        .done(function (response) {
            $('.auto-load-question').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_question').html('<button class="btn content-more-floating p-1 mt-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_question').html('<h6 class="text-secondary" style="font-size:14px;">No more item to show</h6>');
            }

            $('#total').text(total);

            if (total == 0) {
                $('#empty_question_holder').html("<img src='{{ asset('/assets/nodata.png')}}' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Category found</h6>");
            } else if (data.length == 0) {
                $('.auto-load_question').html("<h5 class='text-primary'>Woah!, You have see all the question</h5>");
                return;
            } else {
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var id = data[i].id;
                    var questionBody = data[i].question_body;
                    var questionType = data[i].question_type;
                    var createdAt = data[i].created_at;
                    var updatedAt = data[i].updated_at;
                    var username = data[i].username;

                    if(data[i].question_answer){
                        var questionAnswer = data[i].question_answer;
                    } else {
                        var questionAnswer = " ";
                    }

                    if(updatedAt != null){
                        var status = "answered";
                    } else {
                        var status = "";
                    }

                    var elmt = " " +
                    '<button class="btn question_box ' + status + '" id="question_'+ id +'" onclick="loadDetailAnswer(' + "'" + questionAnswer + "'" + ', ' 
                        + "'" + username + "'" + ', ' + "'" + id + "'" + ', ' + "'" + status + "'" + ')"> ' +
                        '<h6>' + ucFirst(questionType) + '</h6> ' +
                        ucEachWord(questionBody) + 
                        '<p class="m-0 mt-2">' + getDateToContext(createdAt, "full") + ' by <span style="font-weight: 500;">' + username + '</span></p> ' +
                    '</button>';

                    $("#question_holder").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    function loadDetailAnswer(answer, user, id, status){
        if(status == "answered"){
            setSelectedBtnStyle("background: #00c363; color: #F5F5F5; border-radius: 10px;", "question_box", " ", 'question_'+ id);
        } else {
            setSelectedBtnStyle("background: #F78A00; color: #F5F5F5; border-radius: 10px;", "question_box", " ", 'question_'+ id);
        }
        
        loadAnswer(answer, id, status);
    }

    function loadAnswer(answer, id, status){
        var question_holder = document.getElementById("question_answer");
        var question_id = document.getElementById("question_id");
        var question_status = document.getElementById("question_status");

        question_holder.value = answer;
        question_id.value = id;
        question_status.value = status;

        validateForm(validation);
    }
</script>