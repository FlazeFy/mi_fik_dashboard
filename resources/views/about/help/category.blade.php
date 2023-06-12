<div class="category_holder mb-2" id="category_holder-{{str_replace(' ', '', $hl->help_type)}}">
    <!-- Loading -->
    <div class="auto-load-{{str_replace(' ', '', $hl->help_type)}} text-center">
        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
    </div>
</div>
<div id="empty_item_holder-{{str_replace(' ', '', $hl->help_type)}}"></div>
<span id="load_more_holder-{{str_replace(' ', '', $hl->help_type)}}" style="display: flex; justify-content:center;"></span>


<script>
    var page = 1;
    var active_help_cat = "";
    var role = "<?= session()->get('role_key'); ?>";

    //Fix the sidebar & content page FE first to use this feature
    // window.onscroll = function() { 
    //     if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
    //         page++;
    //         infinteLoadMore(page);
    //     } 
    // };

    function loadmore<?= str_replace(' ', '', $hl->help_type); ?>(route){
        page++;
        infinteLoadMore<?= str_replace(' ', '', $hl->help_type); ?>(page);
    }

    function infinteLoadMore<?= str_replace(' ', '', $hl->help_type); ?>(page, type) {  
        $("#empty_item_holder-{{str_replace(' ', '', $hl->help_type)}}").empty();
        $("#load_more_holder-{{str_replace(' ', '', $hl->help_type)}}").empty();
        $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").empty();

        $.ajax({
            url: "/api/v1/help/" + type + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-{{str_replace(' ', '', $hl->help_type)}}').show();
            }
        })
        .done(function (response) {
            $('.auto-load-{{str_replace(' ', '', $hl->help_type)}}').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder-{{str_replace(' ', '', $hl->help_type)}}').html('<button class="btn content-more-floating p-1 mt-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder-{{str_replace(' ', '', $hl->help_type)}}').html('<h6 class="text-secondary" style="font-size:14px;">No more item to show</h6>');
            }

            $('#total').text(total);

            if (total == 0) {
                $('#empty_item_holder-{{str_replace(' ', '', $hl->help_type)}}').html("<img src='{{ asset('/assets/nodata.png')}}' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Category found</h6>");
                if(role == 1){
                    var elmt = " " +
                        '<div class="position-relative"> ' +
                            '<button class="btn btn-icon-rounded-success position-absolute" style="left:0px;" onclick="getInputHelpCat<?= str_replace(' ', '', $hl->help_type); ?>('+"'"+'{{$hl->help_type}}'+"'"+')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
                        '</div>';
                    $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").append(elmt);
                }
            } else if (data.length == 0) {
                $('.auto-load-{{str_replace(' ', '', $hl->help_type)}}').html("<h5 class='text-primary'>Woah!, You have see all the category</h5>");
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

                    if(i == data.length - 1 && role == 1){
                        var elmt = " " +
                        '<div class="position-relative"> ' +
                            '<button class="btn btn-icon-rounded-success position-absolute" style="left:0px;" onclick="getInputHelpCat<?= str_replace(' ', '', $hl->help_type); ?>(' + "'" + help_type + "'" +')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
                            '<button class="btn btn-category-help" id="'+ help_category.split(" ").join("") +'" onclick="loadDetailDesc(' + "'" + help_category + "'" + 
                                ', ' + "'" + help_body + "'" + ', ' + "'" + username + "'" + ', ' + "'" + updated_at + "'" + ', ' + "'" + id + "'" + ')"> ' +
                                ucEachWord(help_category) + 
                            '</button> ' +
                        '</div>';
                    } else {
                        var elmt = " " +
                        '<button class="btn btn-category-help" id="'+ help_category.split(" ").join("") +'" onclick="loadDetailDesc(' + "'" + help_category + "'" + 
                            ', ' + "'" + help_body + "'" + ', ' + "'" + username + "'" + ', ' + "'" + updated_at + "'" + ', ' + "'" + id + "'" + ')"> ' +
                            ucEachWord(help_category) + 
                        '</button>';
                    }

                    $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                var elmt = " " +
                    '<div class="position-relative" style="height:50px;"> ' +
                        '<button class="btn btn-icon-rounded-success position-absolute" style="left:10px;" onclick="getInputHelpCat<?= str_replace(' ', '', $hl->help_type); ?>('+"'"+'{{$hl->help_type}}'+"'"+')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
                        '<h6 class="text-center text-secondary position-absolute" style="top:10px; left:60px;">No category on this type</h6> ' +
                    '</div>';
                $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").append(elmt);
                $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").css("padding-bottom","35px");
            } else {
                // handle other errors
            }
        });
    }

    function getInputHelpCat<?= str_replace(' ', '', $hl->help_type); ?>(type){
        $(".d-inline.form-add-cat").remove();
        var elmt = " " +
            '<form class="d-inline form-add-cat" method="POST" action="/about/help/add/cat"> ' +
                '@csrf ' +
                '<div class="btn btn-category-help add"> ' +
                    '<input name="help_type" value="' + type + '" hidden> ' +
                    '<input class="form-control" name="help_category" type="text" maxlength="75" onblur="this.form.submit()" required> ' +
                    '<a class="warning-input"><i class="fa-solid fa-triangle-exclamation text-primary"></i> Press esc or click outside the input to submit</a> ' +
                '</div> ' +
            '</form>';
        $("#category_holder-{{str_replace(' ', '', $hl->help_type)}}").append(elmt);
    }

    function loadDetailDesc(cat, desc, user, updated, id){
        var cat2 = cat.split(" ").join("");
        setSelectedBtnStyle("background: #F78A00; color: #F5F5F5; border-radius: 10px;", "btn-category-help", " ", cat2);
        <?php
            if(session()->get('role_key') == 1 && session()->get('toogle_edit_help') == "true"){
                echo "loadRichTextDesc(desc, user, updated, cat);";
            } else {
                echo "loadDesc(desc);";
            }
        ?>
        id_body = id;
    }

    function loadDesc(desc){
        var desc_holder = document.getElementById("desc_holder_view");
        document.getElementById("no_cat_selected").hidden = true;
        desc_holder.innerHTML = " ";
        if(desc != "null"){
            desc_holder.innerHTML = "<h5>Help Detail</h5><br>"+desc;
        } else {
            desc_holder.innerHTML = "<h5>Help Detail</h5><br> " +
                "<img src='{{ asset('/assets/nodata.png')}}' class='img nodata-icon-req' style='height:25vh;'><br> " +
                "<h6 class='text-secondary text-center'>This category has no help</h6>";
        }
    }
</script>