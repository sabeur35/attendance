@extends('layouts.auth')

@section('content')
<div class="auth-row">
    <div class="auth-image">
        <div class="auth-image-overlay">
            <h2>Join as a Student</h2>
            <p>Register to access your courses and track your attendance easily.</p>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-header">
            <h1>Student Registration</h1>
            <p>Create your student account to get started</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-floating">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                <label for="name">{{ __('Full Name') }}</label>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-floating">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                <label for="email">{{ __('Email Address') }}</label>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-floating">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                <label for="password">{{ __('Password') }}</label>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-floating">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>
            </div>
            
            <div class="alert alert-success mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Suggested Password:</strong> 
                        <span id="password-display" class="user-select-all"></span>
                    </div>
                    <button id="copy-password-btn" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <small id="copy-feedback" class="d-none">Password copied!</small>
            </div>
            
            <div class="mb-3">
                <button id="generate-password-btn" class="btn btn-secondary w-100">
                    <i class="fas fa-key me-2"></i>Generate New Password
                </button>
            </div>
            
            <button type="submit" class="btn btn-auth">
                <i class="fas fa-user-plus me-2"></i>{{ __('Register as Student') }}
            </button>
            
            <div class="auth-footer">
                <div class="auth-divider"><span>OR</span></div>
                
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                <p>Are you an administrator? <a href="{{ route('admin.register.form') }}">Register as Admin</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/password-generator.js') }}"></script>
@endsection
