<div class="modal fade" id="infoDefaultTag-{{$dtag->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">This "{{$dtag->dct_name}}" category is a default category</p>
                @include('components.infobox', ['info' => $info, 'location'=> "delete_default_category"])
            </div>
        </div>
    </div>
</div>