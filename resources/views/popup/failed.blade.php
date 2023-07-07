<style>
    .modal-content{
        background-color: var(--whiteColor);
        border:none;
    }
</style>

@if(Session::has('failed_message'))
    <div class="modal fade" id="error_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Failed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/Failed.png')}}" alt='failed.png' style='width:30%;'><br>
                <h7 class="m-2">{{ Session::get('failed_message') }}</h7>
            </div>
        </div>
    </div>
    </div>
@endif

<script>
    //Modal setting.
    $(window).on('load', function() {
        $('#error_modal').modal('show');
    });
</script>