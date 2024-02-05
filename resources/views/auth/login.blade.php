@extends('auth.layouts')

@section('content')
<main class="login--main-container">
    <div class="login--wrapper">
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Signup Form</div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @elseif ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>       
        @endif 
        <div class="login--form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup">Signup</label>
                <div class="slider-tab"></div>
            </div>

            <div class="login--form-inner">
                <form action="{{ route('authenticate') }}" method="post" class="login">
                    @csrf
                    <div class="field">
                        <input type="email" class="@error('name') is-invalid @enderror" name="email" placeholder="Email Address" required />
                    </div>
                    <div class="field">
                        <input type="password" class="@error('name') is-invalid @enderror" name="password" placeholder="Password" required />
                    </div>

                    <div class="btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Login">
                    </div>
                    <div class="signup-link">Not a member? <a href="">Signup now</a></div>
                </form>

                <form action="{{ route('store') }}" method="post" class="signup">
                    @csrf
                    <div class="field">
                        <input type="text" class="@error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Name" />
                    </div>

                    <div class="field">
                        <input type="email" class="@error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" />
                    </div>
                    <div class="field">
                        <input type="password" class="@error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" />
                    </div>
                    <div class="field">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Password Confirmation" />
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Signup">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const loginText = document.querySelector(".title-text .login");
    const loginForm = document.querySelector("form.login");
    const loginBtn = document.querySelector("label.login");
    const signupBtn = document.querySelector("label.signup");
    const signupLink = document.querySelector("form .signup-link a");

    signupBtn.onclick = (() => {
        loginForm.style.marginLeft = "-50%";
        loginText.style.marginLeft = "-50%";
    });

    loginBtn.onclick = (()=>{
        loginForm.style.marginLeft = "0%";
        loginText.style.marginLeft = "0%";
    });

    signupLink.onclick = (() => {
        signupBtn.click();
        return false;
    });
</script>

@endsection