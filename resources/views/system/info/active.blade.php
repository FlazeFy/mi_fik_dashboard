<div class="modal fade" id="activeModal-{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>{{ucfirst($status)}} Info</h5>
                
                <form action="/system/info/update/active/{{$in->id}}/{{$status}}" method="POST">
                    @csrf 
                    <h6 class="text-center">Are you sure want to {{$status}} this info</h6>
                    @include('components.infobox',['info'=>$info, 'location'=> 'active_info'])           
                    @if($status == "activate")
                        <button type="submit" class="btn btn-success float-end">Yes</button>
                    @else
                        <button type="submit" class="btn btn-danger float-end">Yes</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

