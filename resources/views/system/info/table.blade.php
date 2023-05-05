<style>
    .form-custom{
        display:inline;
        position:relative;
    }
    .form-custom i{
        color:#9c9c9c;
    }
</style>

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

                <tr class="tabular-item {{$bg}}">
                    <td style="width: 140px;">
                        <form action="/system/info/update/type/{{$in->id}}" method="POST">
                            @csrf
                            <select class="form-select" name="info_type" title="Change Type" onchange="this.form.submit()">
                                @foreach($dictionary as $dct)
                                    @if($in->info_type == strtolower($dct->dct_name))
                                        <option selected value="{{strtolower($dct->dct_name)}}">{{$dct->dct_name}}</option>
                                    @else 
                                        <option value="{{strtolower($dct->dct_name)}}">{{$dct->dct_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <p class="mb-0">Page : <a class="text-primary" href="{{url($in->info_page)}}" style="cursor:pointer;">{{$in->info_page}}</a></p>
                        <p>Location : {{$in->info_location}}</p>
                    </td>
                    <td>
                        <div style="word-break: break-all; width: 400px;" id="info_body_holder_{{$in->id}}">
                            <?= $in->info_body; ?>
                        </div>
                    </td>
                    <td style="width: 180px;">
                        <h6>Created By</h6>
                        <div class="row p-0 m-0">
                            <div class="col-3 p-0">
                                <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                            </div>
                            <div class="col-9 p-0 ps-2 pt-1">
                                <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($in->created_at))}}</h6>
                            </div>
                        </div>    
                        @if($in->updated_at)
                            <h6>Updated By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($in->updated_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                        @if($in->deleted_at)
                            <h6>Deleted By</h6>
                            <div class="row p-0 m-0">
                                <div class="col-3 p-0">
                                    <img class="img img-fluid user-image" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/719912cc-2649-41a1-9e66-ec5e6315cabb/d9a5mif-cc463e46-8bfa-4ed1-8ab0-b0cdf7dab5a7.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzcxOTkxMmNjLTI2NDktNDFhMS05ZTY2LWVjNWU2MzE1Y2FiYlwvZDlhNW1pZi1jYzQ2M2U0Ni04YmZhLTRlZDEtOGFiMC1iMGNkZjdkYWI1YTcuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.TxrhpoYcqn2CqCClDnY2C2Pet3mQM6BddV0HukU4u28" alt="username-profile-pic.png">
                                </div>
                                <div class="col-9 p-0 ps-2 pt-1">
                                    <h5 class="user-username-mini" title="View Profile">Budi</h5>
                                    <h6 class="properties-date">{{date('Y-m-d H:i', strtotime($in->deleted_at))}}</h6>
                                </div>
                            </div>   
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning mb-2" onclick='toogleInfoDescEdit("{{$in->info_body}}","{{$in->id}}")'><i class="fa-solid fa-edit"></i></button>
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
    var id_body = " ";

    function toogleInfoDescEdit(info_body, id){
        var holder_body = document.getElementById("info_body_holder_"+id);

        holder_body.innerHTML = " ";
        holder_body.innerHTML = " <div id='rich_box_" + id + "' style='height: 200px !important;'></div> " +
        "<form class='d-inline' id='form-edit-desc_" + id + "' method='POST' action=''> " +
            '@csrf ' +
            "<input name='info_body' id='info_body_" + id + "' hidden> " +
            "<button class='btn btn-success mt-3' onclick='getRichTextHelpDesc("+ '"' + id + '"' +")'><i class='fa-solid fa-floppy-disk'></i> Save Chages</button> " +
        "</form> ";
        
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