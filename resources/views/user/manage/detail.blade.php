<style>
    .btn-tag{
        background:white;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:#414141;
        font-weight:400;
        border:1.5px solid #F78A00;
    }
    .btn-tag:hover, .btn-tag-selected{
        background:#F78A00;
        padding: 6px 8px;
        border-radius:12px;
        margin:4px;
        color:whitesmoke !important;
    }
    .btn-detail-config{
        color:whitesmoke !important;
        margin-right:6px;
    }
    .btn-detail-config.primary{
        background:#F78A00;
    }
    .btn-detail-config.danger{
        background:#D5534C;
    }
    .btn-detail-config.success{
        background:#58C06E;
    }
    .detail-box{
        height: 80vh;
        position: relative;
    }
    .config-btn-group{
        position: absolute;
        bottom: 10px;
        width: 95%;
    }
    .tag-manage-holder{

    }
</style>

<div class="detail-box">
    <h5 class="text-secondary fw-bold"><span class="text-primary" id="detail_body"></span> Detail</h5>
    <div class="user-req-holder" id="data_wrapper_user_detail">
        <!-- Loading -->
        <div class="auto-load text-center">
        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
            <path fill="#000"
                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
            </path>
        </svg>
    </div>
    <div id="empty_item_holder_user_detail"></div>
    <span id="load_more_holder_user_detail" style="display: flex; justify-content:center;"></span>
    </div>
</div>

<script>
    var page_tag = 1;

    function load_user_detail(slug_name_search) {        
        $.ajax({
            url: "/api/v1/user/" + slug_name_search,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data;

            if (data.length == 0) {
                $('#empty_item_holder_user_detail').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getContentImage(img){
                    if(img){
                        return 'url("http://127.0.0.1:8000/storage/'+img+'")';
                    } else {
                        return "url({{asset('assets/default_content.jpg')}})";
                    }
                }

                function getJoinedAt(datetime){
                    if(datetime){
                        const result = new Date(datetime);
                        const now = new Date(Date.now());
                        const yesterday = new Date();
                        var elmt = ""
                        yesterday.setDate(yesterday.getDate() - 1);
                        
                        //FIx this!!!
                        if(result.toDateString() === now.toDateString()){
                            // $start_date = new DateTime(datetime);
                            // $since_start = $start_date->diff(new DateTime(Date.now()));

                            // if(result.getHours() == now.getHours()){
                            //     const min = result.getMinutes() - now.getMinutes();
                            //     if(min <= 10 && min > 0){
                            //         return $since_start->m;
                            //     } else {
                            //         return  min + " minutes ago";    
                            //     }
                            // } else if(now.getHours() - result.getHours() <= 6){
                            //     return now.getHours() - result.getHours() + " hours ago";    
                            // } else {
                            elmt = "Today at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                            //}
                        } else if(result.toDateString() === yesterday.toDateString()){
                            elmt = "Yesterday at" + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                        } else {
                            elmt = result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
                        }

                        return "<span class='text-success'>Joined since " + elmt + "</span>"
                    } else {
                        return "<span class='text-danger fw-bold'>Waiting for admin approved</span>";
                    }
                }

                function getRoleArea(role){
                    var elmnt = ""

                    if(role){
                        for(var i = 0; i < role.length; i++){
                            elmnt += "<button class='btn btn-tag'>"+role[i]['tag_name']+"</button>"
                        }
                        return elmnt

                    } else {
                        return "<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-role'> " +
                            "<h6 class='text-center'>This user has no tag</h6>" ;
                    }
                }

                function getLifeButton(acc, acc_date){
                    if(!acc && !acc_date){
                        return '<button class="btn btn-detail-config success" title="Approve Account"><i class="fa-solid fa-check"></i></button>';
                    } else if(!acc && acc_date){
                        return '<button class="btn btn-detail-config success" title="Recover Account"><i class="fa-solid fa-rotate-right"></i></button>';
                    } else if(acc && acc_date){
                        return '<button class="btn btn-detail-config danger" title="Suspend Account"><i class="fa-solid fa-power-off"></i></button>';
                    } else {}
                }

                for(var i = 0; i < data.length; i++){
                    $("#data_wrapper_user_detail").empty();

                    //Attribute
                    var slug_name = data[i].slug_name;
                    var full_name = data[i].full_name;
                    var email = data[i].email;
                    var username = data[i].username;
                    var role = data[i].role;
                    var created_at = data[i].created_at;
                    var accepted_at = data[i].accepted_at;
                    var is_accepted = data[i].is_accepted;

                    var elmt = " " +
                        '<div class=""> ' +
                            '<div class="row"> ' +
                                '<div class="col-2 p-0 py-3 ps-2"> ' +
                                    '<img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png"> ' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + full_name + '</h6> ' +
                                    '<h6 class="user-box-desc">' + username + " | " + email + '</h6> ' +
                                    '<h6 class="user-box-date">' + getJoinedAt(accepted_at) + '</h6> ' +
                                '</div> ' +
                            '</div> ' +
                            '<h6 class="text-secondary"> Role</h6> ' +
                            getRoleArea(role) +
                            '<h6 class="text-secondary"> Manage Role</h6> ' +

                            '<div class="tag-manage-holder" id="data_wrapper_manage_tag"> ' +
                                '<div class="auto-load-tag text-center"> ' +
                                '<svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' +
                                    'x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve"> ' +
                                    '<path fill="#000" ' +
                                        'd="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"> ' +
                                        '<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" ' +
                                            'from="0 50 50" to="360 50 50" repeatCount="indefinite" /> ' +
                                    '</path> ' +
                                '</svg> ' +
                            '</div> ' +
                            '<div id="empty_item_holder_manage_tag"></div> ' +
                            '<span id="load_more_holder_manage_tag" style="display: flex; justify-content:center;"></span> ' +
                            '</div> ' +

                            '<div class="config-btn-group">' +
                                '<hr> ' +
                                '<button class="btn btn-detail-config primary" title="Manage role" onclick="infinteLoadMoreTag()"><i class="fa-solid fa-hashtag"></i></button>' +
                                '<a class="btn btn-detail-config primary" title="Send email" href="mailto:' + email + '"><i class="fa-solid fa-envelope"></i></a>' +
                                getLifeButton(is_accepted, accepted_at) +
                            '</div>' +
                        '</div>';

                    $("#data_wrapper_user_detail").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    // infinteLoadMoreTag(page_tag);

    // function loadmoretag(route){
    //     page_tag++;
    //     infinteLoadMoreTag(page_tag);
    // }

    function infinteLoadMoreTag(page_tag) { 
        $.ajax({
            url: "/api/v1/tag/12"+ "?page=" + page_tag,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load-tag').show();
            }
        })
        .done(function (response) {
            $('.auto-load-tag').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_tag != last){
                $('#load_more_holder_manage_tag').html('<button class="btn content-more my-3 p-2" style="max-width:180px;" onclick="loadmoretag()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_manage_tag').html('<h6 class="text-primary my-3">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder_manage_tag').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-tag').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                $("#data_wrapper_manage_tag").empty();

                for(var i = 0; i < data.length; i++){

                    //Attribute
                    var slug_name = data[i].slug_name;
                    var tag_name = data[i].tag_name;

                    var elmt = '<button class="btn btn-tag">' + tag_name + '</button> ';

                    $("#data_wrapper_manage_tag").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }
</script>