<div class="control-holder">
    @php($menu =  session()->get('active_nav'))
    @if($type == "vertical")
        @php($next = "fa-chevron-down")
        @php($prev = "fa-chevron-up")
    @else
        @php($next = "fa-chevron-right")
        @php($prev = "fa-chevron-left")
    @endif

    @if($i != $count - 1)
        @if(session()->get('active_subnav'))
            <form class="d-inline" action="/{{$menu}}/{{session()->get('active_subnav')}}/sortsection/{{session()->get('active_subnav')}}/down" method="POST">
        @else
            <form class="d-inline" action="/{{$menu}}/sortsection/{{$menu}}/down" method="POST">
        @endif
            @csrf
            <input hidden name="menu" value="{{json_encode($sort)}}">
            <input hidden name="section" value="{{$st}}">
            <button class="btn btn-icon-rounded-danger" title="Move {{$st}} to down section" type="submit"><i class="fa-solid {{$next}}"></i></button>
        </form>
    @endif

    @if($i != 0)
        @if(session()->get('active_subnav'))
            <form class="d-inline" action="/{{$menu}}/{{session()->get('active_subnav')}}/sortsection/{{session()->get('active_subnav')}}/up" method="POST">
        @else
            <form class="d-inline" action="/{{$menu}}/sortsection/{{$menu}}/up" method="POST">
        @endif
            @csrf
            <input hidden name="section" value="{{$st}}">
            <input hidden name="menu" value="{{json_encode($sort)}}">
            <button class="btn btn-icon-rounded-success" title="Move {{$st}} to up section" type="submit"><i class="fa-solid {{$prev}}"></i></button>
        </form>
    @endif
</div>