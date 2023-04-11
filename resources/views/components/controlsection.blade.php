<div class="control-holder">
    @if($i != $count - 1)
        <form class="d-inline" action="{{session()->get('active_nav')}}/sortsection/down" method="POST">
            @csrf
            <input hidden name="menu" value="{{json_encode($sort)}}"></input>
            <input hidden name="section" value="{{$st}}"></input>
            <button class="btn btn-icon-rounded-danger" title="Move {{$st}} to down section" type="submit"><i class="fa-solid fa-chevron-down"></i></button>
        </form>
    @endif

    @if($i != 0)
        <form class="d-inline" action="{{session()->get('active_nav')}}/sortsection/up" method="POST">
            @csrf
            <input hidden name="section" value="{{$st}}"></input>
            <input hidden name="menu" value="{{json_encode($sort)}}"></input>
            <button class="btn btn-icon-rounded-success" title="Move {{$st}} to up section" type="submit"><i class="fa-solid fa-chevron-up"></i></button>
        </form>
    @endif
</div>