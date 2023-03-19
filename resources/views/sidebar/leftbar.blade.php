<style>
    /*Sidebar*/
    #sidebar {
        min-width: 300px;
        max-width: 300px;
        background: var(--background3);
        color: #5B5B5B;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
        flex-direction: column;
        height: 100vh;
        overflow-y: scroll;
    }
    #sidebar.active {
        margin-left: -300px;
    }
    #sidebar .logo {
        display: block;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    #sidebar .logo span {
        display: block;
    }
    #sidebar ul.components {
        padding: 0;
    }
    #sidebar ul li {
        font-size: 15.5px;
        padding:4px;
        font-weight:500;
        margin-bottom:18px;
    }
    #sidebar ul li > ul {
        margin-left: 10px;
    }
    #sidebar ul li > ul li {
        font-size: 14px;
    }
    #sidebar ul li a {
        padding: 10px;
        display: block;
        text-decoration:none;
        color: var(--text2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .accordion{
        border: none;
        background: transparent;
        padding: 0;
        margin: 0;
    }
    .btn-accordion-custom{
        border: none;
        /* padding: 0; */
        margin: 0;
        background: transparent;
        width: 100%;
        text-align: left;
        color: #5B5B5c;
        font-weight:500;
    }

    /*li*/
    #sidebar ul li.active, #sidebar ul li:hover {
        width:30vh;
        margin-left:-30px;
        padding: 5px 30px;
        border-radius: 0px 10px 10px 0px;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.6s;
        transition: all 0.6s;
    }
    #sidebar ul li.active, #sidebar ul li.active .btn-accordion-custom {
        background:#F78A00;
        color:whitesmoke !important;
    }
    #sidebar ul li:hover:not(.active) {
        color:#F78A00;
        border-left:12px solid #F78A00;
    }
    #sidebar ul li:hover:not(.active) .btn-accordion-custom {
        color:#F78A00;
    }

    /*li icon*/
    #sidebar ul li.active i, #sidebar ul li:hover i{
        margin-top:5px;
        color:#F78A00 !important;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.6s;
        transition: all 0.6s;
    }
    #sidebar ul li.active i{
        position: absolute;
        right:20px;
    }
    #sidebar ul li:hover:not(.active) i{
        position: absolute;
        right:60px;
    }

    #sidebar ul li.active > a {
        background: transparent;
        border-left: 4px solid var(--text2);
    }
    @media (max-width: 992px) {
        #sidebar {
            margin-left: -300px; 
        }
        #sidebar.active {
            margin-left: 0; 
        }
    }
    @media (max-width: 992px) {
        #sidebarCollapse span {
            display: none;
        }
    }
</style>

<nav id="sidebar" class="p-4 pt-5 position-relative">
    <div class="row my-3">
        <div class="col-4">
            <img class="w-100" src="{{asset('assets/logo.png')}}" alt='logo'
                style='display: block; margin-left: auto; margin-right: auto;'>
        </div>
        <div class="col-8 pt-2">
            <h1>MI-FIK</h1>
        </div>
    </div>
    <!--Main Navbar.-->
    <div class="accordion" id="accordionExample">
        <ul class="list-unstyled components my-5">
            <li class="<?php if(session()->get('active_nav') == "homepage"){ echo " active"; }?>">
                <a href="{{ url('/homepage') }}"><i class="fa-solid fa-home me-3"></i> Homepage</a>
            </li>
            <li class="accordion-header <?php if(session()->get('active_nav') == "event"){ echo " active"; }?>">
                <button class="btn btn-accordion-custom" type="button" data-bs-toggle="collapse" data-bs-target="#clpsEvent"><i class="fa-regular fa-calendar me-3"></i> Events</button>
            </li>
            <div class="collapse ps-4" id="clpsEvent" data-bs-parent="#accordionExample">
                <li class="">
                    <a href="{{ url('/event/tag') }}"><i class="fa-solid fa-hashtag me-3"></i> Tag</a>
                </li>
                <li class="">
                    <a href="{{ url('/event/location') }}"><i class="fa-solid fa-location-dot me-3"></i> Location</a>
                </li>
                <li class="">
                    <a href="{{ url('/event/calendar') }}"><i class="fa-solid fa-calendar me-3"></i> Calendar</a>
                </li>
            
            </div>
            <li class="<?php if(session()->get('active_nav') == "user"){ echo " active"; }?>">
                <a href="{{ url('/user') }}"><i class="fa-solid fa-user-pen me-3"></i> Manage User</a>
            </li>
            <li class="accordion-header <?php if(session()->get('active_nav') == "system"){ echo " active"; }?>">
                <button class="btn btn-accordion-custom" type="button" data-bs-toggle="collapse" data-bs-target="#clpsSystem"><i class="fa-solid fa-globe me-3"></i> System</button>
            </li>
            <div class="collapse ps-4" id="clpsSystem" data-bs-parent="#accordionExample">
                <li class="">
                    <a href="{{ url('/system/maintenance') }}"><i class="fa-solid fa-screwdriver-wrench me-3"></i> Maintenance</a>
                </li>
                <li class="">
                    <a href="{{ url('/system/notification') }}"><i class="fa-solid fa-bell me-3"></i> Notification</a>
                </li>
                <li class="">
                    <a href="{{ url('/system/dictionary') }}"><i class="fa-solid fa-book me-3"></i> Dictionary</a>
                </li>
            </div>
            <li class="<?php if(session()->get('active_nav') == "statistic"){ echo " active"; }?>">
                <a href="{{ url('/statistic') }}"><i class="fa-solid fa-chart-line me-3"></i> Statistic</a>
            </li>
            <li class="">
                <a href="{{ url('/history') }}"><i class="fa-solid fa-clock-rotate-left me-3"></i> History</a>
            </li>
            <li class="">
                <a href="{{ url('/setting') }}"><i class="fa-solid fa-gear me-3"></i> Setting</a>
            </li>
            <li class="">
                <a href="{{ url('/about') }}"><i class="fa-solid fa-regular fa-circle-info me-3"></i> About</a>
            </li>
        </ul>
        <!-- <button class="btn btn-transparent text-secondary position-absolute" style='bottom:20px;' title="Setting"><i class="fa-solid fa-gear"></i></button> -->
        <!-- <button class="btn btn-transparent text-danger fw-bolder position-absolute" style='bottom:20px; right:10px;' title="Sign Out"
            data-bs-toggle="modal" data-bs-target="#sign-out-modal"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign-Out</button> -->
    </div>
</nav>

@include('popup.signout')