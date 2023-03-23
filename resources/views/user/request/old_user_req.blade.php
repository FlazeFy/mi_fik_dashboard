<style>
    #load_more_holder_old_req{
        position: absolute;
        bottom: -10px;
        right: 15px;
    }
</style>

<div class="incoming-req-box">
    <h5 class="text-secondary fw-bold"><span class="text-primary" id="total_old_req"></span> Incoming Request</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:15px; top:0px;" type="button" id="section-more-old-req" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-old-req">
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-check text-success"></i> Accept All</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-xmark text-danger"></i>&nbsp Reject All</a>
    </div>

    <div class="user-req-holder" id="data_wrapper_old_req">
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
    <div id="empty_item_holder_old_req"></div>
    <span id="load_more_holder_old_req" style="display: flex; justify-content:center;"></span>
    </div>
</div>

<script>
    var page_old_req = 1;
    infinteLoadMore_old_req(page_old_req);

    //Fix the sidebar & content page_old_req FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page_old_req++;
    //         infinteLoadMore(page_old_req);
    //     } 
    // };

    function loadmore(route){
        page_old_req++;
        infinteLoadMore_old_req(page_old_req);
    }

    function infinteLoadMore_old_req(page_old_req) {        
        $.ajax({
            url: "/api/v1/user/request/old" + "?page=" + page_old_req,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_old_req != last){
                $('#load_more_holder_old_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_old_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            $('#total_old_req').text(total);

            if (total == 0) {
                $('#empty_item_holder_old_req').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
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

                function getContext(type, tag){
                    if(type == "add"){
                        var tags = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i != tag.length - 1){
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>, ';
                            } else {
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>';
                            }
                        }
                        return "Requested " + tags
                    } else if(type == "remove"){

                        return "Want to remove " + tags
                    }
                }

                function getCreatedAt(datetime){
                    const result = new Date(datetime);
                    const now = new Date(Date.now());
                    const yesterday = new Date();
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
                            return "Today at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                        //}
                    } else if(result.toDateString() === yesterday.toDateString()){
                        return "Yesterday at" + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                    } else {
                        return " " + result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
                    }
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var full_name = data[i].full_name;
                    var created_at = data[i].created_at;
                    var tag = data[i].tag_slug_name;
                    var type = data[i].request_type;

                    var elmt = " " +
                        '<button class="btn user-box" onclick="loadDetailGroup(' + "'" + slug_name + "'" + ')"> ' +
                            '<div class="row ps-2"> ' +
                                '<div class="col-2 p-0 py-3 ps-2"> ' +
                                    '<img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png"> ' +
                                '</div> ' +
                                '<div class="col-10 p-0 py-2 ps-2 position-relative"> ' +
                                    '<h6 class="text-secondary fw-normal">' + full_name + '</h6> ' +
                                    '<div style="width: 60%;"> ' +
                                        '<h6 class="user-box-desc">' + getContext(type, tag) + '</h6> ' +
                                        '<h6 class="user-box-date">' + getCreatedAt(created_at) + '</h6> ' +
                                    '</div> ' +
                                    '<a class="btn btn-icon-rounded-danger" style="position:absolute; right: 15px; top:15px;" title="Reject Request"><i class="fa-solid fa-xmark"></i></a> ' +
                                    '<a class="btn btn-icon-rounded-success" style="position:absolute; right: 55px; top:15px;" title="Accept Request"><i class="fa-solid fa-check"></i></a> ' +
                                '</div> ' +
                            '</div> ' +
                        '</button>';

                    $("#data_wrapper_old_req").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    function loadDetailGroup(slug){
        load_user_detail(slug)
        infinteLoadMoreTag()
    }
</script>