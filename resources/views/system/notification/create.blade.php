<style>
    .btn-quick-action-notif{
        border-radius: var(--roundedMini);
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        height:25vh;
        border:none;
        width:100%;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        /* background-size: cover; */
        transition: 0.5s;
        text-align:left;
        padding:10px;
        background-size: contain;
    }
    .btn-quick-action-notif.small{
        height:70px;
    }
    .btn-quick-action-notif:hover{
        /* background: var(--primaryColor) !important; */
        background-image:linear-gradient(to bottom right,var(--primaryColor) 20%, 70%, #5b5b5b) !important;
    }
    .quick-action-text-notif{
        font-size:24px;
        color:var(--whiteColor);
        transition: 0.5s;
        margin-top:13vh;
    }
    .small .quick-action-text-notif{
        margin-top:10px;
    }
    .quick-action-info-notif{
        font-size:14px;
        color:var(--whiteColor);
        transition: 0.5s;
        display:none;
    }
    .btn-quick-action-notif:hover .quick-action-text-notif{
        margin-top:-4vh;
    }
    .btn-quick-action-notif .small:hover{
        margin-top:10px;
    }
    .btn-quick-action-notif:hover .quick-action-info-notif{
        display:block;
    }

    #user-list-holder, #group-list-holder{
        padding: 5px 16px 0 5px;
        display: flex;
        flex-direction: column;
        max-height: 65vh;
        overflow-y: scroll;
    }
</style>

<script>
    let validation = [
        { id: "notif_body", req: true, len: 255 },
        { id: "notif_title", req: true, len: 35 },
        { id: "selected_item", req: true, len: null }
    ];
</script>

<div class="modal fade" id="selectTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h5>{{ __('messages.slct_notif_type') }}</h5>
                
                <div class="row px-2">
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('All User')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/global.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="{{ __('messages.all_user') }}" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">{{ __('messages.all_user') }}</h5>
                            <p class="quick-action-info-notif">{{ __('messages.all_user_desc') }}</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Role')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/tag.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="{{ __('messages.by_role') }}" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">{{ __('messages.by_role') }}</h5>
                            <p class="quick-action-info-notif">{{ __('messages.by_role_desc') }}</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Grouping')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/group.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="{{ __('messages.by_group') }}" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">{{ __('messages.by_group') }}</h5>
                            <p class="quick-action-info-notif">{{ __('messages.by_group_desc') }}</p>
                        </button>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Person')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/person.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="{{ __('messages.by_person') }}" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">{{ __('messages.by_person') }}</h5>
                            <p class="quick-action-info-notif">{{ __('messages.by_person_desc') }}</p>
                        </button>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 p-2">
                        <button class="btn-quick-action-notif" onclick="setType('Pending')" data-bs-dismiss="modal" style='background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.45)), url("<?= asset('/assets/pending.png'); ?>"); background-color:#FB5E5B;'
                            data-bs-target="#addModal" title="{{ __('messages.pending') }}" data-bs-toggle="modal">
                            <h5 class="quick-action-text-notif">{{ __('messages.pending') }}</h5>
                            <p class="quick-action-info-notif">{{ __('messages.pending_desc') }}</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content">  
            <div class="modal-body pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <form action="/system/notification/add" method="POST" id="form-add-notif">
                    @csrf
                    <h5>{{ __('messages.add') }} <span id="type-title"></span> {{ __('messages.announcement') }}</h5>
                    
                    <span id="section-holder"></span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('form-add-notif').addEventListener('keydown', function(event) {
        if (event.keyCode === 13) { // 13 is the key code for Enter key
            event.preventDefault();
            if(event.target.id == "group_search"){
                infinteLoadGroup(1);
            } else if(event.target.id == "title_search"){
                infinteLoadUser(1);
            } 
        }
    });
    
    var selectedUser = []; 
    var lastPageAllUser = 1;
    var selectedGroup = []; 
    var lastPageAllGroup = 1;
    var selectedRole = [];
    var tag_cat = '<?= $dct_tag[0]["slug_name"]; ?>';
    var page_tag = 1;
    var page_curr_user = 1;
    var page_curr_group = 1;

    window.addEventListener('beforeunload', function(event) {
        if(!isFormSubmitted){
            var is_editing = false;
            const form = document.getElementById('form-add-notif');
            const inputs = form.querySelectorAll('input');

            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                
                if (input.value.trim() !== '' && input.name != "_token" && input.name != "send_to" && input.name != "slug_name[]" && input.name != "user_username[]") {
                    is_editing = true;
                    break;
                }
            }

            if(is_editing || selectedUser.length > 0 || selectedGroup.length > 0 || selectedRole.length > 0){
                event.preventDefault();
                event.returnValue = '';
            }
        }
    });

    function setTagFilter(tag){
        tag_cat = tag;
        page_tag = 1;
        infinteLoadRole(page_tag);
        $("#role-list-holder").empty();
    }

    function setType(type){
        document.getElementById("type-title").innerHTML = type;
        setFormSection(type);

        if(type == "Grouping"){
            infinteLoadGroup(1);
        } else if(type == "Person"){
            infinteLoadUser(1);
        } else if(type == "Role"){
            infinteLoadRole(1);
        }
    }

    function resetGroupSearch(){
        document.getElementById("group_search").value = null;
        infinteLoadGroup(1);
    }

    function resetTitleSearch(){
        document.getElementById("title_search").value = null;
        infinteLoadUser(1);
    }

    function setFormSection(type){
        var sec = document.getElementById("section-holder");
        if(type == "All User"){
            var elmt = `
                <div class="px-2">
                    <input name="id" id="notif_id" hidden>
                    <input name="send_to" value="all" hidden>
                    <div class="form-floating mb-2">
                        <input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35">
                        <label for="notif_title">{{ __('messages.title') }}</label>
                        <a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea>
                        <label for="notif_body">{{ __('messages.body') }}</label>
                        <a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                            @php($i = 0)
                            @foreach($dictionary as $dct)
                                @if($dct->type_name == "Notification")
                                    @if($i == 0)
                                        <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                    @else
                                        <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                    @endif
                                    @php($i++)
                                @endif
                            @endforeach
                        </select>
                        <label for="notif_type">{{ __('messages.type') }}</label>
                        <a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div id="datetime-picker-box"></div>
                   
                    <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                </div>
            `;
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog');
        } else if(type == "Grouping"){
            validation[2]['len'] = "slct-group-list-holder";
            var elmt = `
                <div class="row px-2">
                    <input name="send_to" value="grouping" hidden>
                    <input name="id" id="notif_id" hidden>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-floating mb-2">
                            <input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35">
                            <label for="notif_title">{{ __('messages.title') }}</label>
                            <a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea>
                            <label for="notif_body">{{ __('messages.body') }}</label>
                            <a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                                        @php($i = 0)
                                        @foreach($dictionary as $dct)
                                            @if($dct->type_name == "Notification")
                                                @if($i == 0)
                                                    <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                                @else
                                                    <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                                @endif
                                                @php($i++)
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="notif_type">{{ __('messages.type') }}</label>
                                    <a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- .... -->
                            </div>
                            <div id="datetime-picker-box"></div>
                        </div>
                        <hr>
                        <span class="position-relative">
                            <h6>{{ __('messages.slct_group') }}</h6>
                            <a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllGroup()"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</a>
                        </span>
                        <div id="slct-group-list-holder"></div>
                        <div id="slct-group-list-holder_msg" class="input-warning text-danger"></div>
                        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <input name="list_context" id="list_context_group" value="" hidden>
                        <h6 class="mb-2">{{ __('messages.all_group') }}/h6>
                        <div style="max-width:300px; right:10px; top:-15px;" class="row mb-2 position-absolute">
                            <div class="col-2">
                                <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetGroupSearch()"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                            <div class="col-10 position-relative">
                                <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:var(--darkColor);"></i>
                                <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="group_search" placeholder="Search by group name"  onchange="infinteLoadGroup(1)" maxlength="75">
                            </div>
                        </div>
                        <span id="group-list-holder"></span>
                        <h6 class="mt-1">{{ __('messages.page') }}</h6>
                        <div id="all-group-page" class="mt-2"></div>
                    </div>
                </div>
            `;
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        } else if(type == "Role"){
            validation[2]['len'] = "slct-role-list-holder";
            var elmt = `
                <div class="row px-2">
                    <input name="id" id="notif_id" hidden>
                    <input name="send_to" value="role" hidden>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-floating mb-2">
                            <input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35">
                            <label for="notif_title">{{ __('messages.title') }}</label>
                            <a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea>
                            <label for="notif_body">{{ __('messages.body') }}</label>
                            <a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                                        @php($i = 0)
                                        @foreach($dictionary as $dct)
                                            @if($dct->type_name == "Notification")
                                                @if($i == 0)
                                                    <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                                @else
                                                    <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                                @endif
                                                @php($i++)
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="notif_type">{{ __('messages.type') }}</label>
                                    <a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- .... -->
                            </div>
                            <div id="datetime-picker-box"></div>
                        </div>
                        <hr>
                        <span class="position-relative">
                            <h6>{{ __('messages.slct_role') }}</h6>
                            <a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllRole()"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</a>
                        </span>
                        <div id="slct-role-list-holder"></div>
                        <div id="slct-role-list-holder_msg" class="input-warning text-danger"></div>
                        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <div class="position-absolute" style="right:10px; top:-15px;">
                            <select class="form-select" id="tag_category" title="Tag Category" onchange="setTagFilter(this.value)" name="tag_category" style="font-size:13px;" aria-label="Floating label select example" required>
                                @php($i = 0)
                                @foreach($dct_tag as $dtag)
                                    @if($i == 0)
                                        <option value="{{$dtag->slug_name}}" selected>{{$dtag->dct_name}}</option>
                                        <option value="all">{{ __('messages.all') }}</option>
                                    @else
                                        <option value="{{$dtag->slug_name}}">{{$dtag->dct_name}}</option>
                                    @endif
                                    @php($i++)
                                @endforeach
                            </select>
                        </div>
                        <input name="list_context" id="list_context_role" value="" hidden>
                        <h6>All Role</h6>
                        <span id="role-list-holder"></span>
                    </div>
                </div>
            `;
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        } else if(type == "Pending"){
            var elmt = `
                <div class="px-2">
                    <input name="send_to" value="pending" hidden>
                    <div class="form-floating mb-2">
                        <input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35">
                        <label for="notif_title">{{ __('messages.title') }}</label>
                        <a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea>
                        <label for="notif_body">{{ __('messages.body') }}</label>
                        <a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                            @php($i = 0)
                            @foreach($dictionary as $dct)
                                @if($dct->type_name == "Notification")
                                    @if($i == 0)
                                        <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                    @else
                                        <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                    @endif
                                    @php($i++)
                                @endif
                            @endforeach
                        </select>
                        <label for="notif_type">{{ __('messages.type') }}</label>
                        <a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a>
                    </div>
                    <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                </div>
            `;
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog');
        } else if(type == "Person"){
            validation[2]['len'] = "slct-user-list-holder";
            var elmt = `
                <div class="row px-2">
                    <input name="id" id="notif_id" hidden>
                    <input name="send_to" value="person" hidden>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-floating mb-2">
                            <input class="form-control" id="notif_title" name="notif_title" oninput="validateForm(validation)" maxlength="35">
                            <label for="notif_title">{{ __('messages.title') }}</label>
                            <a id="notif_title_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea class="form-control" style="height: 100px" id="notif_body" name="notif_body" oninput="validateForm(validation)" maxlength="255"></textarea>
                            <label for="notif_body">{{ __('messages.body') }}</label>
                            <a id="notif_body_msg" class="text-danger my-2" style="font-size:13px;"></a>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select class="form-select" id="notif_type" name="notif_type" aria-label="Floating label select example" onchange="validateForm(validation)" required>
                                        @php($i = 0)
                                        @foreach($dictionary as $dct)
                                            @if($dct->type_name == "Notification")
                                                @if($i == 0)
                                                    <option value="{{$dct->slug_name}}" selected>{{$dct->dct_name}}</option>
                                                @else
                                                    <option value="{{$dct->slug_name}}">{{$dct->dct_name}}</option>
                                                @endif
                                                @php($i++)
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="notif_type">{{ __('messages.type') }}</label>
                                    <a id="notif_type_msg" class="text-danger my-2" style="font-size:13px;"></a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                            </div>
                            <div id="datetime-picker-box"></div>
                        </div>
                        <hr>
                        <span class="position-relative">
                            <h6>{{ __('messages.slct_user') }}</h6>
                            <a class="btn btn-noline text-danger" style="float:right; margin-top:-35px;" onclick="clearAllUser()"><i class="fa-regular fa-trash-can"></i> {{ __('messages.filter_tag') }}</a>
                        </span>
                        <div id="slct-user-list-holder"></div>
                        <div id="slct-user-list-holder_msg" class="input-warning text-danger"></div>
                        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <input name="list_context" id="list_context"  value="" hidden>
                        <h6>{{ __('messages.all_user') }}</h6>
                        <div style="max-width:300px; right:10px; top:-15px;" class="row mb-2 position-absolute">
                            <div class="col-2">
                                <a class="btn btn-danger-icon-outlined" title="Reset" onclick="resetTitleSearch()"><i class="fa-solid fa-xmark"></i></a>
                            </div>
                            <div class="col-10 position-relative">
                                <i class="fa-solid fa-magnifying-glass position-absolute" style="top:10px; left: 25px; color:var(--darkColor);"></i>
                                <input type="text" class="form-control rounded-pill" style="padding-left: 35px;" id="title_search" placeholder="{{ __('messages.search_fname') }}" onchange="infinteLoadUser(1)" maxlength="75">
                            </div>
                        </div>
                        <div id="user-list-holder"></div>
                        <span id="empty_item_holder_user"></span>
                        <span id="load_more_holder_user" style="display: flex; justify-content:center;"></span>
                        <h6 class="mt-1">{{ __('messages.page') }}</h6>
                        <div id="all-user-page" class="mt-2"></div>
                    </div>
                </div>
            `;
            document.getElementById("modal-dialog").setAttribute('class', 'modal-dialog modal-lg');
        }
        sec.innerHTML = elmt;
    }

    function transfer(id, type, title, body){
        document.getElementById("notif_id").value = id;
        document.getElementById("notif_type").value = type;
        document.getElementById("notif_title").value = title;
        document.getElementById("notif_body").value = body;
        validateForm(validation);
    }

    function toogleTimePicker(){
        var time = document.getElementById("send_time").value;

        const elmt = `
            <div class="row">
                <div class="col-lg-6">
                    <label>Set Date</label>
                    <input type="date" name="sended_date" id="sended_date" onchange="" class="form-control">
                </div> 
                <div class="col-lg-6"> 
                    <label>Set Time</label> 
                    <input type="time" name="sended_time" id="sended_time" onchange="" class="form-control">
                    <a id="dateEnd_event_msg" class="input-warning text-danger"></a>
                </div>
            </div>
        `;

        document.getElementById("datetime-picker-box").innerHTML = elmt;
    }

    function getFind(check){
        let trim = check.trim();
        if(check == null || trim === ''){
            return "%20"
        } else {
            document.getElementById("group_search").value = trim;
            return trim
        }
    }

    function infinteLoadGroup(page) {       
        page_curr_group = page;
        var order = '<?= session()->get('ordering_group_list'); ?>';
        var find = document.getElementById("group_search").value;
        document.getElementById("group-list-holder").innerHTML = "";

        var per_page = 24;
        if(isMobile()){
            per_page = 12;
        } 

        $.ajax({
            url: "/api/v1/group/limit/"+per_page+ "/order/" + order + "/find/" + getFind(find) + "?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data.data;
            var total = response.data.total;
            lastPageAllGroup = response.data.last_page;

            if(page != lastPageAllGroup){
                $('#load_more_holder_new_req').html(`<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more_holder_new_req').html(`<h6 class="btn content-more-floating mb-3 p-2">{{ __('messages.no_more') }}</h6>`);
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html(`<h5 class='text-secondary'>{{ __('messages.all_viewed') }}</h5>`);
                return;
            } else {                
                const getTotalMember = total => total > 0 ? `<span class="text-primary" style="font-size:13px; font-weight:500;"><i class="fa-solid fa-user"></i> ${total}</span>` : `<span class="text-danger fw-bold" style="font-size:13px;"><i class="fa-solid fa-triangle-exclamation"></i> No member</span>`;

                for(var i = 0; i < data.length; i++){
                    var slug = data[i].slug_name;
                    var groupName = data[i].group_name;
                    var groupDesc = data[i].group_desc;
                    var totalMember = data[i].total;

                    var elmt = `
                        <a class="btn user-box py-3" style="height:110px;" onclick="">
                            <div class="position-relative ps-2">
                                <h6 class="text-secondary fw-normal">${groupName}</h6>
                                <h6 class="text-secondary mb-0 available-desc">${groupDesc}</h6>
                                ${getTotalMember(totalMember)}
                                <div class="form-check position-absolute" style="right: 20px; top: 10px;">
                                    <input class="form-check-input" name="user_username[]" value="${slug}" type="checkbox" style="width: 25px; height:25px;" id="check_group_${slug}" onclick="addSelectedGroup('${slug}', '${groupName}', this.checked)"> 
                                </div> 
                            </div>
                        </a>`;

                    $("#group-list-holder").prepend(elmt);
                }   
            }
            generatePageAllGroup();
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load').hide();
            failResponse(jqXHR, ajaxOptions, thrownError, "#group-list-holder", false, null, null);
            lastPageAllGroup = 1;
            generatePageAllGroup();
        });
    }

    function infinteLoadRole(page_role_list) {       
        document.getElementById("role-list-holder").innerHTML = "";

        var per_page = 24;
        if(isMobile()){
            per_page = 12;
        } 

        $.ajax({
            url: "/api/v1/tag/cat/" + tag_cat + "/"+per_page+ "?page=" + page_tag,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            var data =  response.data.data;
            var total = response.data.total;
            var last = response.data.last_page;

            if(page_role_list != last){
                $('#load_more_holder_new_req').html(`<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more_holder_new_req').html(`<h6 class="btn content-more-floating mb-3 p-2">{{ __('messages.no_more') }}</h6>`);
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html(`<h5 class='text-secondary'>{{ __('messages.all_viewed') }}</h5>`);
                return;
            } else {                
                for(var i = 0; i < data.length; i++){
                    var slug = data[i].slug_name;
                    var tagName = data[i].tag_name;
                    if(data[i].tag_category){
                        var category = data[i].tag_category;
                    } else {
                        var category = "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> No category</span>";
                    }

                    var elmt = `
                        <a class="btn user-box py-3" style="height:80px;" onclick="">
                            <div class="position-relative ps-2"> 
                                <h6 class="text-secondary fw-normal">${tagName}</h6> 
                                <h6 class="text-secondary fw-bold" style="font-size:13px;">${category}</h6>
                                <div class="form-check position-absolute" style="right: 20px; top: 10px;">
                                    <input class="form-check-input" name="slug_name[]" value="${slug}" type="checkbox" style="width: 25px; height:25px;" id="check_role_${slug}" onclick="addSelectedRole('${slug}', '${tagName}', this.checked)"> 
                                </div>
                            </div>
                        </a>`;

                    $("#role-list-holder").prepend(elmt);
                }   
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            $('.auto-load-tag').hide();
            failResponse(jqXHR, ajaxOptions, thrownError, "#role-list-holder", false, null, null);
        });
    }

    function getUserImageNoAdmin(img, role){
        if(img != null && img != "null"){
            return img;
        } else {
            if(role == "Lecturer"){
                return "{{ asset('/assets/default_lecturer.png')}}";
            } else {
                return "{{ asset('/assets/default_student.png')}}";
            }
        } 
    }

    function infinteLoadUser(page) {    
        page_curr_user = page;   
        function getFind(filter, find){
            let trim = find.trim();
            if(find == null || trim === ''){
                return filter;
            } else {
                document.getElementById("title_search").value = trim;
                return trim;
            }
        }

        var name_filter = 'all_all';
        var order = '<?= session()->get('ordering_user_list'); ?>';
        var find = document.getElementById("title_search").value;
        var per_page = 24;
        if(isMobile()){
            per_page = 12;
        } 

        $.ajax({
            url: "/api/v1/user/" + getFind(name_filter, find) + "/limit/"+per_page+ "/order/" + order + "/slug/all?page=" + page,
            datatype: "json",
            type: "get",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
                $('.auto-load').show();
            }
        })
        .done(function (response) {
            $('.auto-load').hide();
            $("#user-list-holder").empty();  
            
            var data =  response.data.data;
            var total = response.data.total;
            lastPageAllUser = response.data.last_page;

            if(page != lastPageAllUser){
                $('#load_more_holder_new_req').html(`<button class="btn content-more-floating mb-3 p-2" style="max-width:180px;" onclick="loadmore()">Show more <span id="textno"></span></button>`);
            } else {
                $('#load_more_holder_new_req').html(`<h6 class="btn content-more-floating mb-3 p-2">{{ __('messages.no_more') }}</h6>`);
            }

            if (total == 0) {
                $('#empty_item_holder_new_req').html("<img src="+'"'+"{{asset('assets/nodata.png')}}"+'"'+" class='img nodata-icon-req'><h6 class='text-secondary text-center'>No Event's found</h6>");
                return;
            } else if (data.length == 0) {
                $('.auto-load').html(`<h5 class='text-secondary'>{{ __('messages.all_viewed') }}</h5>`);
                return;
            } else {              
                for(var i = 0; i < data.length; i++){
                    var username = data[i].username;
                    var fullName = data[i].full_name;
                    var grole = data[i].general_role;
                    var img = data[i].image_url;
                    var role = data[i].role;
                    var email = data[i].email;
                    var joined = data[i].accepted_at;

                    var elmt = `
                        <a class="btn user-box" style="height:80px;"> 
                            <div class="row ps-2"> 
                                <div class="col-2 p-0 py-2 ps-2">
                                    <img class="img img-fluid user-image" src="${getUserImageNoAdmin(img, grole)}" alt="username-profile-pic.png"> 
                                </div> 
                                <div class="col-10 p-0 py-2 ps-2 position-relative"> 
                                    <h6 class="text-secondary fw-normal">${fullName}</h6>
                                    <h6 class="text-secondary fw-bold" style="font-size:13px;">${getRole(grole)}</h6> 
                                    <div class="form-check position-absolute" style="right: 20px; top: 20px;"> 
                                        <input class="form-check-input" name="user_username[]" value="${username}" type="checkbox" style="width: 25px; height:25px;" id="check_${username}" onclick="addSelectedUser('${username}', '${fullName}', this.checked)"> 
                                    </div> 
                                </div> 
                            </div>
                        </a>`;

                    $("#user-list-holder").prepend(elmt);
                }   
            }
            generatePageAllUser();
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            if (jqXHR.status == 404) {
                $('.auto-load').hide();
                $("#user-list-holder").html("<div class='err-msg-data d-block mx-auto'><img src='{{ asset('/assets/nodata.png')}}' class='img' style='width:250px;'><h6 class='text-secondary text-center'>No users found</h6></div>");
            } else {
                // handle other errors
            }
            lastPageAllUser = 1;
            generatePageAllUser();
        });
    }

    function generatePageAllUser(){
        $("#all-user-page").empty();
        for(var i = 1; i <= lastPageAllUser; i++){
            if(i == page_curr_user){
                var elmt = "<a class='page-holder active'>"+i+"</a>";
            } else {
                var elmt = "<a class='page-holder' onclick='infinteLoadUser("+'"'+i+'"'+")'>"+i+"</a>";
            }
            $("#all-user-page").append(elmt);
        }
    }

    function generatePageAllGroup(){
        $("#all-group-page").empty();
        for(var i = 1; i <= lastPageAllGroup; i++){
            if(i == page_curr_group){
                var elmt = "<a class='page-holder active'>"+i+"</a>";
            } else {
                var elmt = "<a class='page-holder' onclick='infinteLoadGroup("+'"'+i+'"'+")'>"+i+"</a>";
            }
            $("#all-group-page").append(elmt);
        }
    }

    function addSelectedRole(slug, tagname, checked){
        var input_holder = document.getElementById("list_context_role");
        if(selectedRole.length == 0){
            selectedRole.push({
                tag_name : tagname,
                slug_name : slug
            });
            input_holder.value = JSON.stringify(selectedRole);
        } else {
            if(checked === false){
                let indexToRemove = selectedRole.findIndex(obj => obj.slug_name == slug);
                if (indexToRemove !== -1) {
                    selectedRole.splice(indexToRemove, 1);

                    document.getElementById("check_role_"+slug).checked = false; 
                    input_holder.value = JSON.stringify(selectedRole);
                } else {
                    //
                }
            } else {
                selectedRole.push({
                    tag_name : tagname,
                    slug_name : slug
                });
                input_holder.value = JSON.stringify(selectedRole);
            }
        }
        refreshListRole();
    }

    function addSelectedUser(username, fullname, checked){
        var input_holder = document.getElementById("list_context");
        if(selectedUser.length == 0){
            selectedUser.push({
                full_name : fullname,
                username : username
            });
            input_holder.value = JSON.stringify(selectedUser);
        } else {
            if(checked === false){
                let indexToRemove = selectedUser.findIndex(obj => obj.username == username);
                if (indexToRemove !== -1) {
                    selectedUser.splice(indexToRemove, 1);

                    document.getElementById("check_"+username).checked = false; 
                    input_holder.value = JSON.stringify(selectedUser);
                } 
            } else {
                selectedUser.push({
                    full_name : fullname,
                    username : username
                });
                input_holder.value = JSON.stringify(selectedUser);
            }
        }
        refreshListUser();
    }

    function addSelectedGroup(slug, groupName, checked){
        var input_holder = document.getElementById("list_context_group");
        if(selectedGroup.length == 0){
            selectedGroup.push({
                slug : slug,
                groupName : groupName
            });
            input_holder.value = JSON.stringify(selectedGroup);
        } else {
            if(checked === false){
                let indexToRemove = selectedGroup.findIndex(obj => obj.slug == slug);
                if (indexToRemove !== -1) {
                    selectedGroup.splice(indexToRemove, 1);

                    document.getElementById("check_group_"+slug).checked = false; 
                    input_holder.value = JSON.stringify(selectedGroup);
                } 
            } else {
                selectedGroup.push({
                    slug : slug,
                    groupName : groupName
                });
                input_holder.value = JSON.stringify(selectedGroup);
            }
        }
        refreshListGroup();
    }

    function clearAllUser(){
        document.getElementById("slct-user-list-holder").innerHTML = "";
        selectedUser.forEach((e) => {
            document.getElementById("check_"+e.username).checked = false; 
        });
        selectedUser = [];
    }

    function clearAllGroup(){
        document.getElementById("slct-group-list-holder").innerHTML = "";
        selectedUser.forEach((e) => {
            document.getElementById("check_"+e.username).checked = false; 
        });
        selectedUser = [];
    }

    function refreshListUser(){
        var holder = document.getElementById("slct-user-list-holder");
        holder.innerHTML = " ";

        selectedUser.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelectedUser('+"'"+e.username+"'"+', '+"'"+e.fullName+"'"+', false)" title="Remove this user"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.full_name + '</a>';
            holder.innerHTML += elmt;
        });
        validateForm(validation);
    }

    function refreshListRole(){
        var holder = document.getElementById("slct-role-list-holder");
        holder.innerHTML = " ";

        selectedRole.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelectedRole('+"'"+e.slug_name+"'"+', '+"'"+e.tag_name+"'"+', false)" title="Remove this role"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.tag_name + '</a>';
            holder.innerHTML += elmt;
        });
        validateForm(validation);
    }

    function refreshListGroup(){
        var holder = document.getElementById("slct-group-list-holder");
        holder.innerHTML = " ";

        selectedGroup.forEach((e) => {
            var elmt = ' ' +
                '<a class="remove_suggest" onclick="addSelectedGroup('+"'"+e.slug+"'"+', '+"'"+e.groupName+"'"+', false)" title="Remove this group"> ' +
                '<i class="fa-sharp fa-solid fa-xmark me-2 ms-1"></i></a> ' +
                '<a>' + e.groupName + '</a>';
            holder.innerHTML += elmt;
        });
        validateForm(validation);
    }
</script>
