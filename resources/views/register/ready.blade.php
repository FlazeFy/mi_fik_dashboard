<div>
    <div class="text-center d-block mx-auto">
        <h2 class="text-primary">{{ __('messages.congrats') }}</h2>
        <lottie-player class="d-block mx-auto" src="https://assets7.lottiefiles.com/packages/lf20_fbwbq3um.json"  background="transparent" speed="0.75" style="width: 400px; height: 400px;" autoplay></lottie-player>
            <p style="font-size:18px;">{{ __('messages.congrats_regis_desc') }}</p>
            <button class="btn btn-submit" onclick='is_finished = true; location.href="/"'><i class="fa-solid fa-check"></i> {{ __('messages.back_login') }}</button>
        </div>
</div>