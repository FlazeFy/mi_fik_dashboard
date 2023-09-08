<div>
    <h2 class="text-primary text-center">{{ __('messages.tnc') }}</h2><br>
    
    <p>{{ __('messages.tnc_desc') }}</p>
    <b>{{ __('messages.legal_compliance') }}</b>
    <ol>
        <li>{{ __('messages.legal_compliance_1') }}</li>
        <li>{{ __('messages.legal_compliance_2') }}</li>
    </ol>

    <b>{{ __('messages.priv_sec') }}</b>
    <ol>
        <li>{{ __('messages.priv_sec_1') }}</li>
        <li>{{ __('messages.priv_sec_2') }}</li>
    </ol>

    <b>{{ __('messages.user_content') }}</b>
    <ol>
        <li>{{ __('messages.user_content_1') }}</li>
        <li>{{ __('messages.user_content_2') }}</li>
    </ol>

    <b>{{ __('messages.change_update') }}</b>
    <ol>
        <li>{{ __('messages.change_update_1') }}</li>
        <li>{{ __('messages.change_update_2') }}</li>
    </ol>

    <b>{{ __('messages.limit_liability') }}</b>
    <ol>
        <li>{{ __('messages.limit_liability_1') }}</li>
        <li>{{ __('messages.limit_liability_2') }}</li>
    </ol>
    
    <p>{{ __('messages.acc_tnc') }}</p>

    <div class="form-check py-1">
        <input class="form-check-input" type="checkbox" onclick="validate('terms')" id="check-terms">
        <label class="form-check-label" for="flexCheckDefault">&nbsp <span class="text-primary">{{ __('messages.i_acc_tnc') }}</span>. {{ __('messages.i_acc_tnc_2') }}</label><br>
        <a id="check_terms_msg" class="text-danger my-2" style="font-size:13px;"></a>
    </div>
</div>
<span id="btn-next-profile-data-holder" class="d-flex justify-content-end">
    <button class="btn-next-steps locked" id="btn-next-profile-data" onclick="warn('terms')"><i class="fa-solid fa-lock"></i> {{ __('messages.locked') }}</button>
</span>