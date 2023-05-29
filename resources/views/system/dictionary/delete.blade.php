<div class="modal fade" id="deleteModal-{{$dc->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>Delete Info</h5>
                
                <form action="/system/dictionary/delete/{{$dc->id}}" method="POST">
                    @csrf 
                    <h6 class="text-center">Are you sure want to delete this '{{$dc->dct_name}}' dictionary with type {{$dc->dct_type}}</h6>
                    @foreach($info as $in)
                        @if($in->info_location == "delete_dictionary")
                            <div class="info-box {{$in->info_type}}">
                                <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                <?php echo $in->info_body; ?>
                            </div>
                        @endif
                    @endforeach
                    <input hidden name="dct_name" value="{{$dc->dct_name}}">
                    <button type="submit" class="btn btn-danger float-end">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

