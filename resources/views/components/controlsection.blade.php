<div class="control-holder">
    @if($i != $count - 1)
        @if(session()->get('active_subnav'))
            <form class="d-inline" action="/{{session()->get('active_nav')}}/{{session()->get('active_subnav')}}/sortsection/down" method="POST">
        @else
            <form class="d-inline" action="/{{session()->get('active_nav')}}/sortsection/down" method="POST">
        @endif
            @csrf
            <input hidden name="menu" value="{{json_encode($sort)}}">
            <input hidden name="section" value="{{$st}}">
            <button class="btn btn-icon-rounded-danger" title="Move {{$st}} to down section" type="submit"><i class="fa-solid fa-chevron-down"></i></button>
        </form>
    @endif

    @if($i != 0)
        @if(session()->get('active_subnav'))
            <form class="d-inline" action="/{{session()->get('active_nav')}}/{{session()->get('active_subnav')}}/sortsection/up" method="POST">
        @else
            <form class="d-inline" action="/{{session()->get('active_nav')}}/sortsection/up" method="POST">
        @endif
            @csrf
            <input hidden name="section" value="{{$st}}">
            <input hidden name="menu" value="{{json_encode($sort)}}">
            <button class="btn btn-icon-rounded-success" title="Move {{$st}} to up section" type="submit"><i class="fa-solid fa-chevron-up"></i></button>
        </form>
    @endif
</div>