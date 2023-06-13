<script>
    let vldtContact = [
        { id: "instagram", req: false, len: 255 },
        { id: "twitter", req: false, len: 255 },
        { id: "whatsapp", req: true, len: 50 },
        { id: "address", req: true, len: 255 },
        { id: "email", req: true, len: 75 },
    ];
</script>

@foreach($ctc as $ct)
    @if($ct->help_category == "instagram")
        @php($ig = $ct->help_body)
    @elseif($ct->help_category == "whatsapp")
        @php($wa = $ct->help_body)
    @elseif($ct->help_category == "twitter")
        @php($twt = $ct->help_body)
    @elseif($ct->help_category == "address")
        @php($adr = $ct->help_body)
    @elseif($ct->help_category == "email")
        @php($email = $ct->help_body)
    @endif
@endforeach

@if(session()->get('role_key') == 1)
    <form class="p-2 mt-2" action="/about/edit/contact" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h6 class="mx-3 text-secondary fw-bold">Social Media</h6>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="instagram" name="instagram" value="{{$ig}}" oninput="validateForm(vldtContact)" maxlength="255">
                    <label for="instagram">Instagram</label>
                    <a id="instagram_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="twitter" name="twitter" value="{{$twt}}" oninput="validateForm(vldtContact)" maxlength="255">
                    <label for="twitter">Twitter</label>
                    <a id="twitter_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="whatsapp" name="whatsapp" value="{{$wa}}" oninput="validateForm(vldtContact)" maxlength="50" required>
                    <label for="whatsapp">Whatsapp</label>
                    <a id="whatsapp_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h6 class="mx-3 text-secondary fw-bold">Address</h6>
                <div class="form-floating mb-2">
                    <textarea class="form-control" style="height: 100px" id="address" name="address" value="{{$adr}}" oninput="validateForm(vldtContact)" maxlength="255">{{$adr}}</textarea>
                    <label for="address">Address Location</label>
                    <a id="address_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control nameInput" id="email" name="email" value="{{$email}}" oninput="validateForm(vldtContact)" maxlength="50" required>
                    <label for="email">Email</label>
                    <a id="email_msg" class="text-danger my-2" style="font-size:13px;"></a>
                </div>
            </div>
        </div>
        <button class="btn btn-success mt-3"><i class="fa-solid fa-floppy-disk"></i> Save Chages</button>
    </form>
@else 
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mt-3">
            <h6 class="fw-bold">Follow us</h6><br>
            <p><i class="fa-brands fa-instagram fa-lg"></i><a class="link-external-dark" href="{{$ig}}"> Intagram</a></p>
            <p><i class="fa-brands fa-facebook fa-lg"></i><a class="link-external-dark" href="{{$wa}}"> Whatsapp</a></p>
            <p><i class="fa-brands fa-twitter fa-lg"></i><a class="link-external-dark" href="{{$twt}}"> Twitter</a></p>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 mx-auto mt-3">
            <h6 class="fw-bold">Contact us</h6><br>
            <p class="link-external-dark" style="font-size:15px;"><i class="fa-solid fa-house"></i> {{$adr}} </p>
            <p class="link-external-dark"><i class="fa-solid fa-envelope"></i> {{$email}} </p>
        </div>
    </div>
@endif
