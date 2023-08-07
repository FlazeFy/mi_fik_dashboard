<script>
    let validation = [
        { id: "first_name", req: true, len: 35 },
        { id: "last_name", req: false, len: 35 },
        // { id: "password", req: true, len: 50 },
        <?php
            if(session()->get("role_key") == 1){
                echo '{ id: "phone", req: true, len: 14 }';
            }
        ?>
    ];
</script>

<div class="position-relative">
    <form class="p-2 mt-2" action="/profile/edit/profile" method="POST">
        @csrf
        <label class="text-secondary">Username</label>
        <h6>{{$user->username}}</h6>
        <label class="text-secondary mt-2">Email</label>
        <h6>{{$user->email}}</h6>
        
        @if(session()->get("role_key") == 1)
            <div class="form-floating mb-2 mt-3">
                <input type="phone" class="form-control nameInput" id="phone" name="phone" value="{{$user->phone}}" oninput="validateForm(validation)" maxlength="14" required>
                <label for="phone">{{ __('messages.phone') }}</label>
                <a id="phone_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
        @endif

        @if(!$isMobile)
            <div class="row mb-2 mt-4">
                <div class="col">
        @endif

        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="first_name" name="first_name" oninput="validateForm(validation)" maxlength="35" value="{{$user->first_name}}" required>
            <label for="first_name">{{ __('messages.fname') }}</label>
            <a id="first_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>

        @if(!$isMobile)
                </div>
            <div class="col">
        @endif

        <div class="form-floating mb-2">
            <input type="text" class="form-control nameInput" id="last_name" name="last_name" oninput="validateForm(validation)" maxlength="35" value="{{$user->last_name}}">
            <label for="last_name">{{ __('messages.lname') }}</label>
            <a id="last_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>

        @if(!$isMobile)
                </div>
            </div>
        @endif
        <!-- <div class="input-group form-floating mb-2 rounded">
            <input type="password" class="form-control nameInput" id="password" name="password" oninput="validateForm(validation)" value="{{$user->password}}" maxlength="50" required>
            <a type="button" class="btn btn-info py-3 rounded" onclick="viewPassword()" id="btn-toogle-pwd"><i class="fa-sharp fa-solid fa-eye-slash"></i></a>
            <label for="password">Password</label>
        </div>
        <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a><br> -->
        @if($info)
            @foreach($info as $in)
                @php($ctx = null)
                @if(session()->get("role_key") == 1)
                    @php($ctx = "edit_profile_admin")
                @else 
                    @php($ctx = "edit_profile_user")
                @endif
                @if($in->info_location == $ctx)
                    <div class="info-box {{$in->info_type}} mt-4">
                        <label><i class="fa-solid fa-circle-info"></i> {{ucfirst($in->info_type)}}</label><br>
                        <?php echo $in->info_body; ?>
                    </div>
                @endif
            @endforeach
        @endif
        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
    </form>
</div>

<script>
    function viewPassword(){
        pwd = document.getElementById("password");
        btn = document.getElementById("btn-toogle-pwd");

        if(pwd.getAttribute('type') == "text"){
            pwd.setAttribute('type', 'password');
            btn.innerHTML = `<i class="fa-sharp fa-solid fa-eye-slash"></i>`;
        } else {
            pwd.setAttribute('type', 'text');
            btn.innerHTML = `<i class="fa-sharp fa-solid fa-eye"></i>`;
        }
    }
</script>