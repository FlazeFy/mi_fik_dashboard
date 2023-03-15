<style>
    .form-floating{
        max-width: 400px;
    }
    .form-floating .form-control{
        border: none;
        border-bottom: 3px solid #212121;
        border-radius: 0;
    }
    .btn-sign-in{
        background: #F78A00;
        border-radius: 30px;
        color: whitesmoke !important;
        font-weight: 500;
        max-width: 400px;
        padding: 8px;
    }
    .btn-forgot-password{
        font-weight: 500;
        max-width: 160px;
        float: right !important;
    }
    .form-control:focus{
        box-shadow: none !important;
    }
</style>

<div class="container-fluid shadow rounded my-4 py-5 text-start d-block mx-auto" style="max-width:450px;">
    <form action="/login" method="POST">
        @csrf
        <h4 class="fw-bold mt-4">Welcome Administrator</h4>
        <h6 class="fw-bold mb-5">Silahkan login dan mulai mengatur data MI-FIK</h6>
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" required>
            <label for="floatingUsername">Username</label>
        </div>
        <div class="form-floating mt-3">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
            <label for="floatingPassword">Password</label>
        </div>
        <a class="btn btn-forgot-password w-100 mt-5">Forgot Password ?</a>
        <button type="submit" class="btn btn-sign-in w-100 mt-3 mb-5">Sign In</button>
    </form>
</div>