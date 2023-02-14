<style>
    .user-username{
        font-size:16px;
        font-weight:500;
        margin:0px;
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
    #task-holder{
        max-height:33vh;
        padding: 0px 3px;
        overflow:auto;
        margin-top:15px;
    }
    #notification-holder{
        max-height:33vh;
        padding: 0px 3px;
        overflow:auto;
        margin-top:15px;
    }
    .check-title{
        font-size:14.5px;
        margin:0px;
        color:#414141;
    }
    .check-subtitle{
        font-size:11px;
        color:grey;
        font-weight:normal;
    }

    /*Custom scrool*/
    #task-holder::-webkit-scrollbar, #notification-holder::-webkit-scrollbar {
        width: 10px;
    }
    #task-holder::-webkit-scrollbar-track, #notification-holder::-webkit-scrollbar-track{
        box-shadow: inset 0 0 3px grey; 
        border-radius: 10px;
    }
    #task-holder::-webkit-scrollbar-thumb, #notification-holder::-webkit-scrollbar-thumb {
        background: #ffab40;
        border-radius: 10px;
    }
    #task-holder::-webkit-scrollbar-thumb:hover, #notification-holder::-webkit-scrollbar-thumb:hover {
        background: #F78A00;
    }
</style>

<div class="row p-0 m-0 mt-5">
    <div class="col-3 p-0">
        <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
    </div>
    <div class="col-9 p-0">
        <h5 class="user-username">Budi</h5>
        <h6 class="user-role">Dosen DKV</h6>
    </div>
</div>

<!--Task-->
<div class="container-fluid mt-3 p-2">
    <button class="content-add" style="margin-top:-5px;" title="Add Task"><i class="fa-solid fa-plus"></i> Add</button>
    <h6 class="content-title"><span class="text-primary">5&nbsp</span> Task</h6>
    <div id="task-holder">
        @for($i = 0; $i < 5; $i++)
            <div class="container-fluid py-0 mt-2">
                <div class="row p-0">
                    <div class="col-2 p-0 py-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                        </div>
                    </div>
                    <div class="col-10 p-0">
                        <h6 class="check-title">Loh heh</h6>
                        <h6 class="check-subtitle"><span class="text-primary" style="font-weight:500;">Today at 8.30 AM</span> Lorem lorem ipsum</h6>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

<!--Notification-->
<div class="container-fluid mt-3 p-2">
    <button class="content-add" style="margin-top:-5px;" title="Remove all notification">Remove</button>
    <h6 class="content-title"><span class="text-primary">6&nbsp</span> Notification</h6>
    <div id="notification-holder">
        @for($i = 0; $i < 6; $i++)
            <div class="container-fluid rounded shadow-sm py-2 mt-2">
                <div class="row p-0">
                    <div class="col-3 p-1">
                        <div class="container-fluid bg-primary p-1 text-center text-white rounded">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                    </div>
                    <div class="col-9 p-0">
                        <h6 class="check-title">Maintenance</h6>
                        <h6 class="check-subtitle">Lorem lorem ipsum</h6>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>