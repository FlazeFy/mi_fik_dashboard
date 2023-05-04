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
        <div id="empty_item_holder"></div>
        <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
        </div>
    </table>
</div>

<script>
    var page_new_req = 1;
    infinteLoadMore(page_new_req);

    //Fix the sidebar & content page_new_req FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page_new_req++;
    //         infinteLoadMore(page_new_req);
    //     } 
    // };

    function loadmore(route){
        page_new_req++;
        infinteLoadMore(page_new_req);
    }

    function infinteLoadMore(page_new_req) {  
        function getFind(filter, find){
            if(find== null || find.trim() === ''){
                return filter;
            } else {
                return find;
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

            $('#total_new_req').text(total);

            if (total == 0) {
                $('#empty_item_holder').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No users found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getDateContext(datetime){
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

                        return "<span class='text-secondary'>" + elmt + "</span>"
                    } else {
                        return "-"
                    }
                }

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
                                '<a>' + getDateContext(createdAt) + '</a> ' +
                                '<h6>Updated At</h6> ' +
                                '<a>' + getDateContext(updatedAt) + '</a> ' +
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
            console.log('Server error occured');
        });
    }
</script>