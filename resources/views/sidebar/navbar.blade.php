<style>
    #notif_holder_detail{
        padding: 5px 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 75vh;
        overflow-y: scroll;
    }
    #history_holder_detail{
        padding: 5px 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 75vh;
        overflow-y: scroll;
    }
    .btn-lang {
        background: transparent;
        border: none;
        color: var(--whiteColor) !important;
        padding: 0;
        margin-top: 20%;
        font-size: var(--textLG);
    }
</style>

<div class="navbar-holder">
    <button type="button" id="sidebarCollapse" class="btn btn-hide-bar" title="Toogle Sidebar">
        <i class="fa fa-bars"></i>
        <span class="sr-only">Toggle Menu</span>
    </button>
    <div class="navbar-title">
        <h5 id="greet"></h5>
    </div>
    <div class="dropdown dd-profil ms-5">
        <div class="btn btn-transparent" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            @if(!$isMobile)
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
                            <h6 class="user-role">Lecturer / Staff</h6>
                        @endif
                    </div>
                </div>    
            @else 
                <div class="navbar-image">
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
            @endif
        </div>
        <ul class="dropdown-menu p-0 shadow" aria-labelledby="dropdownMenuButton1" id="dd-menu-profile">
            <li class="position-relative">
                <a class="dropdown-item" href="/profile"><i class="fa-solid fa-user me-2"></i> {{ __('messages.profile') }}</a>
            </li>
            <li>
                <a class="dropdown-item" data-bs-toggle='modal' href="#history-modal" onclick="toogleHistory()"><i class="fa-solid fa-clock-rotate-left me-2"></i> {{ __('messages.history') }}</a>
            </li>
            <li>
                <a class="dropdown-item" data-bs-toggle='modal' href="#notif-modal" onclick="toogleDetail()"><i class="fa-solid fa-bell me-2"></i> {{ __('messages.notification') }}</a>
            </li>
            <button class="sign-out-area" data-bs-toggle='modal' data-bs-target='#sign-out-modal'>
                <li><a class="dropdown-item danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> {{ __('messages.signout') }}</a></li>
            </button>
        </ul>
    </div>
    <div class="d-inline float-end">
        <button class="btn-lang" type="button" id="dropdownLanguage" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-globe fa-lg"></i> @if(session()->get('locale') == "en") EN @else ID @endif</button>
        <ul class="dropdown-menu p-0 shadow" style="width:240px;" aria-labelledby="dropdownLanguage" id="dropdownLanguage">
            <li class="position-relative">
                <form action="/lang" method="POST">
                    @csrf
                    <input hidden name="lang" value="en">
                    <button class="dropdown-item position-relative" type="submit">
                        <div class="d-inline-block position-relative">
                            <img class='img rounded-circle shadow p-0' style="width:35px; height:35px;" src='http://127.0.0.1:8000/assets/en_lang.png'> 
                        </div>
                        <div class="d-inline-block position-absolute" style="top:15px; margin-left:10px;">
                            English
                        </div>
                    </button>
                </form>
            </li>
            <li>
                <form action="/lang" method="POST">
                    @csrf
                    <input hidden name="lang" value="id">
                    <button class="dropdown-item position-relative" type="submit">
                        <div class="d-inline-block position-relative">
                            <img class='img rounded-circle shadow p-0' style="width:35px; height:35px;" src='http://127.0.0.1:8000/assets/id_lang.png'> 
                        </div>
                        <div class="d-inline-block position-absolute" style="top:15px; margin-left:10px;">
                            Bahasa Indonesia
                        </div>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<div class='modal fade' id='history-modal' data-bs-backdrop="static" tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body text-start pt-4'>
                <button type='button' class='custom-close-modal' data-bs-dismiss='modal' onclick="cleanHistory()" aria-label='Close' title='Close pop up'><i class='fa-solid fa-xmark'></i></button>
                <h5>History</h5>
                <div id="history_holder_detail"></div>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='notif-modal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body text-start pt-4'>
                <button type='button' class='custom-close-modal' data-bs-dismiss='modal'onclick="cleanNotif()" aria-label='Close' title='Close pop up'><i class='fa-solid fa-xmark'></i></button>
                <h5>Notification</h5>
                <div id="notif_holder_detail"></div>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='sign-out-modal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body text-center pt-4'>
                <button type='button' class='custom-close-modal' data-bs-dismiss='modal' aria-label='Close' title='Close pop up'><i class='fa-solid fa-xmark'></i></button>
                <form class='d-inline' action='/sign-out' method='POST' id="form-signout">
                    @csrf
                    <p style='font-weight:500;'>{{ __('messages.signout_confirm') }}</p>
                    <a onclick="signout()" class='btn btn-danger'>{{ __('messages.signout') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var initial = 2000;
    var showDetail = false;
    var showHistory = false;
    var pageHistory = 1;
    var pageNotif = 1;

    getGreeting();
    function getGreeting() {
        const date = new Date();
        const hour = date.getHours();
        let greet;

        if (hour >= 3 && hour <= 12) {
            greet = "Good Morning";
        } else if (hour > 12 && hour <= 18) {
            greet = "Good Afternoon";
        } else if ((hour > 18 && hour <= 23) || (hour >= 0 && hour < 3)) {
            greet = "Good Night";
        }

        document.getElementById("greet").innerHTML = greet;
    }

    $(document).ready(function() {
        clear();
    });

    function toogleDetail(){
        if(showDetail == false){
            showDetail = true;
            notifDetail();
        } else {
            showDetail = false;
        }
    }

    function toogleHistory(){
        if(showHistory == false){
            showHistory = true;
            historyDetail();
        } else {
            showHistory = false;
        }
    }
    
    function clear() {
        setTimeout(function() {
            clear();
            if(showDetail == true){
                notifDetail();
            }
            if(showHistory == true){
                historyDetail();
            }
        }, initial); 

        if(initial == 2000){
            initial = 10000
        }
    }

    function notifDetail() {
        $.ajax({
            url: '/api/v1/notification/my?page='+pageNotif,
            type: 'get',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response){
                $("#load-page-"+pageNotif).remove();
                var response = response.data.data;
                var len = 0;

                if(response != null){
                    len = response.length;
                }
                
                if(len > 0){
                    for(var i = 0; i < len; i++){
                        var notifType = response[i].notif_type;
                        var notifTitle = response[i].notif_title;
                        var createdAt = response[i].created_at;
                        var notifBody = response[i].notif_body;

                        const elmt = `
                            <div class='p-3 rounded shadow mb-3 text-start'>
                                <h6>${notifTitle}</h6> 
                                <p class='mb-0'>${notifBody}</p>
                                <p class='text-secondary m-0' style='font-size:13px;'>${getDateToContext(createdAt,"full")}</p> 
                            </div>
                        `;

                        $("#notif_holder_detail").append(elmt);
                    }    
                }else{
                    const elmt = `
                        <span></span>
                    `;

                    $("#notif_holder_detail").append(elmt);
                }
            },
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log(jqXHR.responseJSON.message);
            failResponse(jqXHR, ajaxOptions, thrownError, "#notif_holder_detail", false, null, null);
        });;
    }

    var listNotif = document.getElementById("notif_holder_detail");
    listNotif.addEventListener('scroll', function() {
        var scrollPosition = listNotif.scrollTop + listNotif.clientHeight;
        
        if (scrollPosition >= listNotif.scrollHeight) {
            pageNotif++;

            var elmt = `
                <div class='d-block mx-auto' id='load-page-${pageNotif}'>
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player>
                </div>
            `;

            $("#notif_holder_detail").append(elmt);
            notifDetail();
        }
    });

    var listItem = document.getElementById("history_holder_detail");
    listItem.addEventListener('scroll', function() {
        var scrollPosition = listItem.scrollTop + listItem.clientHeight;
        
        if (scrollPosition >= listItem.scrollHeight) {
            pageHistory++;

            var elmt = `
                <div class='d-block mx-auto' id='load-page-${pageHistory}'>
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_7fwvvesa.json" background="transparent" speed="1" style="width: 320px; height: 320px; display:block; margin-inline:auto;" loop autoplay></lottie-player>
                </div>
            `;

            $("#history_holder_detail").append(elmt);
            historyDetail();
        }
    });

    function cleanHistory(){
        $("#history_holder_detail").empty();
    }

    function cleanNotif(){
        $("#notif_holder_detail").empty();
    }

    function historyDetail() {
        $.ajax({
            url: '/api/v1/history/my?page='+pageHistory,
            type: 'get',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response){
                $("#load-page-"+pageHistory).remove();
                
                var response = response.data.data;
                var len = 0;

                if(response != null){
                    len = response.length;
                }
                
                if(len > 0){
                    for(var i = 0; i < len; i++){
                        var historyType = response[i].history_type;
                        var createdAt = response[i].created_at;
                        var historyBody = response[i].history_body;

                        const elmt = `
                            <div class='p-3 rounded shadow mb-3 text-start'> 
                                <h6>${historyType}</h6> 
                                <p class='mb-0'>${historyBody}</p> 
                                <p class='text-secondary m-0' style='font-size:13px;'>${getDateToContext(createdAt,"full")}</p> 
                            </div>
                        `;

                        $("#history_holder_detail").append(elmt);
                    }
                        
                }else{
                    var elmt = `
                        <span></span>
                    `;

                    $("#history_holder_detail").append(elmt);
                }
            },
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            failResponse(jqXHR, ajaxOptions, thrownError, "#history_holder_detail", false, null, null);
        });;
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