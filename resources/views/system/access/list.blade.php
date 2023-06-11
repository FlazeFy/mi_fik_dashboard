<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Username</th>
                <th scope="col">Sign-In At</th>
                <th scope="col">Properties</th>
                <th scope="col">Token</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="user-list-holder">
            <!-- Loading -->
            <div class="auto-load text-center">
            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
        </tbody>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
        </div>
    </table>
    <div id="empty_item_holder"></div>
</div>

<script>
    var page_new_req = 1;
    infiniteLoadAccess(page_new_req);

    function loadmore(route){
        page_new_req++;
        infiniteLoadAccess(page_new_req);
    }

    function infiniteLoadAccess(page_new_req) {  
        $.ajax({
            url: "/api/v1/user/access/history/50?page=" + page_new_req,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_new_req != last){
                $('#load_more_holder').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No users found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-secondary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getRole(tag){
                    if(tag){
                        var tags = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i != tag.length - 1){
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>, ';
                            } else {
                                tags += '<span class="text-primary fw-bold">#' + tag[i].tag_name + '</span>';
                            }
                        }
                        return tags
                    } else {
                        return '<span class="text-danger fw-bold">Doesn'+"'"+'t have a role'
                    }
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var id = data[i].id;
                    var type = data[i].type;
                    var token = data[i].token;
                    var last_used_at = data[i].last_used_at;
                    var expires_at = data[i].expires_at;
                    var created_at = data[i].created_at;
                    var updated_at = data[i].updated_at;
                    var admin_username = data[i].admin_username;
                    var user_username = data[i].user_username;
                    var admin_fullname = data[i].admin_fullname;
                    var user_fullname = data[i].user_fullname;

                    var elmt = " " +
                        '<tr class="tabular-item"> ' +
                            '<th scope="row">' + type + '</th> ' +
                            '<td> ' +
                                '<h6 class="m-0">Username : </h6> ' +
                                '<a class="text-secondary text-decoration-none">' + getName(admin_username, user_username) + '</a> ' +
                                '<h6 class="m-0">Fullname : </h6> ' +
                                '<a class="text-secondary text-decoration-none">' + getName(admin_fullname, user_fullname) + '</a> ' +
                            '</td> ' +
                            '<td>' + getDateToContext(created_at, "datetime") + '</td> ' +
                            '<td class="properties"> ' +
                                '<h6 class="m-0">Last Used At</h6> ' +
                                '<a class="text-secondary text-decoration-none">' + getDateToContext(last_used_at, "datetime") + '</a> ' +
                                '<h6 class="m-0">Updated At</h6> ' +
                                '<a class="text-secondary text-decoration-none">' + getDateToContext(updated_at, "datetime") + '</a> ' +
                            '</td> ' +
                            '<td> ' +
                                '<button class="btn btn-info px-3" type="button" id="section-token-id-' + id + '" data-bs-toggle="dropdown" aria-haspopup="true" ' +
                                    'aria-expanded="false"> ' +
                                    '<i class="fa-solid fa-key more"></i> ' +
                                '</button> ' +
                                '<div class="dropdown-menu dropdown-menu-end shadow" onclick="event.stopPropagation()" aria-labelledby="section-token-' + id + '" style="width:250px !important;"> ' +
                                    '<span class="dropdown-item px-3 py-2 position-relative" style="word-wrap: break-word !important;"> ' +
                                        '<h6 class="m-0">Access Token</h6> ' +
                                        '<a style="cursor:pointer; position:absolute; right:10px; top:0;" title="Copy token" onclick="messageCopy('+ "'" + token + "'" + ')"><i class="fa-solid fa-copy fa-lg text-primary"></i></a> ' +
                                        '<a class="text-secondary text-decoration-none mb-1 d-block" style="white-space: normal !important;">' + token + '</a> ' +
                                    '</span> ' +
                                '</div> ' +
                            '</td> ' +
                        '</tr>';

                    $("#user-list-holder").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#empty_item_holder").html("<div class='err-msg-data d-block mx-auto' style='margin-top:-30% !important;'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
        });
    }
</script>