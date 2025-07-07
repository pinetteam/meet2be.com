<?php

return [
    'title' => 'Users',
    'subtitle' => 'Manage system users and permissions',
    
    // Actions
    'actions' => [
        'add' => 'Add User',
        'create' => 'Create User',
        'edit' => 'Edit User',
        'update' => 'Update User',
        'delete' => 'Delete User',
        'view' => 'View User',
        'back_to_list' => 'Back to Users',
        'back_to_user' => 'Back to User',
        'all_users' => 'All Users',
        'add_first' => 'Add First User',
    ],
    
    // Labels
    'labels' => [
        'details' => 'User Details',
        'create_new' => 'Create a new system user',
        'update_info' => 'Update user information',
        'view_info' => 'View user information',
        'no_users' => 'No users found',
        'adjust_filters' => 'Try adjusting your search or filter criteria',
        'search_placeholder' => 'Name, email, username...',
        'all_types' => 'All Types',
        'all_statuses' => 'All Statuses',
        'select_type' => 'Select user type',
        'select_status' => 'Select status',
        'select_tenant' => 'Select a tenant',
        'no_tenant' => 'No Tenant',
        'never_logged' => 'Never logged in',
    ],
    
    // Sections
    'sections' => [
        'basic_info' => 'Basic Information',
        'account_info' => 'Account Information',
        'password_info' => 'Password Information',
        'permissions' => 'User Permissions',
        'login_activity' => 'Login Activity',
        'system_info' => 'System Information',
    ],
    
    // Fields
    'fields' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'username' => 'Username',
        'email' => 'Email',
        'phone' => 'Phone',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'new_password' => 'New Password',
        'confirm_new_password' => 'Confirm New Password',
        'user_type' => 'User Type',
        'status' => 'Status',
        'tenant' => 'Tenant',
        'tenant_id' => 'Tenant ID',
        'user_id' => 'User ID',
        'last_login' => 'Last Login',
        'last_ip' => 'Last IP Address',
        'last_user_agent' => 'Last User Agent',
        'settings' => 'User Settings',
    ],
    
    // User Types
    'types' => [
        'admin' => 'Administrator',
        'screener' => 'Screener',
        'operator' => 'Operator',
    ],
    
    // Messages
    'messages' => [
        'created_successfully' => 'User created successfully',
        'updated_successfully' => 'User updated successfully',
        'deleted_successfully' => 'User deleted successfully',
        'delete_confirm' => 'Are you sure you want to delete this user?',
        'password_hint' => 'Leave password fields blank to keep current password',
    ],
    
    // Table headers
    'table' => [
        'user' => 'User',
        'type' => 'Type',
        'status' => 'Status',
        'last_login' => 'Last Login',
        'actions' => 'Actions',
    ],
]; 