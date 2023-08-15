<div>
    <div class="text-center d-block mx-auto">
        <h4 class="text-primary">{{ __('messages.congrats') }}</h4>
        <h6 style="font-size:18px;" class="text-primary">{{ __('messages.forget_success') }}</h6>
        <img class="img img-fluid d-block mx-auto mt-3" style="width: 240px;" src="{{'/assets/welcome_3.png'}}">
        <p class="text-dark mt-2">{{ __('messages.back_to_login_desc') }}</p>
        <button class="btn btn-submit mt-3" onclick='is_finished = true; location.href="/"'><i class="fa-solid fa-check"></i> {{ __('messages.back_login') }}</button>
    </div>
</div>