<style>
    .toast-body.rounded-bottom{
        background:var(--toast2);
        color:var(--text);
    }
    .toast-header{
        background:var(--toast1);
        color:var(--text);
    }
</style>

<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 11">
    <div id="success_toast" class="toast hide shadow rounded-top" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img class="mx-2" src="{{asset('assets/Success.png')}}" alt='success.png' style='width:22px;'>
            <h6 class="me-auto mt-1 ">{{ __('messages.success') }}</h6>
            <small>Recently</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body rounded-bottom" id="success_toast_msg"></div>
    </div>
</div>

<div class="modal fade" id="error_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('messages.failed') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/Failed.png')}}" alt='failed.png' style='width:30%;'><br>
                <h7 class="m-2" id="err_modal_msg"></h7>
            </div>
        </div>
    </div>
</div>
