<script>
    let vldtContact = [
        { id: "instagram", req: false, len: 255 },
        { id: "website", req: false, len: 255 },
        { id: "whatsapp", req: true, len: 50 },
        { id: "address", req: true, len: 255 },
        { id: "email", req: true, len: 75 },
    ];
</script>

@foreach($ctc as $ct)
    @if($ct->help_category == "instagram")
        @php($ig = explode('/',$ct->help_body))
    @elseif($ct->help_category == "whatsapp")
        @php($wa = explode('/',$ct->help_body))
    @elseif($ct->help_category == "website")
        @php($web = $ct->help_body)
    @elseif($ct->help_category == "address")
        @php($adr = $ct->help_body)
    @elseif($ct->help_category == "email")
        @php($email = $ct->help_body)
    @endif
@endforeach

@if(session()->get('role_key') == 1 && session()->get('toogle_edit_contact') == "true")
    <div class="position-relative">
        <form class="d-inline position-absolute" style="right: 0; top:-35px;" method="POST" action="/about/toogle/contact/false">
            @csrf
            <button class="btn btn-danger rounded-pill mt-3 me-2 px-3 py-2" style="font-size: var(--textLG) !important;" type="submit"><i class="fa-solid fa-xmark"></i>@if(!$isMobile) Close @endif</button>
        </form>
        <form class="p-2 mt-2" action="/about/edit/contact" method="POST">
            @csrf
            <h6 class="mx-3 text-secondary fw-bold">{{ __('messages.socmed') }}</h6>
            <div class="mb-1">
                <label for="basic-url" class="form-label">Instagram</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">https://www.instagram.com/</span>
                    <input type="text" class="form-control nameInput" id="instagram" name="instagram" value="{{$ig[3]}}" oninput="validateForm(vldtContact)" maxlength="255" aria-describedby="basic-addon3">
                </div>
                <a id="instagram_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="mb-1">
                <label for="basic-url" class="form-label">{{ __('messages.web') }}</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control nameInput" id="website" name="website" value="{{$web}}" oninput="validateForm(vldtContact)" maxlength="255" aria-describedby="basic-addon3">
                </div>
                <a id="website_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="mb-1">
                <label for="basic-url" class="form-label">Whatsapp</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">https://wa.me/</span>
                    <input type="text" class="form-control nameInput" id="whatsapp" name="whatsapp" value="{{$wa[3]}}" oninput="validateForm(vldtContact)" maxlength="255" aria-describedby="basic-addon3">
                </div>
                <a id="whatsapp_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>

            <h6 class="mx-3 text-secondary fw-bold">{{ __('messages.addr') }}</h6>
            <div class="form-floating mb-2">
                <textarea class="form-control" style="height: 100px" id="address" name="address" value="{{$adr}}" oninput="validateForm(vldtContact)" maxlength="255">{{$adr}}</textarea>
                <label for="address">{{ __('messages.addr') }} {{ __('messages.location') }}</label>
                <a id="address_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control nameInput" id="email" name="email" value="{{$email}}" oninput="validateForm(vldtContact)" maxlength="50" required>
                <label for="email">Email</label>
                <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
            </div>
            <span id="submit_holder"><button disabled class="btn btn-submit-form"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button></span>
        </form>
    </div>
@else 
    <div class="position-relative px-2">
        @if(session()->get('role_key') == 1 && session()->get('toogle_edit_contact') == "false")
            <form class="d-inline" method="POST" action="/about/toogle/contact/true">
                @csrf
                <button class="btn btn-info rounded-pill toogle-edit-about" type="submit" style="@if(!$isMobile) right:10px; @else right:0; @endif top:-25px;"><i class="fa-regular fa-pen-to-square"></i>@if(!$isMobile) Edit @endif</button>
            </form>
        @endif  
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mt-3">
                <h6 class="fw-bold">{{ __('messages.contact_us') }}</h6><br>
                <p><i class="fa-brands fa-instagram fa-lg"></i>
                    <a class="link-external-dark" href="{{implode('/', $ig)}}">{{$ig[3]}}</a>
                </p>
                <p><i class="fa-brands fa-whatsapp fa-lg"></i>
                    <a class="link-external-dark" href="{{implode('/', $wa)}}">{{$wa[3]}}</a>
                </p>
                <p><i class="fa-solid fa-globe fa-lg"></i>
                    <a class="link-external-dark" href="{{$web}}">{{$web}}</a>
                </p>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mt-3">
                <h6 class="fw-bold">{{ __('messages.follow_us') }}</h6><br>
                <p class="link-external-dark" style="font-size:15px;"><i class="fa-solid fa-house"></i> {{$adr}} </p>
                <p class="link-external-dark"><i class="fa-solid fa-envelope"></i> {{$email}} </p>
            </div>
        </div>
    </div>
@endif
