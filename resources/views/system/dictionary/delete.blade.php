<div class="modal fade" id="deleteModal-{{$dc->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Delete Info</h5>
                
                <form action="/system/dictionary/delete/{{$dc->id}}" method="POST">
                    @csrf 
                    <h6 class="text-center">{{ __('messages.del_validation') }} '{{$dc->dct_name}}' dictionary with type {{$dc->dct_type}}</h6>
                    @include('components.infobox',['info'=>$info, 'location'=> 'delete_dictionary'])
                    <input hidden name="dct_name" value="{{$dc->dct_name}}">
                    <button type="submit" class="btn btn-danger float-end">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

