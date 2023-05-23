<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
</style>

<?php
    use Carbon\Carbon;
    use App\Helpers\Generator;
?>

<div class="table-responsive">
    <table class="table table-paginate" id="dctTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Slug</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Properties</th>
            </tr>
        </thead>
        <tbody class="tabular-body">
            @php($i = 0)
            @foreach($dictionary as $dc)
                <tr class="tabular-item normal">
                    <td style="width: 140px;">
                        <select class="form-select" title="Change Type" onchange="validateChange(this.value, '{{$dc->id}}')" id="select-{{$dc->id}}">
                            @foreach($dictionaryType as $dct)
                                @if($dc->dct_type == $dct->app_code)
                                    <option selected value="{{$dct->app_code}}" id="{{$dct->app_code}}{{$dc->id}}">{{$dct->type_name}}</option>
                                @else 
                                    <option value="{{$dct->app_code}}" id="{{$dct->app_code}}{{$dc->id}}">{{$dct->type_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="modal fade" id="edit-type-{{$dc->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">   
                                    <div class="modal-body text-center pt-4">
                                        <button type="button" class="custom-close-modal" onclick="resetChange('select-{{$dc->id}}', '{{$dct->app_code}}')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                        <p style="font-weight:500;">Are you sure want to change type to '<span id="dct_name{{$dc->id}}"></span>'</p>
                                        <form action="/system/dictionary/update/type/{{$dc->id}}" method="POST">
                                            @csrf
                                            <input hidden id="dct_type_{{$dc->id}}" name="dct_type" value="">
                                            <button class='btn btn-submit-form' type='submit'><i class='fa-solid fa-paper-plane'></i> Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="width: 100px;">{{$dc->slug_name}}</td>
                    <td style="width: 100px;">{{$dc->dct_name}}</td>
                    <td>
                        <div style="word-break: break-all; width: 200px;">
                            @if($dc->dct_desc)
                                {{$dc->dct_desc}}
                            @else 
                                -
                            @endif
                        </div>
                    </td>
                    <td style="width: 220px;">
                        <h6>Created By</h6>
                        <div class="">
                            <div class="d-inline-block">
                                <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($dc->admin_username_created, null, $dc->admin_image_created, null)}}" 
                                    alt="{{Generator::getProfileImageContent($dc->admin_username_created, null, $dc->admin_image_created, null)}}">
                            </div>
                            <div class="d-inline-block position-relative w-75">
                                <h5 class="user-username-mini" title="View Profile">{{$dc->admin_username_created}}</h5>
                                <h6 class="properties-date date_holder_1">{{Carbon::parse($dc->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                            </div>
                        </div>    
                        @if($dc->updated_at)
                            <h6>Updated By</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($dc->admin_username_updated, null, $dc->admin_image_updated, null)}}" 
                                        alt="{{Generator::getProfileImageContent($dc->admin_username_updated, null, $dc->admin_image_updated, null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini" title="View Profile">{{$dc->admin_username_updated}}</h5>
                                    <h6 class="properties-date date_holder_2">{{Carbon::parse($dc->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($dc->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($dc->admin_username_deleted, null, $dc->admin_image_deleted, null)}}" 
                                        alt="{{Generator::getProfileImageContent($dc->admin_username_deleted, null, $dc->admin_image_deleted, null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini" title="View Profile">{{$dc->admin_username_deleted}}</h5>
                                    <h6 class="properties-date date_holder_3">{{Carbon::parse($dc->deleted_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                </tr>
                
                @php($i++)
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const date_holder_1 = document.querySelectorAll('.date_holder_1');
    const date_holder_2 = document.querySelectorAll('.date_holder_2');
    const date_holder_3 = document.querySelectorAll('.date_holder_3');

    date_holder_1.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    date_holder_2.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    date_holder_3.forEach(e => {
        const date = new Date(e.textContent);
        e.textContent = getDateToContext(e.textContent, "datetime");
    });

    function validateChange(slct, id){
        document.getElementById("dct_type_"+id).value = slct;
        document.getElementById("dct_name"+id).innerHTML = document.getElementById(slct+id).textContent;

        var myModal = document.getElementById('edit-type-'+id);
        var modal = new bootstrap.Modal(myModal, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }

    function resetChange(id, app_code){
        document.getElementById(id).value= app_code;
    }
</script>