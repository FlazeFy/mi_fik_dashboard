<img class="logo-big-res" src="{{asset('assets/usermanage.png')}}">
<p class="text-primary" style="font-size:26px;">Sorry {{session()->get('username_key')}}. But you don't have access to Mi-FIK</p>

@if($found)
    <p style="font-size:18px;">Your account is not accepted yet by Admin. Please wait some moment or try to contact the 
    <a class="text-primary text-decoration-none" title="Send email" href="mailto:hello@mifik.id">Admin</a></p>
@else
    <p style="font-size:18px;">You either not have this <span class="btn btn-tag" title="General Tag">Lecturer</span> or <span class="btn btn-tag" title="General Tag">Staff</span> as your <b>Role</b></p>
@endif
