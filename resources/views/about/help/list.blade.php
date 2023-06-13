<style>
    .list-help-holder{
        width: 100%;
        display: flex;
        flex-direction: column;
        max-height: 500px;
        overflow-y: auto !important;
    }
    .list-help-holder .helps_type_box{
        text-align: left;
        padding: 10px;
        height: 80px;
        cursor: pointer;
        border: 1.5px solid #F78A00;
        border-radius:10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
    .list-help-holder .helps_type_box:hover{
        background: #F78A00;
    }
    .list-help-holder .helps_type_box h6{
        color: #F78A00;
        font-size: 18px;
    }
    .list-help-holder .helps_type_box p{
        color: #414141;
        font-size: 14px;
    }
    .list-help-holder .helps_type_box:hover h6, .list-help-holder .helps_type_box:hover p{
        color: #F5F5F5;
    }
</style>

@if(session()->get('role_key') == 1)
    @include('about.help.addType')
@endif
<div class="list-help-holder accordion" id="accordion_help">
    <!-- Loading -->
    <div class="auto-load text-center">
        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player> 
    </div>
    <div id="item_type_holder"></div>
</div>
<hr>

<script>
    loadType();
    var page = 1;
    var i = 0;
    var active_help_cat = "";
    var role = "<?= session()->get('role_key'); ?>";

    function loadType() {  
        $("#item_type_holder").empty();
        $.ajax({
            url: "/api/v1/help",
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
            var data =  response.data;
           
            function getTotal(total, cat){
                if(cat){
                    return total;
                } else {
                    return total - 1;
                }
            }

            for(var i = 0; i < data.length; i++){
                //Attribute
                var helpType = data[i].help_type;

                if(role == 1){
                    var id = data[i].id;
                    var total = data[i].total;
                    var helpCat = data[i].help_category;

                    var elmt = " " +
                        '<div class="helps_type_box" data-bs-toggle="collapse" data-bs-target="#collapse_category_'+id+'" onclick="infinteLoadCategory('+"'"+id+"'"+','+"'"+helpType+"'"+')"> ' +
                            '<h6>' + ucFirst(helpType) + '</h6> ' +
                            '<p>' + getTotal(total, helpCat) + ' Category</p> ' +
                        '</div> ' +
                        
                        '<div class="collapse p-2 pt-0" id="collapse_category_'+id+'" data-bs-parent="#accordion_help"> ' + 
                            '<div class="category_holder mb-2" id="category_holder-'+helpType.replace(" ", "")+'"> ' +
                                '<div class="auto-load-'+helpType.replace(" ", "")+' text-center"> ' +
                                    '<lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 150px; height: 150px; display:block; margin-inline:auto;" loop autoplay></lottie-player> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="empty_item_holder-'+helpType.replace(" ", "")+'"></div> ' +
                            '<span id="load_more_holder-'+helpType.replace(" ", "")+'" style="display: flex; justify-content:center;"></span> ' +
                        '</div>';
                } else {
                    var elmt = " " +
                        '<div class="helps_type_box" style="height:60px;" data-bs-toggle="collapse" data-bs-target="#collapse_category_'+i+'" onclick="infinteLoadCategory('+"'"+id+"'"+','+"'"+helpType+"'"+')"> ' +
                            '<h6 class="mt-2">' + ucFirst(helpType) + '</h6> ' +
                        '</div> ' +
                        
                        '<div class="collapse p-2 pt-0" id="collapse_category_'+i+'" data-bs-parent="#accordion_help"> ' + 
                            '<div class="category_holder mb-2" id="category_holder-'+helpType.replace(" ", "")+'"> ' +
                                '<div class="auto-load-'+helpType.replace(" ", "")+' text-center"> ' +
                                    '<lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 150px; height: 150px; display:block; margin-inline:auto;" loop autoplay></lottie-player> ' +
                                '</div> ' +
                            '</div> ' +
                            '<div id="empty_item_holder-'+helpType.replace(" ", "")+'"></div> ' +
                            '<span id="load_more_holder-'+helpType.replace(" ", "")+'" style="display: flex; justify-content:center;"></span> ' +
                        '</div>';
                }

                $("#item_type_holder").append(elmt);
            }   
            
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#item_type_holder").html("<div class='err-msg-data d-block mx-auto' style='margin-top:-30% !important;'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
        });
    }

    function loadmore(id, type){
        page++;
        infinteLoadCategory(id, type);
    }

    function infinteLoadCategory(id, type) {  
        $("#empty_item_holder-"+type.replace(" ", "")).empty();
        $("#load_more_holder-"+type.replace(" ", "")).empty();
        $("#category_holder-"+type.replace(" ", "")).empty();

        $.ajax({
            url: "/api/v1/help/" + type + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load-'+type.replace(" ", "")).show();
            }
        })
        .done(function (response) {
            $('.auto-load-'+type.replace(" ", "")).hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page != last){
                $('#load_more_holder-'+type.replace(" ", "")).html('<button class="btn content-more-floating p-1 mt-2" style="max-width:180px;" onclick="loadmore('+"'"+id+"'"+','+"'"+type+"'"+')">Show more <span id="textno"></span></button>');
            } else {
                $('#load_more_holder-'+type.replace(" ", "")).html('<h6 class="text-secondary" style="font-size:14px;">No more item to show</h6>');
            }

            $('#total').text(total);

            if (total == 0) {
                $('#empty_item_holder-'+type.replace(" ", "")).html("<img src='{{ asset('/assets/nodata.png')}}' class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Category found</h6>");
                if(role == 1){
                    var elmt = " " +
                        '<div class="position-relative"> ' +
                            '<button class="btn btn-icon-rounded-success position-absolute" style="left:0px;" onclick="getInputHelpCat('+"'"+type+"'"+')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
                        '</div>';
                    $("#category_holder-"+type.replace(" ", "")).append(elmt);
                }
            } else if (data.length == 0) {
                $('.auto-load-'+type.replace(" ", "")).html("<h5 class='text-primary'>Woah!, You have see all the category</h5>");
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
                            '<button class="btn btn-icon-rounded-success position-absolute" style="left:0px;" onclick="getInputHelpCat(' + "'" + type + "'" +')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
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

                    $("#category_holder-"+type.replace(" ", "")).append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                var elmt = " " +
                    '<div class="position-relative" style="height:50px;"> ' +
                        '<button class="btn btn-icon-rounded-success position-absolute" style="left:10px;" onclick="getInputHelpCat(' + "'" + type + "'" +')" title="Add new category"><i class="fa-solid fa-plus"></i></button> ' +
                        '<h6 class="text-center text-secondary position-absolute" style="top:10px; left:60px;">No category on this type</h6> ' +
                    '</div>';
                $("#category_holder-"+type.replace(" ", "")).append(elmt);
            } else {
                // handle other errors
            }
        });
    }

    function getInputHelpCat(type){
        $(".d-inline.form-add-cat").remove();
        console.log(type)
        var elmt = " " +
            '<form class="d-inline form-add-cat" method="POST" action="/about/help/add/cat"> ' +
                '@csrf ' +
                '<div class="btn btn-category-help add"> ' +
                    '<input name="help_type" value="' + type + '" hidden> ' +
                    '<input class="form-control" name="help_category" type="text" maxlength="75" onblur="this.form.submit()" required> ' +
                    '<a class="warning-input"><i class="fa-solid fa-triangle-exclamation text-primary"></i> Press esc or click outside the input to submit</a> ' +
                '</div> ' +
            '</form>';
        $("#category_holder-"+type.replace(" ", "")).append(elmt);
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