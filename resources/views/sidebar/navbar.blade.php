<style>
    .dd-profil{
        float: right;
        top: -10px;
    }
    .dd-profil .btn-secondary{
        background: white !important;
        border: none;
        min-width: 240px;
        height: 58px !important;
        border-radius: 30px;
        padding-left:6px;
        text-align:left;
    }
    .user-username{
        font-size:16px;
        font-weight:500;
        margin:0px;
        color: #F78A00;
    }
    .user-role{
        font-size:14px;
        font-weight:500;
        margin:0px;
        color:grey;
    }
    .user-image{
        border:2px solid #F78A00;
        width:45px;
        height:45px;
        cursor:pointer; /*if we can view other user profile*/
        border-radius:30px;
        margin-inline:auto;
        display: block;
    }
    
    /* Global */
    .dd-profil .dropdown-menu{
        border: none;
        margin: 10px 0 0 10px !important;
        width: 90%; 
        border-radius: 15px !important;
        padding-bottom: 0px;
    }
    .dd-profil .dropdown-menu hr{
        margin: 10px 0 10px 0;
        padding: 0;
    }
    .dd-profil .dropdown-item{
        color: #808080;
        font-weight: 500;
        padding: 10px 10px 10px 20px;
    }
    .dd-profil .dropdown-menu li{
        position: relative;
    }
    .sign-out-area{
        background: #F85D59;
        border-radius: 0 0  15px 15px;
        color: white !important;
    }
    .sign-out-area .dropdown-item{
        color: white !important;
    }
    .sign-out-area .dropdown-item .notification{
        position: absolute;
        right: 10px !important;
        top: 0 !important;
    }

    .sign-out-area .dropdown-item:hover{
        background: none;
    }
    .item-notification{
        position: absolute;
        right: 10px;
        top: 11px;
        color: #F78A00;
        font-size: 15px;
        font-weight: 500;
    }
</style>

<button type="button" id="sidebarCollapse" class="btn btn-primary" title="Toogle Sidebar">
    <i class="fa fa-bars"></i>
    <span class="sr-only">Toggle Menu</span>
</button>

<div class="dropdown dd-profil">
    <div class="btn btn-secondary shadow" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="row p-0 m-0">
            <div class="col-3 p-0">
                <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
            </div>
            <div class="col-9 p-0 pt-1">
                <h5 class="user-username">Budi</h5>
                <h6 class="user-role">Dosen DKV</h6>
            </div>
        </div>    
    </div>
    <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1">
        <li class="position-relative">
            <a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i> Profile</a>
            <div class="item-notification">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
            </div>
        </li>
        <li>
            <a class="dropdown-item" href="#"><i class="fa-solid fa-list-check me-2"></i> Task</a>
            <div class="item-notification">
                <i class="fa-solid fa-bell me-2"></i>3
            </div>
        </li>
        <li>
            <a class="dropdown-item" href="#"><i class="fa-solid fa-bell me-2"></i> Notification</a>
            <div class="item-notification" id="notif-holder"><img src='http://127.0.0.1:8000/assets/loading-notif.gif' style='height:24px; margin-top:-5px;'></div>
        </li>
        <div class="sign-out-area">
            <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign-Out</a></li>
        </div>
    </ul>
</div>

<script type="text/javascript">
    //Get data ajax
    $(document).ready(function() {
        clear();
    });
    
    function clear() {
        setTimeout(function() {
            update();
            clear();
        }, 5000); 
    }
    
    function update() {
        $.ajax({
            url: '/api/v1/notification',
            type: 'get',
            dataType: 'json',
            success: function(response){
                var response = response.data;
                var len = 0;

                $('#notif-holder').empty(); 
                if(response != null){
                    len = response.length;
                }
                
                if(len > 0){
                    var elmt = 
                        "<i class='fa-solid fa-bell me-2'></i>"+len ;
                        
                    $("#notif-holder").append(elmt);
                }else{
                    var elmt = 
                        "<span>" +
                            " " + //Check this again
                        "</span>";

                    $("#notif-holder").append(elmt);
                }
            }
       });
    }
</script>