<style>
    .event-holder{
        
    }
    .event-box{
        border-radius:14px;
        height:240px;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
        cursor:pointer;
        width: 100%;
        padding:0px;
        text-align:left;
    }
    .event-box:hover{
        transform: translateY(15px);
    }
    .event-box .event-title{
        font-weight:bold;
        font-size:14px;
        color:#404040 !important;
        margin:0px;
    }
    .event-box .event-subtitle{
        font-weight:500;
        font-size:12.5px;
        color:#5B5B5B !important;
        margin:0px;
    }
    .event-box .event-desc{
        font-weight:400;
        font-size:12px;
        color:#989898 !important;
        margin:0px;
        overflow: hidden; 
        text-overflow: ellipsis; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        line-clamp: 2; 
        -webkit-box-orient: vertical;
    }
    .btn-detail{
        text-decoration: none !important;
        border-radius: 6px;
        font-size:12px;
        font-weight:500;
        padding: 5px;
        color:#F78A00 !important;
        cursor:pointer;
    }
    .user-image-content{
        border:2px solid #F78A00;
        width:40px;
        height:40px;
        cursor:pointer; /*if we can view other user profile*/
        border-radius:30px;
        margin-inline:auto;
        display: block;
    }
    .header-image{
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: black;
        height:110px;
        width: 100%;
        border-radius: 14px 14px 0px 0px !important;
    }
    .loc-limiter{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 70%;
    }

    @media screen and (max-width: 1000px) {
        .user-image-content{ /*Need to be fixed*/
            position:absolute;
            margin-top:-37.5px;
            margin-left:35%;
        }
    }
    /* @media screen and (max-width: 768px) {
        
    } */
    .ajax-load{
        padding: 10px 0px;
        width: 100%;
    }
</style>

<div class="container mt-5 p-0">
    <div class="event-holder row mt-3" >        
    <div class="row p-0 m-0" id="data-wrapper"></div>
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
    
    <button class="btn content-more d-block mx-auto my-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>
</div>



</div>

<script type="text/javascript">
    var page = 1;
    infinteLoadMore(page);

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

    function infinteLoadMore(page) {
        $.ajax({
            url: "/api/v1/content?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function () {
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data;

            if (data.length == 0) {
                $('.auto-load').html("<h5 class='text-primary'>Woah!, You have see all the newest event :)</h5>");
                return;
            } else {
                function getEventLoc(loc){
                    if(loc){
                        return "<span class='loc-limiter px-0 m-0'> " +
                                "<a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> "+loc[0].detail+"</a> " +
                            "</span>";
                    } else {
                        return "";
                    }
                }

                function getDateMonth(date){
                    const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                    return ("0" + date.getDate()).slice(-2) + " " + month[date.getMonth() + 1];
                }

                function getHourMinute(date){
                    return ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);
                }

                function getEventTag(tag){
                    if(tag){
                        var str = "";

                        for(var i = 0; i < tag.length; i++){
                            if(i != tag.length - 1){
                                str += tag[i].tag_name +", ";
                            } else {
                                str += tag[i].tag_name;
                            }
                        }

                        return '<a class="btn-detail" title="'+ str +'"><i class="fa-solid fa-hashtag"></i>'+ tag.length +'</a>';
                    } else {
                        return "";
                    }
                }

                function getEventDate(dateStart, dateEnd){
                    if(dateStart && dateEnd){
                        const ds = new Date(dateStart);
                        const de = new Date(dateEnd);

                        if(ds.getFullYear() !== de.getFullYear()){
                            //Event year not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + "/" + ds.getFullYear() + " " + getHourMinute(ds) + 
                                " - " +
                                getDateMonth(de) + "/" + de.getFullYear() + " " + getHourMinute(de) + "</a>";

                        } else if(ds.getMonth() !== de.getMonth()){
                            //If month not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + "/" + ds.getFullYear() + " " + getHourMinute(ds) + 
                                " - " +
                                getDateMonth(de) + " " + getHourMinute(de) + "</a>";

                        } else if(ds.getDate() !== de.getDate()){
                            //If date not same
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + getHourMinute(ds) + 
                                " - " +
                                ("0" + de.getDate()).slice(-2) + " " + "0" + de.getHours().slice(-2) + ":" + ("0" + de.getMinutes()).slice(-2) + "</a>";

                        } else if(ds.getDate() === de.getDate()){
                            return "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> "+ 
                                getDateMonth(ds) + " " + getHourMinute(ds) + 
                                " - " +
                                getHourMinute(de) + "</a>";

                        }
                    } else {
                        return "";
                    }
                }

                //For now.
                function removeTags(str) {
                    if ((str===null) || (str===''))
                        return false;
                    else
                        str = str.toString();
                        
                    return str.replace( /(<([^>]+)>)/ig, '');
                }

                function getContentImage(img){
                    if(img){
                        return 'url("http://127.0.0.1:8000/storage/'+img+'")';
                    } else {
                        return "url({{asset('assets/default_content.jpg')}})";
                    }
                }

                for(var i = 0; i < data.length; i++){
                    //Attribute
                    var slug_name = data[i].slug_name;
                    var content_title = data[i].content_title;
                    var content_desc = data[i].content_desc;
                    var content_loc = data[i].content_loc;
                    var content_tag = data[i].content_tag;
                    var content_image = data[i].content_image;
                    var content_date_start = data[i].content_date_start;
                    var content_date_end = data[i].content_date_end;

                    var elmt = " " +
                        "<div class='col-lg-4 col-md-6 col-sm-12 pb-3'> " +
                            "<button class='card shadow event-box' onclick='location.href="+'"'+"/event/detail/" + slug_name + '"' +";"+"'> " +
                                "<div class='card-header header-image' style='background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), " + getContentImage(content_image) + ";'></div> " +
                                "<div class='card-body p-2 w-100'> " +
                                    "<div class='row px-2'> " +
                                        "<div class='col-lg-2 px-1'> " +
                                            "<img class='img img-fluid user-image-content' src='https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28' alt='username-profile-pic.png'> " +
                                        "</div> " +
                                        "<div class='col-lg-9 p-0 py-1'> " +
                                            "<h6 class='event-title'>" + content_title + "</h6> " +
                                            "<h6 class='event-subtitle'>[username]</h6> " +
                                        "</div> " +
                                    "</div> " +
                                    "<div style='height:45px;'> " +
                                        "<p class='event-desc my-1'>" + removeTags(content_desc) + "</p> " +
                                    "</div> " +
                                    "<div class='row d-inline-block px-2'> " +
                                        getEventLoc(content_loc) +
                                        getEventDate(content_date_start, content_date_end) +
                                        getEventTag(content_tag) +
                                    "</div> " +
                                "</div> " +
                            "</button> " +
                        "</div>";

                    $("#data-wrapper").append(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    }
</script>