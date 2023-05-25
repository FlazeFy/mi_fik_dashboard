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
                    @if(session()->get('profile_pic') == null)
                        @if(session()->get('role_key') == 0)
                            <img class="img img-fluid user-image" src="{{ asset('/assets/default_lecturer.png')}}" alt="{{ asset('/assets/default_lecturer.png')}}">
                        @elseif(session()->get('role_key') == 1)
                            <img class="img img-fluid user-image" src="{{ asset('/assets/default_admin.png')}}" alt="{{ asset('/assets/default_admin.png')}}">
                        @endif
                    @else
                        <img class="img img-fluid user-image" src="{{session()->get('profile_pic')}}" alt="{{session()->get('profile_pic') == null}}">
                    @endif
                </div>
                <div class="col-9 p-0 pt-1">
                    <h5 class="user-username"><?php echo session()->get('username_key'); ?></h5>
                    @if(session()->get("role_key") == 1)
                        <h6 class="user-role">Admin</h6>
                    @else 
                        <h6 class="user-role">Lecturer</h6>
                    @endif
                </div>
            </div>    
        </div>
        <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1" id="dd-menu-profile">
            <li class="position-relative">
                <a class="dropdown-item" href="/profile"><i class="fa-solid fa-user me-2"></i> Profile</a>
                <div class="item-notification">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                </div>
            </li>
            <li>
                <a class="dropdown-item" href="#"><i class="fa-solid fa-bell me-2"></i> Notification</a>
                <div class="item-notification" id="notif-holder"><img src="{{ asset('/assets/loading-notif.gif')}}" style='height:24px; margin-top:-5px;'></div>
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
                <form class='d-inline' action='/sign-out' method='POST' id="form-signout">
                    @csrf
                    <p style='font-weight:500;'>Are you sure want to sign out?</p>
                    <a onclick="signout()" class='btn btn-danger'>Sign Out</a>
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
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
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

    function signout() {
        $.ajax({
            url: "/api/v1/logout",
            type: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer <?= session()->get("token_key"); ?>"
            },
            success: function(data, textStatus, jqXHR) {
                sessionStorage.clear();
                $('#form-signout').submit();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 401) {
                    sessionStorage.clear();
                    window.location.href = "/";
                }
            }
        });
    }
</script>