<!-- resources/views/auth/mfa.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Two-Factor Authentication Code') }}</div>

                <div class="card-body">
                    <!-- Form for verifying MFA code -->
                    <form method="POST" action="{{ route('mfa.verify.submit') }}">
                        @csrf

                        <div class="form-group">
                            <label for="code">{{ __('Verification Code') }}</label>
                            <input type="text" class="form-control" id="code" name="code" required autofocus>
                        </div>

                        <!-- Display success message if status is set in session -->
                        @if (session('status'))
                        <div class="alert alert-success mt-3">
                            {{ session('status') }}
                        </div>
                        @endif

                        <!-- Display error message if there's a validation error for the MFA code -->
                        @error('code')
                        <div class="alert alert-danger mt-3">
                            {{ $message }}
                        </div>
                        @enderror

                        <!-- Submit button for verifying the MFA code -->
                        <button type="submit" class="btn btn-primary mt-3">{{ __('Verify') }}</button>
                    </form>

                    <!-- Form for resending MFA code -->
                    <form method="POST" action="{{ route('mfa.resend') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-link">{{ __('Resend Code') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection