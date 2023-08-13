<div class="modal fade" id="editModal-{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>{{ __('messages.edit_notif') }}</h5>
                <div class="row my-2">
                    <form action="/system/notification/update" method="POST">
                        @csrf 
                        <div class="row">
                            <div class="col-lg-8 pb-2">
                                <div class="form-floating">
                                    <textarea class="form-control" id="bodyInput_{{$i}}" style="height:180px;" onkeyup="lengValidator(<?= $i; ?>)" onchange="lengValidator(<?= $i; ?>)">{{$nt['notif_body']}}</textarea>
                                    <label for="floatingTextarea2">{{ __('messages.body') }}</label>
                                </div>
                                <a id="body_msg" class="input-warning text-danger"></a>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingSelect" name="notif_type" aria-label="Floating label select example">
                                        @foreach($dictionary as $dct)
                                            @if($dct->dct_name == $type)
                                                <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                            @else
                                                <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="floatingSelect">Type</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingSelect" name="is_pending" aria-label="Floating label select example" onchange="getPendingDate(this.value, <?= $i; ?>)">
                                        @if($nt['is_pending'] == 1)
                                            <option value="1" selected>{{ __('messages.pending') }}</option>
                                            <option value="0">{{ __('messages.announced') }}</option>
                                        @else
                                            <option value="1">{{ __('messages.pending') }}</option>
                                            <option value="0" selected>{{ __('messages.announced') }}</option>
                                        @endif
                                    </select>
                                    <label for="floatingSelect">Status</label>
                                </div>
                                <span id="pending-until-holder-{{$i}}"></span>
                            </div>
                        </div>
                    
                        <label class="input-title">{{ __('messages.send_to') }}</label>
                        <p style="font-weight:400;"><i class="fa-solid fa-circle-info text-primary"></i> ...</p>
                        <span id="btn-submit-holder-{{$i}}"></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getPendingDate(status, id){
        if(status == 1){
            $("#pending-until-holder-"+id).append("<label class='input-title'>Pending Until</label><input type='date' name='pending_until' class='form-control'>");
        } else {
            $("#pending-until-holder-"+id).empty();
        }
    }

    //Validator
    function lengValidator(id){
        if($("#bodyInput_"+id).val().length >= 255){
            $("#body_msg_"+id).html("<i class='fa-solid fa-triangle-exclamation'></i> Reaching maximum character length");
        } else {
            $("#body_msg_"+id).text("");
        }

        if($("#bodyInput_"+id).val().length <= 12){
            $("#btn-submit-holder-"+id).html("");
        } else {
            $("#btn-submit-holder-"+id).html('<button type="submit" class="custom-submit-modal"><i class="fa-solid fa-floppy-disk"></i> Update</button>');
        }
    }
</script>

