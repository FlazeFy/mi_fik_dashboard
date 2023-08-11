<style>
    .box-event-detail{
        margin: var(--spaceLG) 0 var(--spaceLG) 0;
        min-height: 80vh;
    }
    .event-detail-img-header{
        height:30vh;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        background-color: var(--darkColor);
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
        color: var(--primaryColor) !important;
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
        background: var(--primaryColor);
    }
    .event-detail{
        color: var(--primaryColor) !important;
        text-decoration:none;
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
  
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: var(--hoverBG); 
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
            <h6 class="text-primary mt-3">Tags</h6>
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
</script>