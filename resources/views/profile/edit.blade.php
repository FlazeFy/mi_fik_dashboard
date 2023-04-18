<script>
    let validation = [
        { id: "first_name", req: true, len: 35 },
        { id: "last_name", req: false, len: 35 },
        { id: "password", req: true, len: 50 },
    ];
</script>

<div class="position-relative">
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button"
        data-bs-toggle="popover" title="Info" data-bs-content="Tag is ... ...."><i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <form class="p-2 mt-2" action="/profile/edit/profile" method="POST">
        @csrf
        <div class="row mb-2">
            <div class="col">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="first_name" name="first_name" oninput="validateForm(validation)" maxlength="35" value="{{$user->first_name}}" required>
                    <label for="first_name">First Name</label>
                    <a id="first_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
            <div class="col">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="last_name" name="last_name" oninput="validateForm(validation)" maxlength="35" value="{{$user->last_name}}">
                    <label for="last_name">Last Name</label>
                    <a id="last_name_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
        </div>
        <div class="form-floating mb-3">
            <input type="username" class="form-control nameInput" id="username" name="username" value="{{$user->username}}" disabled required>
            <label for="username">Username</label>
            <a id="username_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control nameInput" id="email" name="email" value="{{$user->email}}" disabled required>
            <label for="email">Email</label>
            <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
        </div>
        <div class="input-group form-floating mb-2 rounded">
            <input type="password" class="form-control nameInput" id="password" name="password" oninput="validateForm(validation)" value="{{$user->password}}" maxlength="50" required>
            <a type="button" class="btn btn-info py-3 rounded" onclick="viewPassword()" id="btn-toogle-pwd"><i class="fa-sharp fa-solid fa-eye-slash"></i></a>
            <label for="password">Password</label>
        </div>
        <a id="password_msg" class="text-danger my-2" style="font-size:13px;"></a><br>

        <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> Locked</button></span>
    </form>
</div>

<script>
    function viewPassword(){
        pwd = document.getElementById("password");
        btn = document.getElementById("btn-toogle-pwd");

        if(pwd.getAttribute('type') == "text"){
            pwd.setAttribute('type', 'password');
            btn.innerHTML = '<i class="fa-sharp fa-solid fa-eye-slash"></i>';
        } else {
            pwd.setAttribute('type', 'text');
            btn.innerHTML = '<i class="fa-sharp fa-solid fa-eye"></i>';
        }
    }
</script>