<style>
    .modal-content{
        background-color: var(--whiteColor);
        border:none;
    }
</style>

@if(Session::has('granted_message'))
    <div class="modal fade" id="granted_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Access Granted</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/Success.png')}}" alt='granted.png' style='width:30%;'><br>
                <h7 class="m-2">{{ Session::get('granted_message') }}</h7>

                <h7 class="m-2">Are you a new user and need some guidance? See our <a class="btn btn-submit-form" href="{{url('/about')}}"><i class="fa-solid fa-arrow-right"></i> Help Center</a></h7>
            </div>
        </div>
    </div>
    </div>
@endif

<script>
    //Modal setting.
    $(window).on('load', function() {
        $('#granted_modal').modal('show');
    });
</script>