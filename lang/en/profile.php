<?php

return [
    'title' => 'Profile',
    'subtitle' => 'Update your profile information',
    
    // Sections
    'sections' => [
        'personal_info' => 'Personal Information',
        'contact_info' => 'Contact Information',
        'password' => 'Change Password',
        'preferences' => 'Preferences',
        'activity' => 'Activity',
    ],
    
    // Fields
    'fields' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'username' => 'Username',
        'email' => 'Email',
        'phone' => 'Phone',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
        'language' => 'Language',
        'timezone' => 'Timezone',
        'date_format' => 'Date Format',
        'time_format' => 'Time Format',
    ],
    
    // Labels
    'labels' => [
        'password_hint' => 'Leave blank to keep unchanged',
        'last_login' => 'Last Login',
        'account_created' => 'Account Created',
        'member_since' => 'Member Since',
    ],
    
    // Messages
    'messages' => [
        'updated_successfully' => 'Profile updated successfully',
        'password_changed' => 'Password changed successfully',
        'current_password_incorrect' => 'Current password is incorrect',
        'passwords_dont_match' => 'Passwords do not match',
    ],
]; 