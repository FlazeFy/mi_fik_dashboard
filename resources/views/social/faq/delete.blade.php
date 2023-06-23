<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <p style="font-weight:500;">Are you sure want to delete this question about</p>
                <p class="text-secondary" id="question-delete-verify"></p>
                @if($info)
                    @foreach($info as $in)
                        @if($in->info_location == "delete_question")
                            <div class="info-box {{$in->info_type}}">
                                <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                                <?php echo $in->info_body; ?>
                            </div>
                        @endif
                    @endforeach 
                @endif               
                <form class="d-inline" action="" id="form-delete-faq" method="POST">
                    @csrf
                    <button class="btn btn-danger float-end" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>