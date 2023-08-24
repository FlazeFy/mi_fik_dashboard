@foreach($ctc as $ct)
    @if($ct->help_category == "instagram")
        @php($ig = $ct->help_body)
    @elseif($ct->help_category == "whatsapp")
        @php($wa = $ct->help_body)
    @elseif($ct->help_category == "website")
        @php($web = $ct->help_body)
    @elseif($ct->help_category == "address")
        @php($adr = $ct->help_body)
    @elseif($ct->help_category == "email")
        @php($email = $ct->help_body)
    @endif
@endforeach

<footer class="page-footer">
	<div class="container-fluid text-center text-md-left">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 mx-auto mt-3">
                <h4 class="fw-bold text-white">{{ __('messages.about_us') }}</h4><br>
                <p class="text-white">{{ __('messages.about_us_desc')}}</p>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 mx-auto mt-3">
                <h4 class="fw-bold text-white">{{ __('messages.follow_us') }}</h4><br>
                <p class="text-white"><i class="fa-brands fa-instagram fa-lg"></i><a class="link-external-white" href="{{$ig}}"> Instagram</a></p>
                <p class="text-white"><i class="fa-brands fa-whatsapp fa-lg"></i><a class="link-external-white" href="{{$wa}}"> Whatsapp</a></p>
                <p class="text-white"><i class="fa-solid fa-globe fa-lg"></i><a class="link-external-white" href="{{$web}}"> {{ __('messages.website') }}</a></p>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 mx-auto mt-3">
                <h4 class="fw-bold text-white">{{ __('messages.contact_us') }}</h4><br>
                <p class="link-external-white" style="font-size:15px;"><i class="fa-solid fa-house"></i> {{$adr}} </p>
                <p class="link-external-white"><i class="fa-solid fa-envelope"></i> {{$email}} </p>
            </div>
        </div>

        <br>
        <div class="footer-copyright text-center py-3 text-white">© 2023 Copyright:
            <a href="https://mifik.id/" class="link-external-white">www.mifik.id</a>
        </div>
    </div>
</footer>