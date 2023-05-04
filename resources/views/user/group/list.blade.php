<div class="table-responsive">
    <table class="table tabular">
        <thead>
            <tr>
                <th scope="col">Group Name @include('user.group.sorting.groupname')</th>
                <th scope="col">Description @include('user.group.sorting.groupdesc')</th>
                <th scope="col">Total @include('user.group.sorting.total')</th>
                <th scope="col">Properties @include('user.group.sorting.created')</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody class="user-holder tabular-body" id="group-list-holder">
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
        var order = '<?= session()->get('ordering_group_list'); ?>';
    
        $.ajax({
            url: "/api/v1/group/limit/100/order/" + order + "?page=" + page_new_req,
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

                function deleteGroup(id, slug, name){
                    var elmt = ' ' +
                        '<div class="modal fade" id="delete-group-'+slug+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog"> ' +
                                '<div class="modal-content"> ' +
                                    '<div class="modal-body text-center pt-4"> ' +
                                        '<button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<p style="font-weight:500;">Are you sure want to delete "' + name + '" group</p> ' +
                                        '@foreach($info as $in) ' +
                                            '@if($in->info_location == "delete_group") ' +
                                                '<div class="info-box {{$in->info_type}}"> ' +
                                                    '<label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br> ' +
                                                    "{!! $in->info_body !!} " +
                                                '</div> ' +
                                            '@endif ' +
                                        '@endforeach ' +
                                        '<form class="d-inline" action="/user/group/delete/'+id+'" method="POST"> ' +
                                            '@csrf ' +
                                            '<input hidden name="group_name" value="' + name + '"> ' +
                                            '<button class="btn btn-danger float-end" type="submit">Delete</button> ' +
                                        '</form> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ';
                    
                    return elmt;
                }

                function editGroup(id, slug, name, desc, updated){
                    var elmt = ' ' +
                        '<div class="modal fade" id="edit-group-'+slug+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> ' +
                            '<div class="modal-dialog"> ' +
                                '<div class="modal-content"> ' +
                                    '<div class="modal-body text-left pt-4"> ' +
                                        '<button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button> ' +
                                        '<h5>Edit Group</h5> ' +
                                        '<form class="d-inline" action="/user/group/edit/'+id+'" method="POST"> ' +
                                            '@csrf ' +
                                            '<input hidden name="group_name" value="' + name + '"> ' +
                                            '<div class="form-floating"> ' +
                                                '<input type="text" class="form-control nameInput" id="group_name" name="group_name" value="' + name + '" maxlength="75" oninput="" required> ' +
                                                '<label for="titleInput_event">Group Name</label> ' +
                                                '<a id="group_name_msg" class="text-danger my-2" style="font-size:13px;"></a> ' +
                                            '</div> ' +
                                            '<div class="form-floating mt-2"> ' +
                                                '<textarea class="form-control" id="group_desc" name="group_desc" style="height: 140px" maxlength="255" value="' + desc + '" oninput="">' + desc + '</textarea> ' +
                                                '<label for="floatingTextarea2">Description (Optional)</label> ' +
                                                '<a id="group_desc_msg" class="input-warning text-danger"></a> ' +
                                            '</div> ' +
                                            '<p>Last Updated : ' + getDateContext(updated) + '</p> '+
                                            '@foreach($info as $in) ' +
                                                '@if($in->info_location == "edit_group") ' +
                                                    '<div class="info-box {{$in->info_type}}"> ' +
                                                        '<label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br> ' +
                                                        "{!! $in->info_body !!} " +
                                                    '</div> ' +
                                                '@endif ' +
                                            '@endforeach ' +
                                            '<input hidden name="old_group_name" value="' + name + '">' +
                                            '<button class="btn btn-submit-form" type="submit" id="btn-submit"><i class="fa-solid fa-paper-plane"></i> Submit</button> ' +
                                        '</form> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ';
                    
                    return elmt;
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var groupName = data[i].group_name;
                    var slug = data[i].slug_name;
                    var id = data[i].id;
                    var groupDesc = data[i].group_desc;
                    var total = data[i].total;
                    var createdAt = data[i].created_at;
                    var updatedAt = data[i].updated_at;

                    var elmt = " " +
                        '<tr class="tabular-item"> ' +
                            '<td>' + groupName + '</td> ' +
                            '<td>' + groupDesc + '</td> ' +
                            '<td>' + total + '</td> ' +
                            '<td class="properties"> ' +
                                '<h6>Joined At</h6> ' +
                                '<a>' + getDateContext(createdAt) + '</a> ' +
                                '<h6>Updated At</h6> ' +
                                '<a>' + getDateContext(updatedAt) + '</a> ' +
                            '</td> ' +
                            '<td class="tabular-role-holder"> ' +
                                '<div class="position-relative"> ' +
                                    '<button class="btn btn-primary" type="button" data-bs-target="#edit-group-'+slug+'" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-pen-to-square"></i> ' +
                                    '</button> ' +
                                    editGroup(id, slug, groupName, groupDesc, updatedAt) +
                                    '<button class="btn btn-info" type="button" data-bs-target="add-rel" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-user-plus"></i> ' +
                                    '</button> ' +
                                    '<button class="btn btn-danger" type="button" data-bs-target="#delete-group-'+slug+'" data-bs-toggle="modal" aria-haspopup="true" ' +
                                        'aria-expanded="false"> ' +
                                        '<i class="fa-solid fa-solid fa-trash"></i> ' +
                                    '</button> ' +
                                    deleteGroup(id, slug, groupName) +
                                '</div> ' +
                            '</td> ' +  
                        '</tr>';

                    $("#group-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }
</script>