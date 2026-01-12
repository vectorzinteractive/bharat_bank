<section>
    <h2>Profile Information</h2>
    <p>Update your account's profile information and email address.</p>

    <!-- Email Verification Resend Form -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Profile Update Form -->
    <form method="post" action="" id="update-profile-form">
        @csrf
        @method('patch')
        <div class="col-lg-3">
        <div class="form-group inputicon">
            <input
                name="name"
                autocomplete="off"
                id="name"
                type="text"
                placeholder="Name"
                value="{{ old('name', $user->name) }}">
            <span><i class="las la-user"></i></span>
        </div>

        <div class="form-group inputicon">
            <input
                name="email"
                autocomplete="off"
                id="email"
                type="email"
                placeholder="Email"
                value="{{ old('email', $user->email) }}">
            <span><i class="las la-envelope"></i></span>


            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm">
                    <p>Your email address is unverified.</p>
                    <button form="send-verification" class="btn btn-link p-0">
                        Click here to re-send the verification email.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-1">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>
        </div>
        <div class="btns-row mt-3">
            <button type="submit" class="button primary-btn" id="update-profile-btn">Update Profile</button>
        </div>
    </form>
    <div id="file2_err" class="text-danger mt-2"></div>
</section>
