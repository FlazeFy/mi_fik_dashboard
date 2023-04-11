<style>
    .list-help-holder{
        width: 100%;
        display: flex;
        flex-direction: column;
        max-height: 500px;
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

<div class="list-help-holder accordion" id="accordion_help">
    @php($i = 0)

    @if(count($helplist) > 0)
        @foreach($helplist as $hl)
            <a class="helps_type_box" data-bs-toggle="collapse" data-bs-target="#collapse_category_{{$i}}" onclick="<?php echo "infinteLoadMore(1, '".$hl->help_type."')"; ?>">
                <h6>{{ucfirst($hl->help_type)}}</h6>
                @if($hl->total == 1)
                    <p>{{$hl->total - 1}} Category</p>
                @else 
                    <p>{{$hl->total}} Category</p>
                @endif
            </a>
            <div class="collapse p-2 pt-0" id="collapse_category_{{$i}}" data-bs-parent="#accordion_help">
                @include('about.help.category')
            </div>
            @php($i++)
        @endforeach
    @else 

    @endif
    @include('about.help.addType')
    <hr>
</div>