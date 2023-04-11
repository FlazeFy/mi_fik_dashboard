<div class="category_holder mb-3" id="category_holder-{{$hl->help_type}}">
    <!-- Loading -->
    <div class="auto-load-{{$hl->help_type}} text-center">
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
<div id="empty_item_holder-{{$hl->help_type}}"></div>
<span id="load_more_holder-{{$hl->help_type}}" style="display: flex; justify-content:center;"></span>


<script>
    var page = 1;
    var active_help_cat = "";

    //Fix the sidebar & content page FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page++;
    //         infinteLoadMore(page);
    //     } 
    // };

    function loadmore(route){
        page++;
        infinteLoadMore(page);
    }

    function infinteLoadMore(page, type) {  
        $("#empty_item_holder-{{$hl->help_type}}").empty();
        $("#load_more_holder-{{$hl->help_type}}").empty();
        $("#category_holder-{{$hl->help_type}}").empty();

        $.ajax({
            url: "/api/v1/help/" + type + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load-{{$hl->help_type}}').show();
            }
        })
        .done(function (response) {
            $('.auto-load-{{$hl->help_type}}').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder-{{$hl->help_type}}').html('<button class="btn content-more-floating p-1 mt-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder-{{$hl->help_type}}').html('<h6 class="text-secondary" style="font-size:14px;">No more item to show</h6>');
            }

            $('#total').text(total);

            if (total == 0) {
                $('#empty_item_holder-{{$hl->help_type}}').html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Category found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-{{$hl->help_type}}').html("<h5 class='text-primary'>Woah!, You have see all the category</h5>");
                return;
            } else {
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var id = data[i].id;
                    var help_type = data[i].help_type;
                    var help_body = data[i].help_body;
                    var help_category = data[i].help_category;
                    var username = data[i].username;
                    var updated_at = data[i].updated_at;

                    var elmt = " " +
                        '<button class="btn btn-category-help" id="'+ help_category.split(" ").join("") +'" onclick="loadDetailDesc(' + "'" + help_category + "'" + 
                            ', ' + "'" + help_body + "'" + ', ' + "'" + username + "'" + ', ' + "'" + updated_at + "'" + ', ' + "'" + id + "'" + ')"> ' +
                            ucEachWord(help_category) + 
                        '</button>';

                    $("#category_holder-{{$hl->help_type}}").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }

    function loadDetailDesc(cat, desc, user, updated, id){
        var cat2 = cat.split(" ").join("");
        setSelectedBtnStyle("background: #F78A00; color: whitesmoke; border-radius: 10px;", "btn-category-help", " ", cat2);
        loadRichTextDesc(desc, user, updated, cat);
        id_body = id;
    }
</script>