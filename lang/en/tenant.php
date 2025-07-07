<?php

return [
    'title' => 'Tenants',
    'subtitle' => 'Manage tenant accounts and subscriptions',
    
    // Statuses
    'statuses' => [
        'active' => 'Active',
        'trial' => 'Trial',
        'suspended' => 'Suspended',
        'cancelled' => 'Cancelled',
        'expired' => 'Expired',
    ],
    
    // Fields
    'fields' => [
        'name' => 'Tenant Name',
        'code' => 'Tenant Code',
        'legal_name' => 'Legal Name',
        'tax_number' => 'Tax Number',
        'tax_office' => 'Tax Office',
        'owner' => 'Owner',
        'status' => 'Status',
        'subscription_plan' => 'Subscription Plan',
        'subscription_starts_at' => 'Subscription Start',
        'subscription_ends_at' => 'Subscription End',
        'trial_ends_at' => 'Trial End',
        'user_limit' => 'User Limit',
        'event_limit' => 'Event Limit',
        'storage_limit' => 'Storage Limit',
        'current_users' => 'Current Users',
        'current_events' => 'Current Events',
        'current_storage' => 'Used Storage',
    ],
    
    // Messages
    'messages' => [
        'not_found' => 'Tenant not found or access denied',
        'access_denied' => 'You do not have access to this tenant',
        'subscription_expired' => 'Subscription has expired',
        'trial_expired' => 'Trial period has expired',
        'suspended' => 'Tenant account is suspended',
        'limit_exceeded' => 'Limit exceeded',
        'created_successfully' => 'Tenant created successfully',
        'updated_successfully' => 'Tenant updated successfully',
        'deleted_successfully' => 'Tenant deleted successfully',
    ],
    
    // Limits
    'limits' => [
        'users' => 'Users',
        'events' => 'Events',
        'storage' => 'GB Storage',
        'unlimited' => 'Unlimited',
    ],
]; 