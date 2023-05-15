<script>
    let validation = [
        { id: "username", req: true, len: 35 },
        { id: "email", req: true, len: 75 },
    ];
    let validation2 = [
        { id: "password", req: true, len: 75 },
        { id: "first_name", req: true, len: 75 },
        { id: "last_name", req: false, len: 75 },
    ];
    var val1 = false; 
    var val2 = false;
</script>

<style>
    .content-image-holder{
        position: relative;
        margin-top: 6px;
        margin-bottom: 6px; 
    }
    .content-image-holder .content-image{
        margin-inline: auto;
        display: block;
        border-radius: 100% !important;
        background-position: center;
        background-repeat:no-repeat;
        position: relative;
        background-size: cover;
        height:200px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .image-upload{
        position: absolute;
        bottom: 5px;
        right: 30px;
    }
    .image-upload>input {
        display: none;
    }
    .btn.change-image{
        width:45px; 
        height:45px; 
        background:#F78A00;
        border-radius: 100%;
        display: block;
        margin-inline: auto;
    }
    .content-image-holder .btn-icon-reset-image{
        position: absolute; 
        bottom: 10px; 
        left: 30px;
        width:45px; 
        height:45px; 
        padding-top: 8px;
        border-radius: 100%;
        background: #e74645 !important;
        color:#ffffff !important;
    }
    .content-image-holder .status-holder{
        position: absolute; 
        bottom: 10px; 
        left: 60px;
    }
</style>

<div>
    <h4 class="text-primary">Profile Data</h4>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-floating mb-3">
                <input type="text" class="form-control nameInput" id="username" name="username" oninput="validateForm(validation)" maxlength="35" required>
                <label for="username">Username</label>
                <a id="username_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-floating mb-3">
                <input type="email" class="form-control nameInput" id="email" name="email" oninput="validateForm(validation)" maxlength="75" required>
                <label for="email">Email</label>
                <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating mb-3">
                <input type="password" class="form-control nameInput" id="password" name="password" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="password">Password</label>
                <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-floating mb-3" style="max-width:160px;">
                <select class="form-select" id="valid_until" name="valid_until" aria-label="Floating label select example" onchange="validateFormSecond(validation2)" required>
                    <option value="{{date('Y')}}" selected>{{date('Y')}}</option>
                    @php($vu_list = [])
                    @php($now = (int)date('Y-m-d'))
                    @for($i = 0; $i < 6; $i++)
                        @php(array_push($vu_list, (int)date('Y', strtotime('-'.$i.' years', strtotime($now)))))
                        @php(array_push($vu_list, (int)date('Y', strtotime('+'.$i.' years', strtotime($now)))))
                    @endfor

                    @php(rsort($vu_list))
                    @php($vu_list = array_values(array_unique($vu_list)))
                    @foreach($vu_list as $vu)
                        <option value="{{$vu}}">{{$vu}}</option>
                    @endforeach
                    
                </select>
                <label for="valid_until">Valid Until</label>
                <a id="valid_until_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control nameInput" id="first_name" name="first_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="first_name">First Name</label>
                <a id="first_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-floating mb-3">
                <input type="text" class="form-control nameInput" id="last_name" name="last_name" oninput="validateFormSecond(validation2)" maxlength="75" required>
                <label for="last_name">Last Name</label>
                <a id="last_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        </div>
    </div>
    <a id="input_all_profiledata_msg" class="text-danger my-2" style="font-size:13px;"></a>

    <h4 class="text-primary">Profile Image</h4>
    <div class="row">
        <div class="col-lg-6 p-4">
            <div class="content-image-holder">
                <img id="frame" class="content-image img img-fluid" src="{{ asset('/assets/default_lecturer.png')}}">
                <div class='image-upload' id='formFileImg'>
                    <label for='file-input'>
                        <img class='btn change-image shadow position-relative p-1' title='Change Image' src="{{asset('assets/change_image.png')}}"/>
                    </label>
                    <input id='file-input' type='file' accept="image/*" value="" onchange='setValueContentImage()'/>
                </div>
                <input hidden type="text" name="content_image" id="content_image_url" value="">
                <a class="btn btn-icon-reset-image shadow" title="Reset to default image" onclick="clearImage()"><i class="fa-solid fa-trash-can fa-lg"></i></a>
                <span class="status-holder shadow">
                    <a class="attach-upload-status success" id="header-progress"></a>
                    <a class="attach-upload-status danger" id="header-failed"></a>
                    <a class="attach-upload-status warning" id="header-warning"></a>
                </span>
            </div>
        </div>
    </div>
</div>
<span id="btn-next-role-holder">
    <button class="btn-next-steps locked" id="btn-next-profile-role" onclick="warn('profiledata')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>