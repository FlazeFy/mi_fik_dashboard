<style>
    .box-event-detail{
        margin:20px 0 20px 0;
        min-height: 80vh;
    }
    .event-detail-img-header{
        height:30vh;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: black;
        width: 100%;
        border-radius: var(--roundedLG) var(--roundedLG) 0 0;
        transition: all .25s linear;
    }
    .content-detail-views{
        position: absolute;
        left: 10px;
        bottom: 10px;
        color: var(--whiteColor);
    }
    .event-header-size-toogle{
        color: #F78A00 !important;
        background: none;
        border: none;
        position: absolute;
        top: 15px;
        left: 15px;
        cursor: pointer;
    }
    .event-tag-box{
        border-radius: var(--roundedMini);
        color:var(--whiteColor) !important;
        background: #F78A00;
    }
    .event-detail{
        color: #F78A00 !important;
        text-decoration:none;
    }
    .text-link{
        cursor: pointer;
    }
    .btn-copy-link{
        color: var(--shadowColor);
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-copy-link:hover{
        color: #F78A00;
    }
    .archive-holder{
        display: flex;
        flex-direction: column;
        height: 300px;
        padding-inline: 10px;
        overflow-y: scroll;
        overflow-x: hidden;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .archive-box{
        padding: 10px;
        margin-top: 14px;
        width: 200px !important;
    }
    .archive-count{
        font-size: 12px;
        font-weight: 400;
    }
    .dropdown-menu{
        border: none;
        margin: 10px 0 0 0 !important; 
        border-radius: var(--roundedMD) !important;
        padding-bottom: 0px;
    }
    .dropdown-menu-end .dropdown-item.active, .dropdown-menu-end .dropdown-item:active, .dropdown-menu-end .dropdown-item:hover{
        background: none !important;
    }

    .btn.archive-box{
        position: relative;
    }
    .btn.archive-box .icon-holder{
        display: none;
        color: var(--whiteColor);
        position: absolute;
        top: 20px;
        left: -32.5px; 
    }
    .btn.archive-box:hover{
        border-left: 50px solid #00C363;
        transition: all .30s linear;
    }
    .btn.archive-box:hover .icon-holder{
        display: inline;
    }
    .btn.archive-box.active{
        color: var(--whiteColor);
        background: #F78A00;
        border: none !important;
    }
    .btn.archive-box.active h6{
        color: var(--whiteColor) !important;
    }
    .btn.archive-box.active:hover{
        border-left: 50px solid #E74645 !important;
        transition: all .30s linear;
    }
    .btn-add-archive{
        margin-top: 10px;
        font-size: 13px;
        color: #212121;
    }
    .btn-add-archive:hover{
        color: #F78A00;
    }
  
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    ::-webkit-scrollbar-thumb {
        background: #888; 
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
</style>

<div class="box-event-detail">
    @include('event.edit.image')
    <div class="row p-3">
        <div class="col-lg-8">
            @include('event.edit.titledescinput')<hr>

            <div class="position-relative">
                <h6 class="text-primary mt-3">Manage Attachment</h6>
                @include('event.edit.attachment.add')  
                <br>
                @include('event.edit.attachment.show')   
            </div>             
        </div>
        <div class="col-lg-4">
            <h6 class="text-primary mt-3">Tag</h6>
                @include('event.edit.tag.show')
                @include('event.edit.tag.add')
            <hr>

            <h6 class="text-primary mt-3">Date & Time</h6>
                @include('event.edit.datepicker')
            <hr>

            <div class="position-relative">
                <h6 class="text-primary">Event Location</h6>
                @include('event.edit.maps.delete')
            </div>
            @include('event.edit.maps.add')
            <hr>

            <h6 class="text-primary mt-3">History</h6>
                @include('components.history')
            <hr>
        </div>
    </div>
</div>

<script>
    var i = 0;
    var j = 0;

    function copylink(id) {
        var copyText = document.getElementById("copy_url_"+id);

        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(copyText.value);
    }
</script>