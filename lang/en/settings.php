<?php

return [
    // Page
    'title' => 'Settings',
    'subtitle' => 'Manage your organization settings',
    
    // Tabs
    'tabs' => [
        'general' => 'General',
        'contact' => 'Contact',
        'localization' => 'Localization',
        'subscription' => 'Subscription',
    ],
    
    // Sections
    'sections' => [
        'organization_info' => 'Organization Information',
        'contact_info' => 'Contact Information',
        'address' => 'Address',
        'regional_settings' => 'Regional Settings',
        'datetime_formats' => 'Date & Time Formats',
    ],
    
    // Fields
    'fields' => [
        // General
        'organization_name' => 'Organization Name',
        'legal_name' => 'Legal Name',
        'organization_code' => 'Organization Code',
        'organization_id' => 'Organization ID',
        
        // Contact
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'website' => 'Website',
        
        // Address
        'address_line_1' => 'Address Line 1',
        'address_line_2' => 'Address Line 2',
        'city' => 'City',
        'state' => 'State/Province',
        'postal_code' => 'Postal Code',
        'country' => 'Country',
        
        // Localization
        'language' => 'Language',
        'timezone' => 'Timezone',
        'date_format' => 'Date Format',
        'time_format' => 'Time Format',
    ],
    
    // Placeholders
    'placeholders' => [
        'organization_name' => 'Enter your organization name',
        'legal_name' => 'Enter legal business name',
        'email' => 'info@example.com',
        'website' => 'https://www.example.com',
        'address_line_1' => 'Street address, P.O. box, company name',
        'address_line_2' => 'Apartment, suite, unit, building, floor, etc.',
        'city' => 'City or locality name',
        'state' => 'State, province, or region',
        'postal_code' => 'ZIP or postal code',
        'select_country' => 'Select a country',
        'select_language' => 'Select a language',
        'select_timezone' => 'Select a timezone',
    ],
    
    // Hints
    'hints' => [
        'organization_name' => 'This name will be displayed throughout the system',
        'legal_name' => 'Official registered name for legal documents and invoices',
        'organization_code' => 'Unique code assigned to your organization',
        'organization_id' => 'Unique system identifier for your organization',
        'email' => 'Primary contact email for your organization',
        'phone' => 'Primary contact number for business communications',
        'website' => 'Your organization\'s website URL',
        'address_line_1' => 'Street address or P.O. box',
        'address_line_2' => 'Additional address information (optional)',
        'city' => 'City or locality',
        'state' => 'State, province, or administrative region',
        'postal_code' => 'Postal or ZIP code',
        'country' => 'Country for address and regional settings',
        'language' => 'Default language for your organization',
        'timezone' => 'Timezone for scheduling and timestamps',
        'date_format' => 'How dates will be displayed throughout the system',
        'time_format' => 'How times will be displayed throughout the system',
        'no_changes' => 'No changes to save',
        'save_changes' => 'You have unsaved changes',
    ],
    
    // Date Formats
    'date_formats' => [
        'iso8601' => 'ISO 8601',
        'european' => 'European',
        'us' => 'US',
        'european_dot' => 'European (dot)',
        'european_dash' => 'European (dash)',
        'long' => 'Long format',
        'full' => 'Full format',
        'compact' => 'Compact',
        'medium' => 'Medium',
    ],
    
    // Time Formats
    'time_formats' => [
        '24_hour' => '24-hour',
        '24_hour_seconds' => '24-hour with seconds',
        '12_hour' => '12-hour',
        '12_hour_seconds' => '12-hour with seconds',
        '12_hour_no_leading' => '12-hour (no leading zero)',
        '12_hour_no_leading_seconds' => '12-hour with seconds (no leading zero)',
    ],
    
    // Subscription
    'subscription' => [
        'current_plan' => 'Current Plan',
        'plan' => 'Plan',
        'status' => 'Status',
        'expires' => 'Expires',
        'usage_statistics' => 'Usage Statistics',
        'users' => 'Users',
        'storage' => 'Storage',
        'events' => 'Events',
        'used' => 'used',
    ],
    
    // Messages
    'messages' => [
        'saved_successfully' => 'Settings saved successfully',
        'save_failed' => 'Failed to save settings. Please try again.',
        'datetime_updated' => 'Date and time settings updated. Page will reload to apply changes.',
    ],
]; 