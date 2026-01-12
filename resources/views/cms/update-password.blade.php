<section>
    <h2>Update Password</h2>
    <p>Ensure your account is using a strong password to stay secure.</p>

    <form id="update-password-form">
        @csrf
        <div class="col-lg-3">

        <div class="form-group inputicon">
            <input
                name="current_password"
                id="current_password"
                type="password"
                class="form-control"
                placeholder="Current Password">
            <span><i class="las la-lock"></i></span>
            <div id="error_current_password" class="text-danger mt-1"></div>
        </div>

        {{-- <div class="form-group inputicon">
            <input
                name="new_password"
                id="new_password"
                type="password"
                class="form-control"
                placeholder="New Password">
            <span><i class="las la-lock"></i></span>
            <div id="error_password" class="text-danger mt-1"></div>
        </div> --}}
        <div class="form-group inputicon">
                            <input name="new_password" autocomplete="off" id="password" type="password" placeholder="New Password"> <span><i class="las la-lock"></i></span>
                            <span id="eye-sign" class="hideshow-icon"><img id="show-hide-img" src="{{ asset('/cls-eye.svg') }}"></span>
                        </div>
        </div>

        <div class="btns-row mt-3">
            <button type="submit" class="button primary-btn" id="updatePasswordBtn">Update Password</button>
            <span id="update-success" class="text-success ms-3" style="display: none;">Password updated.</span>
        </div>
    </form>
    <div id="updatepass-response" class="text-danger mt-2"></div>
</section>
