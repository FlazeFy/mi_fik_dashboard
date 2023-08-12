<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body p-4 pb-1">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5 class="text-primary">{{ __('messages.history') }}</h5><hr>
                @include('components.history', ['history' => $history])
            </div>
        </div>
    </div>
</div>