<style>
    .list-help-holder{
        width: 100%;
        display: flex;
        flex-direction: column;
        max-height: 300px;
        overflow-y: auto !important;
    }
    .list-help-holder .helps_type_box{
        text-align: left;
        padding: 10px;
        height: 80px;
        cursor: pointer;
        border: 1.5px solid #F78A00;
        border-radius:10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
    .list-help-holder .helps_type_box:hover{
        background: #F78A00;
    }
    .list-help-holder .helps_type_box h6{
        color: #F78A00;
        font-size: 18px;
    }
    .list-help-holder .helps_type_box p{
        color: #414141;
        font-size: 14px;
    }
    .list-help-holder .helps_type_box:hover h6, .list-help-holder .helps_type_box:hover p{
        color: whitesmoke;
    }
</style>

<div class="list-help-holder">
    @if(count($helplist) > 0)
        @foreach($helplist as $hl)
            <a class="helps_type_box">
                <h6>{{ucfirst($hl->help_type)}}</h6>
                <p>3 Category</p>
            </a>
        @endforeach
    @else 

    @endif
    @include('about.help.addType')
    <hr>
</div>