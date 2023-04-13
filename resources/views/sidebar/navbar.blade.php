<style>
   
    
</style>

<div class="navbar-holder">
    <button type="button" id="sidebarCollapse" class="btn btn-hide-bar" title="Toogle Sidebar">
        <i class="fa fa-bars"></i>
        <span class="sr-only">Toggle Menu</span>
    </button>
    <div class="navbar-title">
        <h5>{{$greet}}</h5>
    </div>
    <div class="dropdown dd-profil">
        <div class="btn btn-transparent" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="row p-0 m-0">
                <div class="col-3 p-0">
                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                </div>
                <div class="col-9 p-0 pt-1">
                    <h5 class="user-username"><?php echo session()->get('username_key'); ?></h5>
                    <h6 class="user-role">Dosen DKV</h6>
                </div>
            </div>    
        </div>
        <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1" id="dd-menu-profile">
            <li class="position-relative">
                <a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i> Profile</a>
                <div class="item-notification">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                </div>
            </li>
            <li>
                <a class="dropdown-item" href="#"><i class="fa-solid fa-bell me-2"></i> Notification</a>
                <div class="item-notification" id="notif-holder"><img src='http://127.0.0.1:8000/assets/loading-notif.gif' style='height:24px; margin-top:-5px;'></div>
            </li>
            <button class="sign-out-area" data-bs-toggle='modal' data-bs-target='#sign-out-modal'>
                <li><a class="dropdown-item"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign-Out</a></li>
            </button>
        </ul>
    </div>
</div>

<div class='modal fade' id='sign-out-modal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body text-center pt-4'>
                <button type='button' class='custom-close-modal' data-bs-dismiss='modal' aria-label='Close' title='Close pop up'><i class='fa-solid fa-xmark'></i></button>
                <form class='d-inline' action='/sign-out' method='POST'>
                    @csrf
                    <p style='font-weight:500;'>Are you sure want to sign out?</p>
                    <button class='btn btn-danger' type='submit' onclick="sessionStorage.clear()">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var initial = 2000

    //Get data ajax
    $(document).ready(function() {
        clear();
    });
    
    function clear() {
        setTimeout(function() {
            update();
            clear();
        }, initial); 

        if(initial == 2000){
            initial = 15000
        }
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