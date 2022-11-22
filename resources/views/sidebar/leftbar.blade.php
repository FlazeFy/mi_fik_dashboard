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
        font-size: 17px;
        padding:4px;
        font-weight:bold;
        margin-bottom:15px;
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
    #sidebar ul li.active {
        background:#F78A00;
        color:whitesmoke;
    }
    #sidebar ul li:hover:not(.active) {
        color:#F78A00;
        border-left:12px solid #F78A00;
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
    <ul class="list-unstyled components my-5">
        <li class="<?php if(session()->get('active_nav') == "dashboard"){ echo " active"; }?>">
            <a href="{{ url('/dashboard') }}"><i class="fa-solid fa-table-columns me-3"></i> Dashboard</a>
        </li>
        <li class="<?php if(session()->get('active_nav') == "event"){ echo " active"; }?>" data-bs-toggle="collapse" href="#collapseExample">
            <a><i class="fa-regular fa-calendar me-3"></i> Events</a>
        </li>
        <div class="collapse ps-4" id="collapseExample">
            <li class="">
                <a href="{{ url('/event/browse') }}"><i class="fa-solid fa-magnifying-glass me-3"></i> Browse</a>
            </li>
            <li class="">
                <a href="{{ url('/event/tag') }}"><i class="fa-solid fa-hashtag me-3"></i> Tag</a>
            </li>
            <li class="">
                <a href="{{ url('/event/location') }}"><i class="fa-solid fa-location-dot me-3"></i> Location</a>
            </li>
           
        </div>
        <li class="">
            <a href="{{ url('/statistic') }}"><i class="fa-solid fa-chart-line me-3"></i> Statistic</a>
        </li>
        <li class="">
            <a href="{{ url('/history') }}"><i class="fa-solid fa-clock-rotate-left me-3"></i> History</a>
        </li>
    </ul>
    <button class="btn btn-transparent text-secondary position-absolute" style='bottom:20px;' title="Setting"><i class="fa-solid fa-gear"></i></button>
    <button class="btn btn-transparent text-danger fw-bolder position-absolute" style='bottom:20px; right:10px;' title="Sign Out"
        data-bs-toggle="modal" data-bs-target="#sign-out-modal"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign-Out</button>
</nav>

@include('popup.signout')