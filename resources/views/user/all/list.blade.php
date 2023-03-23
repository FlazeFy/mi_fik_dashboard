<table class="table">
    <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Full Name</th>
            <th scope="col">Properties</th>
        </tr>
    </thead>
    <tbody class="user-holder" id="user-list-holder">
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
    <div id="empty_item_holder_new_req"></div>
    <span id="load_more_holder_new_req" style="display: flex; justify-content:center;"></span>
    </div>
</table>

<script>
    var page_new_req = 1;
    infinteLoadMore_new_req(page_new_req);

    //Fix the sidebar & content page_new_req FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page_new_req++;
    //         infinteLoadMore(page_new_req);
    //     } 
    // };

    function loadmore_new_req(route){
        page_new_req++;
        infinteLoadMore(page_new_req);
    }

    function infinteLoadMore_new_req(page_new_req) {    
        var name_filter = '<?= session()->get('filtering_fname')."_".session()->get('filtering_lname'); ?>';
    
        $.ajax({
            url: "/api/v1/user/" + name_filter + "/100" + "?page=" + page_new_req,
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

            if(page_new_req != last){
                $('#load_more_holder_new_req').html('<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder_new_req').html('<h6 class="btn content-more-floating mb-3 p-2">No more item to show</h6>');
            }

            $('#total_new_req').text(total);

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
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

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var username = data[i].username;
                    var fullname = data[i].full_name;
                    var email = data[i].email;

                    var elmt = " " +
                        '<tr> ' +
                            '<th scope="row">1</th> ' +
                            '<td>' + username + '</td> ' +
                            '<td>' + email + '</td> ' +
                            '<td>' + fullname + '</td> ' +
                            '<td>@mdo</td> ' +
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