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
        margin-bottom:30px;
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
</style>

<div class="row p-0" id="eventHolder">
</div>

<script type="text/javascript">
    //Get data ajax
    $(document).ready(function() {
        clear();
    });
    
    function clear() {
        // setTimeout(function() {
        //     update();
        //     clear();
        // }, []); //Every 1500 milliseconds
        update();
    }
    

    function update(){
        $.ajax({
            url: 'https://mifik.leonardhors.site/api/content?page=1',
            type: 'get',
            dataType: 'json',
            success: function(response){
                var len = 0;
                var data = response.data;

                $('#body_barangTable').empty(); 
                if(response != null){
                    len = response.data.length;
                }

                //Date converter.
                function convertDate(datetime){
                    if(datetime == null){
                        return "-";
                    } else {
                        const result = new Date(datetime);
                        return result.getFullYear() + "-" + (result.getMonth() + 1) + "-" + result.getDate();
                    }
                }

                function getTag(tag){
                    if(tag != null){
                        return "<a class='btn-detail' title=''><i class='fa-solid fa-hashtag'></i> " + JSON.parse(tag).length + " </a>";
                    } else {
                        return "";
                    }
                }

                function getLoc(loc){
                    if(loc != null){
                        return 
                            "<span class='loc-limiter px-0 m-0'> " +
                                "<a class='btn-detail' title='Event Location'><i class='fa-solid fa-location-dot'></i> " + loc[0]->detail + " </a> " +
                            "</span> " ;
                    } else {
                        return "";
                    }
                }
                
                if(len > 0){
                    for(var i=0; i<len; i++){
                        //Attribute
                        var content_title = data[i].content_title;
                        var content_desc = data[i].content_desc;
                        var content_tag = data[i].content_tag;
                        var content_loc = data[i].content_loc;
                            
                        var element = 
                            "<div class='col-4'> " +
                                "<button class='card shadow event-box'> " +
                                    "<div class='card-header header-image' style='background-image: linear-gradient(rgba(0, 0, 0, 0.6),rgba(0, 0, 0, 0.55)), url({{asset('assets/content-2.jpg')}});'></div> " +
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
                                            "<p class='event-desc my-1'>" + content_desc + "</p> " +
                                        "</div> " +
                                        "<div class='row d-inline-block px-2'> " +

                                            getLoc(content_loc)
                                            "<a class='btn-detail' title='Event Started Date'><i class='fa-regular fa-clock'></i> - </a> " +
                                            getTag(content_tag)
                                    
                                        "</div> " +
                                    "</div> " +
                                "</button> " +
                            "</div> " ;
                            
                        $("#eventHolder").append(element);
                    }
                }else{
                    var element = 
                        "<div>" +
                            "<h4>Empty Data</h4>" +
                        "</div>";
                    $("#eventHolder").append(element);
                }
            }
        });
    }

    
</script>