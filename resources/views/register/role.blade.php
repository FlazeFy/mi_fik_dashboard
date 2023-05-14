<div>
    <h4 class="text-primary">Available Role</h4>
    <div class="" id="data-wrapper"></div>
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
    <div id="empty_item_holder"></div>
    <span id="load_more_holder" style="display: flex; justify-content:end;"></span>
</div>
<span id="btn-next-role-holder">
    <button class="btn-next-steps locked" id="btn-next-ready" onclick="warn('role')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>

<script type="text/javascript">
    var page = 1;

    function loadTag() {        
        $.ajax({
            url: "/api/v1/dictionaries/type/TAG-001",
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data;

            for(var i = 0; i < data.length; i++){
                //Attribute
                var slug_name = data[i].slug_name;
                var dct_name = data[i].dct_name;

                var elmt = " " +
                    "<div class=''> " +
                        "<h6 class='mt-2 mb-0'>" + dct_name + "</h6> " +
                        "<div class='' id='tag-cat-holder-" + slug_name + "'></div> " +
                        "<div class='auto-load-" + slug_name + " text-center'> " +
                            "<svg version='1.1' id='L9' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' " +
                                "x='0px' y='0px' height='60' viewBox='0 0 100 100' enable-background='new 0 0 0 0' xml:space='preserve'> " +
                                "<path fill='#000' " +
                                    "d='M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50'> " +
                                    "<animateTransform attributeName='transform' attributeType='XML' type='rotate' dur='1s' " +
                                        "from='0 50 50' to='360 50 50' repeatCount='indefinite' /> " +
                                "</path> " +
                            "</svg> " +
                        "</div> " +
                        "<div id='empty_item_holder_" + slug_name + "'></div> " +
                        "<span id='load_more_holder_" + slug_name + "' style='display: flex; justify-content:end;'></span> " +
                    "</div>";

                loadTagByCat(slug_name);

                $("#data-wrapper").append(elmt);   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#empty_item_holder").html("<div class='err-msg-data'><img src='{{ asset('/assets/nodata2.png')}}' class='img' style='width:280px;'><h6 class='text-secondary text-center'>Sorry but we not found any tag category</h6></div>");
            } else {
                // handle other errors
            }
        });
    }

    function loadTagByCat(cat) {        
        $.ajax({
            url: "/api/v1/tag/cat/" + cat + "/20?page="+page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                $('.auto-load-' + cat).show();
            }
        })
        .done(function (response) {
            $('.auto-load-' + cat).hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder-' + cat).html('<button class="btn content-more-floating my-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more</button>');
            } else {
                $('#load_more_holder-' + cat).html('<h6 class="btn content-more-floating my-3 p-2">No more role to show</h6>');
            }

            if (total == 0) {
                $('#empty_item_holder-' + cat).html("<img src='http://127.0.0.1:8000/assets/nodata.png' class='img nodata-icon'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load-'+cat).html("<h5 class='text-primary'>Woah!, You have see all the role</h5>");
                return;
            } else {
                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var tag_name = data[i].tag_name;

                    var elmt = " " +
                        "<button class='btn btn-tag' title='Select this role'>" + tag_name + "</button>";

                    $("#tag-cat-holder-" + cat).append(elmt);   
                }
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load-'+cat).hide();
                $("#empty_item_holder_" + cat).html("<h6 class='text-secondary text-center'>No role available</h6>");
            } else {
                // handle other errors
            }
        });
    }
</script>