<style>
    input[type="file"].d-none {
        display: none;
    }
</style>

<div class="position-relative" style="min-height:25vh;">
    <h5 class="section-title">{{ __('messages.new_tag') }} ({{ __('messages.advan') }})</span></h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button" id="section-more-import-tag" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-import-tag">
        <a class="dropdown-item" data-bs-target="#helpImport" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> {{ __('messages.help') }}</a>
        <a class="dropdown-item" onclick="getTemplate()"><i class="fa-solid fa-print"></i> {{ __('messages.get_template') }}</a>
    </div>
    <div class="modal fade" id="helpImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">  
                <div class="modal-body p-4 pb-1">
                    <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                    <h5 class="text-primary">{{ __('messages.import') }}</h5><hr>
                    <div class="info-box tips">
                        <label><i class="fa-solid fa-circle-info"></i> Tips</label><br>
                        <p>{{ __('messages.import_tag_tips') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center" id="init-import">
        <img class="img w-50 d-block mx-auto" src="{{asset('assets/import.png')}}">
        <label>{{ __('messages.supported_desc') }} <b>CSV</b></label>
        <input type="file" id="fileInput" accept=".csv" class="d-none" oninput="importTagFile()">
        <label for="fileInput" class="btn btn-success py-1 ms-1 rounded-pill"><i class="fa-solid fa-cloud"></i> {{ __('messages.upload_file') }}</label>
    </div>
    <p class="text-danger my-2" id="err-import-tag-msg"></p>
    <span id="success-check"></span>

    <div class="modal fade" id="addTagAdvanced" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">  
                <form action="/event/tag/add" method="POST">
                    @csrf 
                    <div class="modal-body pt-4 position-relative">
                        <input hidden id="slug_name" name="slug_name">
                        <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                        <h5>{{ __('messages.new_tag') }}</h5>
                        <div class="mt-2 row" id="imported-tag-holder"></div>
                        <span id="btn-submit-holder-event"><button class="custom-submit-modal"><i class="fa-solid fa-paper-plane"></i> {{ __('messages.submit_all') }}</button></span><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function getTemplate() {
        const fileURL = 'http://127.0.0.1:8000/document/import_tag_template.csv';
        const fileName = 'import_tag_template.csv';
        const link = document.createElement('a');
        link.href = fileURL;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function importTagFile(){
        clearImportedTag();
        var files = document.getElementById('fileInput').files[0];
        var max = 15; // CSV maximum size
        var err_import_msg = document.getElementById("err-import-tag-msg");

        if(files.size <= max * 1024 * 1024){
            var reader = new FileReader();

            reader.readAsText(files);
            reader.onload = function(event) {
                var csvdata = event.target.result;
                var rowData = csvdata.split('\n');
                var success = 0;
                var failed = 0;

                if(rowData.length > 0 && rowData.length < 100){
                    for (var row = 1; row < rowData.length; row++) {
                        rowColData = rowData[row].split(',');

                        document.getElementById("init-import").innerHTML = " ";
                        setTimeout(() => {
                            document.getElementById("success-check").innerHTML = '<lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 300px; height: 300px;" autoplay></lottie-player>';
                        }, 500);

                        for (var col = 0; col < rowColData.length; col++) {
                            var tag_col = rowColData[col].split(';');
                            var is_success = true;
                            var msg = "";
                            var tag_name = "";
                            var tag_desc = "";

                            if(tag_col[0] != null){
                                tag_name = tag_col[0].trim();
                            } else {
                                tag_name = tag_col[0];
                            }

                            if (tag_name !== "" && tag_name.length < validation[0]['len']) {
                                $("#imported-tag-holder").append(`
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control nameInput" id="tag_name" name="tag_name[]" value="${tag_name}" maxlength="30" required>
                                            <label for="tag_name">Tag Name</label>
                                            <a id="tag_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                        </div>
                                    </div>
                                `);
                            } else {
                                let msg;
                                if (tag_name === "") {
                                    msg = "Tag name can't be empty";
                                } else {
                                    msg = `Tag name must be below ${validation[0]['len']} characters long`;
                                }
                                is_success = false;
                            }
                        
                            if(tag_col[1] != null){
                                tag_desc = tag_col[1].trim();
                            } else {
                                ttag_desc = tag_col[1];
                            }

                            if (tag_desc.length < validation[1]['len'] && is_success) {
                                $("#imported-tag-holder").append(`
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-2">
                                            <textarea class="form-control" id="tag_desc" name="tag_desc[]" value="${tag_desc}" maxlength="255">${tag_desc}</textarea>
                                            <label for="tag_desc">Tag Description</label>
                                            <a id="tag_desc_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                        </div>
                                    </div>
                                `);
                            } else {
                                if (is_success) {
                                    $("#imported-tag-holder").children().last().remove();
                                }
                                msg = `Tag description must be below ${validation[1]['len']} characters long`;
                                is_success = false;
                            }

                            if (is_success) {
                                success++;
                                $("#imported-tag-holder").append(`
                                    <div class="col-lg-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="tag_category" name="tag_category[]" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                                                @php($i = 0)
                                                @foreach($dct_tag as $dtag)
                                                    @if($i == 0)
                                                        <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                                                    @else
                                                        <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                                                    @endif
                                                    @php($i++)
                                                @endforeach
                                            </select>
                                            <label for="tag_category">Category</label>
                                            <a id="tag_category_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                        </div>
                                    </div><hr>
                                `);
                            } else {
                                $("#imported-tag-holder").append(`
                                    <div class="col-12 py-3">
                                        <h6 class="text-dark">Failed to import at line ${row}</h6>
                                        <a class="text-danger err-msg"><i class="fa-solid fa-triangle-exclamation"></i> ${msg}</a>
                                    </div><hr>
                                `);
                                failed++;
                            }

                            setTimeout(() => {
                                document.getElementById("success-check").innerHTML = "";
                                err_import_msg.innerHTML = "";
                                document.getElementById("init-import").innerHTML = `
                                    <br><br>
                                    <h5 class="text-success">${success} Tag imported <span style="color:var(--shadowColor);">/</span> <span class="text-danger" style="font-size:var(--textLG);">${failed} Import failed</span></h5>
                                    <button class="btn btn-success rounded-pill py-1 mt-1" data-bs-target="#addTagAdvanced" data-bs-toggle="modal"><i class="fa-solid fa-eye"></i> Preview</button>
                                    <input type="file" id="fileInput" accept=".csv" class="d-none" oninput="importTagFile()">
                                    <label for="fileInput" class="btn btn-danger py-1 ms-1 mt-1 rounded-pill"><i class="fa-solid fa-cloud"></i> Change File</label>
                                    <button class="btn btn-danger rounded-pill py-1 mt-1 px-3" onclick="clearImportedTag()"><i class="fa-solid fa-trash"></i></button>
                                `;
                            }, 3000);

                        }

                        setTimeout(() => {
                            document.getElementById("success-check").innerHTML = "";
                            err_import_msg.innerHTML = "";
                            document.getElementById("init-import").innerHTML = ' ' +
                                '<br><br> ' +
                                '<h5 class="text-success">'+success+' Tag imported <span style="color:var(--shadowColor);">/</span> <span class="text-danger" style="font-size:var(--textLG);">'+failed+' Import failed</span></h5> ' +
                                '<button class="btn btn-success rounded-pill py-1 mt-1" data-bs-target="#addTagAdvanced" data-bs-toggle="modal"><i class="fa-solid fa-eye"></i> Preview</button>' +
                                '<input type="file" id="fileInput" accept=".csv" class="d-none" oninput="importTagFile()"> ' +
                                '<label for="fileInput" class="btn btn-danger py-1 ms-1 mt-1 rounded-pill"><i class="fa-solid fa-cloud"></i> Change File</label> ' +
                                '<button class="btn btn-danger rounded-pill py-1 mt-1 px-3" onclick="clearImportedTag()"><i class="fa-solid fa-trash"></i></button>';
                        },  3000);
                    }
                } else {
                    if(rowData == 0){
                        err_import_msg.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> File failed to import, no item found on this CSV';
                    } else {
                        err_import_msg.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> File failed to import, too many item. Maximum item to import is 100 item';
                    }
                }
            }
        }
    }

    function clearImportedTag(){
        $("#imported-tag-holder").empty();
    }
</script>