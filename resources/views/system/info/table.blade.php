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
    <table class="table tabular table-paginate" id="infoTable" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Page / Location</th>
                <th scope="col">Body</th>
                <th scope="col">Is Active</th>
                <th scope="col">Properties</th>
            </tr>
        </thead>
        <tbody class="tabular-body">
            @php($i = 0)
            @foreach($info as $in)
                @php($bg = "")
                @if($in->is_active == 0)
                    @php($bg = "waiting")
                @endif

                <tr class="tabular-item normal{{$bg}}">
                    <td style="width: 140px;">
                        <select class="form-select" title="Change Type" onchange="validateChange(this.value, '{{$in->id}}')" id="select-{{$in->id}}">
                            @foreach($dictionary as $dct)
                                @if($in->info_type == strtolower($dct->dct_name))
                                    <option selected value="{{strtolower($dct->dct_name)}}" id="{{strtolower($dct->dct_name)}}{{$in->id}}">{{$dct->dct_name}}</option>
                                @else 
                                    <option value="{{strtolower($dct->dct_name)}}" id="{{strtolower($dct->dct_name)}}{{$in->id}}">{{$dct->dct_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="modal fade" id="edit-type-{{$in->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">   
                                    <div class="modal-body text-center pt-4">
                                        <button type="button" class="custom-close-modal" onclick="resetChange('select-{{$in->id}}', '{{strtolower($dct->dct_name)}}')" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                                        <p style="font-weight:500;">Are you sure want to change type to '<span id="dct_name{{$in->id}}"></span>'</p>
                                        <form action="/system/info/update/type/{{$in->id}}" method="POST">
                                            @csrf
                                            <input hidden id="dct_name_{{$in->id}}" name="info_type" value="">
                                            <button class='btn btn-submit-form' type='submit'><i class='fa-solid fa-paper-plane'></i> Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="width: 180px;">
                        <div id="info_page_location_holder_{{$in->id}}">
                            <p class="mb-0">Page : <a class="text-primary" href="{{url($in->info_page)}}" style="cursor:pointer;">{{$in->info_page}}</a></p>
                            <p>Location : {{$in->info_location}}</p>
                        </div>
                    </td>
                    <td>
                        <div style="word-break: break-all; width: 380px;" id="info_body_holder_{{$in->id}}">
                            <?= $in->info_body; ?>
                        </div>
                    </td>
                    <td style="width: 220px;">
                        <h6>Created By</h6>
                        <div class="">
                            <div class="d-inline-block">
                                <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($in->admin_username_created, null, $in->admin_image_created, null)}}" 
                                    alt="{{Generator::getProfileImageContent($in->admin_username_created, null, $in->admin_image_created, null)}}">
                            </div>
                            <div class="d-inline-block position-relative w-75">
                                <h5 class="user-username-mini">{{$in->admin_username_created}}</h5>
                                <h6 class="properties-date date_holder_1">{{Carbon::parse($in->created_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                            </div>
                        </div>    
                        @if($in->updated_at)
                            <h6>Updated By</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($in->admin_username_updated, null, $in->admin_image_updated, null)}}" 
                                        alt="{{Generator::getProfileImageContent($in->admin_username_updated, null, $in->admin_image_updated, null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini">{{$in->admin_username_updated}}</h5>
                                    <h6 class="properties-date date_holder_2">{{Carbon::parse($in->updated_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($in->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="">
                                <div class="d-inline-block">
                                    <img class="img img-fluid user-image" src="{{Generator::getProfileImageContent($in->admin_username_deleted, null, $in->admin_image_deleted, null)}}" 
                                        alt="{{Generator::getProfileImageContent($in->admin_username_deleted, null, $in->admin_image_deleted, null)}}">
                                </div>
                                <div class="d-inline-block position-relative w-75">
                                    <h5 class="user-username-mini">{{$in->admin_username_deleted}}</h5>
                                    <h6 class="properties-date date_holder_3">{{Carbon::parse($in->deleted_at)->format('Y-m-d\TH:i:s.\0\0\0\0\0\0\Z')}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning mb-2" onclick='toogleInfoDescEdit("{{$in->info_body}}","{{$in->id}}","{{$in->info_page}}","{{$in->info_location}}")'><i class="fa-solid fa-edit"></i></button>
                        @if($in->info_location != "delete_info")
                            <button class="btn btn-danger" data-bs-target="#deleteModal-{{$i}}" data-bs-toggle="modal"><i class="fa-solid fa-trash"></i></button>
                        @endif
                    </td>
                </tr>

                @if($in->info_location != "delete_info")
                    @include('system.info.delete')
                @endif
                
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
        document.getElementById("dct_name_"+id).value = slct;
        document.getElementById("dct_name"+id).innerHTML = document.getElementById(slct+id).textContent;

        var myModal = document.getElementById('edit-type-'+id);
        var modal = new bootstrap.Modal(myModal, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    }

    function resetChange(id, name){
        document.getElementById(id).value= name;
    }

    var id_body = " ";
    var toogle = 0;
    var menus = [<?php foreach($menu as $mn){ echo "'".substr($mn->menu_url,1)."',"; } ?>'register','landing','event/edit','event/detail']; 

    function toogleInfoDescEdit(info_body, id, page, loc){
        var holder_body = document.getElementById("info_body_holder_"+id);
        var pagloc_body = document.getElementById("info_page_location_holder_"+id);

        if(toogle % 2 == 0){
            holder_body.innerHTML = " <div id='rich_box_" + id + "' style='height: 200px !important;'></div> " +
            "<form class='d-inline' id='form-edit-desc_" + id + "' method='POST' action=''> " +
                '@csrf ' +
                "<input name='info_body' id='info_body_" + id + "' hidden> " +
                "<button class='btn btn-success mt-3' onclick='getRichTextHelpDesc("+ '"' + id + '"' +")'><i class='fa-solid fa-floppy-disk'></i> Save Chages</button> " +
            "</form> ";

            pagloc_body.innerHTML = " " +
                "<form class='d-inline' method='POST' action='/system/info/update/pagloc/" + id + "'> " +
                    '@csrf ' +
                    '<div class="form-floating mb-2"> ' +
                        '<select class="form-select" id="info_type_' + id + '" style="font-size:14px;" title="Info Page" name="info_page" aria-label="Floating label select example" required></select> ' +
                        '<label for="floatingSelect">Page</label> ' +
                    '</div> ' +
                    '<div class="form-floating"> ' +
                        '<input type="text" class="form-control nameInput" id="info_location_' + id + '" value="' + loc + '" style="font-size:14px;" name="info_location" maxlength="75" oninput="validateForm(validationAdd)" required> ' +
                        '<label for="titleInput_event">Info Location</label> ' +
                        '<a id="info_location_msg_' + id + '" class="text-danger my-2" style="font-size:13px;"></a> ' +
                    '</div> ' +
                    "<button class='btn btn-success mt-3'><i class='fa-solid fa-floppy-disk'></i> Save Chages</button> " +
                "</form> ";
                
                menus.forEach(e => {
                    if(e == page){
                        $("#info_type_" + id).append('<option value="'+e+'" selected>'+e+'</option>');
                    } else {
                        $("#info_type_" + id).append('<option value="'+e+'">'+e+'</option>');
                    }
                });
            
            var quill<?= str_replace("-", "", $in->id) ?> = new Quill('#rich_box_' + id, {
                theme: 'snow'
            });

            var info_input = document.getElementById("info_body_" + id);
            var parent = document.getElementById("rich_box_" + id);
            var child = parent.getElementsByClassName("ql-editor")[0];
            if(info_body != null || info_body != "null"){
                child.innerHTML = info_body;
            } else {
                child.innerHTML = " ";
            }
        } else {
            pagloc_body.innerHTML = '<p class="mb-0">Page : <a class="text-primary" href="{{url($in->info_page)}}" style="cursor:pointer;">' + page + '</a></p> ' +
                '<p>Location : ' + loc + '</p>';
            holder_body.innerHTML = info_body;
        }
        toogle++;
    }

    function getRichTextHelpDesc(id){
        var rawText = document.getElementById("rich_box_"+ id).innerHTML;
        var input_body = document.getElementById("info_body_"+id);
        var form = document.getElementById("form-edit-desc_" + id);

        //Remove quills element from raw text
        var cleanText = rawText.replace('<div class="ql-editor" data-gramm="false" contenteditable="true">','').replace('<div class="ql-editor ql-blank" data-gramm="false" contenteditable="true">');
        //Check this clean text 2!!!
        cleanText = cleanText.replace('</div><div class="ql-clipboard" contenteditable="true" tabindex="-1"></div><div class="ql-tooltip ql-hidden"><a class="ql-preview" target="_blank" href="about:blank"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL"><a class="ql-action"></a><a class="ql-remove"></a></div>','');
        
        //Pass html quilss as input value
        var characterToDeleteAfter = "</div>";
        var modifiedString = deleteAfterCharacter(cleanText, characterToDeleteAfter);
        input_body.value = modifiedString;

        form.addEventListener('submit', function(event) {
            event.preventDefault(); 
            form.action = '/system/info/update/body/' + id;
            form.submit();
        });
    }
</script>