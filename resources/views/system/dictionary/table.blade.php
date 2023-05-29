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
                <th scope="col">Info</th>
                <th scope="col">Properties</th>
                <th scope="col">Action</th>
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
                    <td style="width: 120px;">{{$dc->slug_name}}</td>
                    <td style="width: 280px;">
                        <span id="dct-holder-show-{{$dc->id}}">
                            <h6 class="mb-0">Name</h6>
                            {{$dc->dct_name}}

                            <h6 class="mt-2 mb-0">Description</h6>
                            <div style="word-break: break-all; width: 200px;">
                                @if($dc->dct_desc)
                                    {{$dc->dct_desc}}
                                @else 
                                    -
                                @endif
                            </div>
                        </span>
                        <span id="dct-holder-edit-{{$dc->id}}" class="d-none">
                            <form class="d-inline" action="/system/dictionary/update/info/{{$dc->id}}" method="POST">
                                @csrf
                                <script>
                                    let validation<?= str_replace('-', '', $dc->id); ?> = [
                                        { id: "dct_name_{{str_replace('-', '', $dc->id)}}", req: true, len: 30 },
                                        { id: "dct_desc_{{str_replace('-', '', $dc->id)}}", req: false, len: 255 },
                                    ];
                                </script>
                                <div class="form-floating mb-2">
                                    <input type="text" class="form-control nameInput" id="dct_name_{{str_replace('-', '', $dc->id)}}" name="dct_name" value="{{$dc->dct_name}}" maxlength="30" oninput="validateFull(validation<?= str_replace('-', '', $dc->id); ?>, '<?= str_replace('-', '', $dc->id); ?>')" required>
                                    <label for="dct_name">Dictionary Name</label>
                                    <a id="dct_name_{{str_replace('-', '', $dc->id)}}_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                </div>
                                <div class="form-floating">
                                    <textarea class="form-control" style="height: 120px" id="dct_desc_{{str_replace('-', '', $dc->id)}}" name="dct_desc" value="{{$dc->dct_desc}}" oninput="validateFull(validation<?= str_replace('-', '', $dc->id); ?>, '<?= str_replace('-', '', $dc->id); ?>')" maxlength="255">{{$dc->dct_desc}}</textarea>
                                    <label for="dct_desc">Dictionary Description</label>
                                    <a id="dct_desc_{{str_replace('-', '', $dc->id)}}_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                </div>
                                <span id="submit_holder_{{str_replace('-', '', $dc->id)}}"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
                            </form>
                        </span>
                    </td>
                    <td style="width: 240px;">
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
                    <td>
                        <button class="btn btn-warning mb-2" onclick='toogleInfoDescEdit("{{$dc->id}}")'><i class="fa-solid fa-edit"></i></button>
                        <button class="btn btn-danger" data-bs-target="#deleteModal-{{$dc->id}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                        @include('system.dictionary.delete')                            
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

    var id_body = " ";
    var toogle = 0;
    let validation = [];

    function toogleInfoDescEdit(id){
        validation = [
            { id: "dct_name_"+id, req: true, len: 35 },
            { id: "dct_desc_"+id, req: false, len: 255 },
        ];
        
        var holder_body = document.getElementById("info_body_holder_"+id);

        if(toogle % 2 == 0){
            document.getElementById("dct-holder-edit-" + id).setAttribute('class', 'd-normal');
            document.getElementById("dct-holder-show-" + id).setAttribute('class', 'd-none');
            document.getElementById("dct_name_" + id).setAttribute('oninput', 'validateForm(validation)');
        } else {
            document.getElementById("dct-holder-show-" + id).setAttribute('class', 'd-normal');
            document.getElementById("dct-holder-edit-" + id).setAttribute('class', 'd-none');
            document.getElementById("dct_name_" + id).setAttribute('oninput', '');
        }
        toogle++;
    }
</script>