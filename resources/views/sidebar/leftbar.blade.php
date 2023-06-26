<nav id="sidebar" class="p-4 pt-5 position-relative">
    <div class="row my-3">
        <div class="col-4">
            <img class="w-100" src="{{asset('assets/logo.png')}}" alt='logo'
                style='display: block; margin-left: auto; margin-right: auto;'>
        </div>
        <div class="col-8 pt-2">
            <h1>Mi-FIK</h1>
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