<div class="modal fade" id="deleteModal-{{$tg->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">Are you sure want to delete "{{$tg->tag_name}}" tag</p>
                @if($info)
                    @foreach($info as $in)
                        @if($in->info_location == "delete_tag")
                            <div class="info-box {{$in->info_type}}">
                                <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                <?php echo $in->info_body; ?>
                            </div>
                        @endif
                    @endforeach    
                @endif            
                <form class="d-inline" action="/event/tag/delete/{{$tg->id}}" method="POST">
                    @csrf
                    <input value="{{$tg->tag_name}}" name="tag_name" hidden>
                    <button class="btn btn-danger float-end" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>