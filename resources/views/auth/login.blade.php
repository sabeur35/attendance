@extends('layouts.auth')

@section('content')
<div class="auth-row">
    <div class="auth-image">
        <div class="auth-image-overlay">
            <h2>Welcome Back!</h2>
            <p>Log in to access your attendance dashboard and manage your courses.</p>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-header">
            <h1>Sign In</h1>
            <p>Enter your credentials to access your account</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-floating">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                <label for="email">{{ __('Email Address') }}</label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-floating">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                <label for="password">{{ __('Password') }}</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            
            <button type="submit" class="btn btn-auth">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Sign In') }}
            </button>
            
            <div class="auth-footer">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
                
                <div class="auth-divider"><span>OR</span></div>
                
                <p>Don't have an account? 
                    <a href="{{ route('register') }}">Register as Student</a> | 
                    <a href="{{ route('admin.register.form') }}">Register as Admin</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
