<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Login
    'login' => [
        'title' => 'Sign In',
        'heading' => 'Sign in to your account',
        'email' => 'Email Address',
        'password' => 'Password',
        'remember' => 'Remember me',
        'forgot' => 'Forgot your password?',
        'submit' => 'Sign In',
        'no_account' => 'Don\'t have an account?',
        'register_link' => 'Register',
    ],
    
    // Register
    'register' => [
        'title' => 'Register',
        'heading' => 'Create new account',
        'name' => 'Full Name',
        'email' => 'Email Address',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'terms' => 'I agree to the terms and conditions',
        'submit' => 'Register',
        'have_account' => 'Already have an account?',
        'login_link' => 'Sign in',
    ],
    
    // Password reset
    'reset' => [
        'title' => 'Reset Password',
        'heading' => 'Reset your password',
        'email' => 'Email Address',
        'password' => 'New Password',
        'confirm_password' => 'Confirm Password',
        'submit' => 'Reset Password',
        'send_link' => 'Send Reset Link',
    ],
    
    // Email verification
    'verify' => [
        'title' => 'Email Verification',
        'heading' => 'Verify your email address',
        'sent' => 'Verification link has been sent to your email.',
        'check' => 'Please check your email before continuing.',
        'not_received' => 'Didn\'t receive the email?',
        'resend' => 'Resend',
    ],
    
    // Messages
    'messages' => [
        'logged_in' => 'You have successfully logged in.',
        'logged_out' => 'You have successfully logged out.',
        'registered' => 'Your account has been created successfully.',
        'verified' => 'Your email address has been verified.',
        'reset_sent' => 'Password reset link has been sent to your email.',
        'reset_success' => 'Your password has been reset successfully.',
        'invalid_token' => 'Invalid or expired link.',
    ],
];
