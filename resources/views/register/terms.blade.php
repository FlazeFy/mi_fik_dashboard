<style>
    #terms-holder{
        margin: 0;
        padding: 0;
        display: flex;
        max-height: 75vh;
        flex-direction: column;
        overflow-y: scroll;
    }
</style>
<div id="terms-holder">
    <h4 class="text-primary">Terms & Condition</h4>
    
    <p>Welcome to the Faculty of Creative Industries Information Management Application (MI-FIK). Before using this application, please read and understand carefully the following terms and conditions of use:</p>
    <b>Legal Compliance:</b>
    <ol>
        <li>By Using this application, you agree to comply with all applicable laws and regulations in your jurisdiction related to the use of this application</li>
        <li>Take full responsibility for any activities conducted through your account in this app</li>
    </ol>

    <b>Privacy and Security:</b>
    <ol>
        <li>We respect your privacy and are committed to protecting the personal data you provide</li>
        <li>You are responsible for maintaining the confidentiality and security of your account. Do not provide your login information to other parties and be sure to log out of your account when finished using the application</li>
    </ol>

    <b>User Content:</b>
    <ol>
        <li>When using this app, you agree not to upload, share, or distribute content that is unlawful, infringes intellectual property rights, or violates the privacy of others</li>
        <li>You are solely responsible for the content you share through this app. We are not responsible for inappropriate user content</li>
    </ol>

    <b>Changes and Updates::</b>
    <ol>
        <li>We reserve the right to change or update these terms and conditions from time to time without prior notice. Please be sure to check this page periodically to keep up to date with the latest information</li>
        <li>In case of important changes, we will provide notice to users through appropriate communication channels</li>
    </ol>

    <b>Limitation of Liability:</b>
    <ol>
        <li>This app is provided "as is" and we do not make any warranties or representations regarding the accuracy, reliability, or availability of this app</li>
        <li>We are not liable for any loss or damage arising from the use of this app or the inability to use it</li>
    </ol>
    
    <p>By agreeing to the terms and conditions in using the MI-FIK application, you agree and accept all the terms and conditions listed above. If you do not agree with these terms and conditions, please stop using this application. If you have any questions or concerns, please feel free to contact our support team.</p>

    <div class="form-check py-1">
        <input class="form-check-input" type="checkbox" onclick="validate('terms')" id="check-terms">
        <label class="form-check-label" for="flexCheckDefault">&nbsp <span class="text-primary">I agree to the terms and condition on this app</span>. Thank you for your understanding and cooperation</label><br>
        <a id="check_terms_msg" class="text-danger my-2" style="font-size:13px;"></a>
    </div>
</div>
<span id="btn-next-profile-data-holder">
    <button class="btn-next-steps locked" id="btn-next-profile-data" onclick="warn('terms')"><i class="fa-solid fa-lock"></i> Locked</button>
</span>