<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Username @include('user.all.sorting.username')</th>
                <th scope="col">Email @include('user.all.sorting.email')</th>
                <th scope="col">Full Name @include('user.all.sorting.fullname')</th>
                <th scope="col">Properties @include('user.all.sorting.joined')</th>
                <th scope="col">Role</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="user-list-holder">
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
        </tbody>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
        </div>
    </table>
    <div id="empty_item_holder"></div>

</div>

<script>
    var page_new_req = 1;
    infinteLoadMoreUser(page_new_req);

    //Fix the sidebar & content page_new_req FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page_new_req++;
    //         infinteLoadMoreUser(page_new_req);
    //     } 
    // };

    function loadmore(route){
        page_new_req++;
        infinteLoadMoreUser(page_new_req);
    }

    function infinteLoadMoreUser(page_new_req) {  
        function getFind(filter, find){
            let trim = find.trim();
            if(find == null || trim === ''){
                return filter;
            } else {
                document.getElementById("title_search").value = trim;
                return trim;
            }
        }

        var name_filter = '<?= session()->get('filtering_fname')."_".session()->get('filtering_lname'); ?>';
        var order = '<?= session()->get('ordering_user_list'); ?>';

        var find = document.getElementById("title_search").value;
        document.getElementById("user-list-holder").innerHTML = "";
    
        $.ajax({
            url: "/api/v1/user/" + getFind(name_filter, find) + "/limit/100/order/" + order + "?page=" + page_new_req,
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
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getItemBg(date, acc){
                    if(date && acc){
                        return "normal"
                    } else if(!acc && !date){
                        return "waiting"
                    } else if(!acc && date){
                        return "suspend"
                    }
                }

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
                    var username = data[i].username;
                    var fullname = data[i].full_name;
                    var createdAt = data[i].created_at;
                    var updatedAt = data[i].updated_at;
                    var email = data[i].email;
                    var accStatus = data[i].is_accepted;
                    var accDate = data[i].accepted_at;
                    var role = data[i].role;

                    var elmt = " " +
                        '<tr class="tabular-item ' + getItemBg(accDate, accStatus) + '"> ' +
                            '<th scope="row">1</th> ' +
                            '<td>' + username + '</td> ' +
                            '<td class="email" title="Send Email" onclick="window.location = '+"'"+'mailto:'+email+"'"+'" href="mailto:' + email + '">' + email + '</td> ' +
                            '<td>' + fullname + '</td> ' +
                            '<td class="properties"> ' +
                                '<h6>Joined At</h6> ' +
                                '<a>' + getDateToContext(createdAt, "datetime") + '</a> ' +
                                '<h6>Updated At</h6> ' +
                                '<a>' + getDateToContext(updatedAt, "datetime") + '</a> ' +
                            '</td> ' +
                            '<td class="tabular-role-holder"> ' +
                                getRole(role) +
                            '</td> ' +
                        '</tr>';

                    $("#user-list-holder").prepend(elmt);
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