<?php

return [
    'title' => 'Tenant Management',
    'singular' => 'Tenant',
    'plural' => 'Tenants',
    
    // Types
    'types' => [
        'individual' => 'Individual',
        'business' => 'Business',
        'enterprise' => 'Enterprise',
    ],
    
    // Statuses
    'statuses' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'expired' => 'Expired',
        'trial' => 'Trial',
    ],
    
    // Plans
    'plans' => [
        'basic' => 'Basic',
        'pro' => 'Professional',
        'enterprise' => 'Enterprise',
    ],
    
    // Fields
    'fields' => [
        'name' => 'Name',
        'code' => 'Code',
        'type' => 'Type',
        'status' => 'Status',
        'plan' => 'Plan',
        'email' => 'Email',
        'phone' => 'Phone',
        'created_at' => 'Created At',
    ],
    
    // Actions
    'actions' => [
        'create' => 'Create Tenant',
        'edit' => 'Edit Tenant',
        'delete' => 'Delete Tenant',
        'view' => 'View Tenant',
    ],
    
    // Messages
    'messages' => [
        'created' => 'Tenant created successfully.',
        'updated' => 'Tenant updated successfully.',
        'deleted' => 'Tenant deleted successfully.',
        'not_found' => 'Tenant not found.',
    ],
]; 