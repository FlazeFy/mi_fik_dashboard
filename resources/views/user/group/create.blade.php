<style>
    .user-check{
        background: white;
        border-radius: 20px;
        border: 1.5px solid #D9D9D9;
        overflow: hidden;
        margin-top: 6px;
        width: fit-content;
        block-size: fit-content;    
        margin-right: 4px;
        display: inline-block;
    }
    .user-check:hover{
        border: 1.5px solid #00c363;
        background: #00c363 !important;
    }
    .user-check:hover label span{
        color: #fff !important;
    }

    .user-check input:checked{
        border: 1.5px solid #00c363 !important;
    }
    
    .user-check label {
        float: left; 
        cursor: pointer !important;
    }
    
    .user-check label span {
        text-align: center;
        padding: 4px 12px;
        display: block;
    }
    
    .user-check label input {
        position: absolute;
        display: none !important;
        color: #fff !important;
    }
    .user-check label input + span{
        color: #343434;
    }  
    .user-check input:checked + span {
        background: #00c363 !important;
    }
    .Checked input:checked + span{
        background: #00c363 !important;
        /* background: transparent; */
    }
</style>

<script>
    let validation = [
        { id: "group_name", req: true, len: 75 },
        { id: "group_desc", req: false, len: 255 },
    ];
</script>

<button class="btn btn-submit position-absolute" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Group</button>
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Add Grouping</h5>
                
                <form action="/user/group/add" method="POST">
                    @csrf 
                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-floating">
                                <input type="text" class="form-control nameInput" id="group_name" name="group_name" maxlength="75" oninput="validateForm(validation)" required>
                                <label for="titleInput_event">Group Name</label>
                                <a id="group_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                            </div>
                            <div class="form-floating mt-2">
                                <textarea class="form-control" id="group_desc" name="group_desc" style="height: 140px" maxlength="255" oninput="validateForm(validation)"></textarea>
                                <label for="floatingTextarea2">Description (Optional)</label>
                                <a id="group_desc_msg" class="input-warning text-danger"></a>
                            </div>

                            @foreach($info as $in)
                                @if($in->info_location == "add_group")
                                    <div class="info-box {{$in->info_type}}">
                                        <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                        <?php echo $in->info_body; ?>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <span id="user-list-holder"></span>
                        </div>
                    </div>
                    <span id="submit_holder" class="float-end"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                </form>
            </div>
        </div>
    </div>
</div>

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
        $.ajax({
            url: "/api/v1/user/all_all/limit/100/order/first_name__DESC?page=" + page_new_req,
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
                    var slugName = data[i].slug_name;
                    var fullName = data[i].full_name;

                    var elmt = " " +
                        '<a class="user-check action py-2"> ' +
                            '<label> ' +
                                '<input class="" name="slug_name[]" type="checkbox" value="' + slugName + '" id="flexCheckDefault"> ' +
                                "<img class='img img-fluid rounded-circle d-block mx-auto' src='{{asset('assets/default_content.jpg')}}' style='height:45px; width:45px;'> " +
                                '<span style="font-size:12px;" class="text-secondary">' + fullName + '</span> ' +
                            '</label> ' +
                        '</a>';

                    $("#user-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }
</script>

