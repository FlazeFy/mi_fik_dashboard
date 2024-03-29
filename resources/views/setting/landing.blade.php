@foreach($settingLanding as $stl)
    <form action="/setting/update_landing/{{$stl->id}}" method="POST">
        @csrf
        <div class="form-floating mb-3 mt-4">
            <input type="number" class="form-control" name="FAQ_range" min="4" max="100" value="{{$stl->FAQ_range}}" required>
            <label for="floatingInput">{{ __('messages.faq_range') }}</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="FBC_range" min="3" max="100" value="{{$stl->FBC_range}}" required>
            <label for="floatingInput">{{ __('messages.feedback_range') }}</label>
        </div>
        <div class="info-box tips m-0">
            <label><i class="fa-solid fa-circle-info"></i> Tips</label>
            <p>{{ __('messages.setting_system_desc') }}</p>
        </div>
        <br><br><br><br>
        <button class="btn btn-submit w-100 py-2" type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>
    </form>
@endforeach