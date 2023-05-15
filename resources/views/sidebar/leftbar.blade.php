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
        font-size: 16px;
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
        width:95%;
        margin-left:-30px;
        padding: 5px 30px;
        border-radius: 0px 10px 10px 0px;
        -webkit-transition: all 0.6s;
        -o-transition: all 0.6s;
        transition: all 0.6s;
    }
    #sidebar ul li.active, #sidebar ul li.active .btn-accordion-custom {
        background:#F78A00;
        color:#F5F5F5 !important;
    }
    #sidebar ul li:hover:not(.active), #sidebar ul li.sub.active {
        color:#F78A00 !important;
        background: #FFFFFF;
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
            @php($group = false)
            @php($before = null)
            @php($total = count($menu))
            @php($i = 0)

            @foreach($menu as $mn)
                @if(!$mn->menu_name)
                    <li class="<?php if(session()->get('active_nav') == $mn->menu_group){ echo " active"; }?>">
                        <a href="{{ url($mn->menu_url) }}"><?= $mn->menu_icon; ?> {{ucfirst($mn->menu_group)}}</a>
                    </li>
                    @php($group = false)
                @else 
                    @php($menu_group = str_replace(' ', '', $mn->menu_group))

                    <!-- Fix this -->
                    @if($menu_group == "event")
                        <?php $icon = '<i class="fa-regular fa-calendar me-3"></i>'; ?>
                    @elseif($menu_group == "system")
                        <?php $icon = '<i class="fa-solid fa-globe me-3"></i>'; ?>
                    @elseif($menu_group == "manageuser")
                        <?php $icon = '<i class="fa-solid fa-user-pen me-3"></i>'; ?>
                    @elseif($menu_group == "social")
                        <?php $icon = '<i class="fa-regular fa-comments me-3"></i>'; ?>
                    @endif
                    
                    @if(!$group)
                        <li class="accordion-header <?php if(session()->get('active_nav') == $menu_group){ echo " active"; }?>">
                            <button class="btn btn-accordion-custom" type="button" data-bs-toggle="collapse" data-bs-target="#clps{{$menu_group}}"><?= $icon; ?> {{ucfirst($mn->menu_group)}}</button>
                        </li>
                        @php($group = true)
                    @endif
                    
                    @if($before == null || ($before != $menu_group && $group == true))
                        @if($menu_group == session()->get('active_nav'))
                            @php($show = "show")
                        @else
                            @php($show = "")
                        @endif

                        <div class="collapse {{$show}} ps-4" id="clps{{$menu_group}}" data-bs-parent="#accordionExample">
                        @php($before = $menu_group)
                    @endif

                    @if($mn->menu_name == session()->get('active_subnav'))
                        @php($active = "active")
                    @else
                        @php($active = "")
                    @endif
                    <li class="sub {{$active}}">
                        <a href="{{ url($mn->menu_url) }}"><?= $mn->menu_icon; ?> {{ucfirst($mn->menu_name)}}</a>
                    </li>
                   
                    @if($i <= $total)
                        @if(str_replace(' ', '', $menu[$i + 1]['menu_group']) != $before)
                            </div>
                            @php($group = false)
                        @endif
                    @endif

                @endif
                @php($i++)
            @endforeach
        </ul>
        <!-- <button class="btn btn-transparent text-secondary position-absolute" style='bottom:20px;' title="Setting"><i class="fa-solid fa-gear"></i></button> -->
        <!-- <button class="btn btn-transparent text-danger fw-bolder position-absolute" style='bottom:20px; right:10px;' title="Sign Out"
            data-bs-toggle="modal" data-bs-target="#sign-out-modal"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign-Out</button> -->
    </div>
</nav>