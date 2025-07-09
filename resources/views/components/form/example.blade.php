{{-- Meet2Be: Form Components Example Page --}}
{{-- Author: Meet2Be Development Team --}}
{{-- This file demonstrates all form components usage --}}

@extends('layouts.portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Form Components Example</h1>
    
    <form x-data="{ formData: {} }" class="space-y-12">
        @csrf
        
        {{-- Text Inputs Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Text Inputs</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Basic Text Input --}}
                <x-form.input 
                    type="text"
                    name="full_name"
                    label="Full Name"
                    placeholder="Enter your full name"
                    hint="Your first and last name"
                    required />
                
                {{-- Email Input --}}
                <x-form.input 
                    type="email"
                    name="email"
                    label="Email Address"
                    placeholder="john@example.com"
                    required />
                
                {{-- Password Input --}}
                <x-form.input 
                    type="password"
                    name="password"
                    label="Password"
                    placeholder="Enter password"
                    hint="Minimum 8 characters"
                    required />
                
                {{-- Text with Prefix --}}
                <x-form.input 
                    type="text"
                    name="website"
                    label="Website"
                    prefix="https://"
                    placeholder="example.com" />
                
                {{-- Text with Suffix --}}
                <x-form.input 
                    type="text"
                    name="price"
                    label="Price"
                    suffix="USD"
                    placeholder="100.00" />
                
                {{-- Disabled Input --}}
                <x-form.input 
                    type="text"
                    name="tenant_id"
                    label="Organization ID"
                    value="550e8400-e29b-41d4-a716-446655440000"
                    disabled />
            </div>
        </div>
        
        {{-- Select Components Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Select Components</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Simple Select --}}
                <x-form.select
                    name="role"
                    label="User Role"
                    placeholder="Select a role"
                    required>
                    <option value="admin">Administrator</option>
                    <option value="user">User</option>
                    <option value="guest">Guest</option>
                </x-form.select>
                
                {{-- Searchable Select --}}
                <x-form.select
                    name="department"
                    label="Department"
                    placeholder="Select department"
                    searchable>
                    <option value="sales">Sales</option>
                    <option value="marketing">Marketing</option>
                    <option value="engineering">Engineering</option>
                    <option value="hr">Human Resources</option>
                    <option value="finance">Finance</option>
                    <option value="operations">Operations</option>
                </x-form.select>
                
                {{-- Country Select --}}
                <x-form.specialized.country-select
                    name="country_id"
                    label="Country"
                    required />
                
                {{-- Language Select --}}
                <x-form.specialized.language-select
                    name="language_id"
                    label="Preferred Language" />
                
                {{-- Currency Select --}}
                <x-form.specialized.currency-select
                    name="currency_id"
                    label="Currency" />
                
                {{-- Timezone Select --}}
                <x-form.specialized.timezone-select
                    name="timezone_id"
                    label="Timezone" />
                
                {{-- Date Format Select --}}
                <x-form.specialized.date-format-select
                    name="date_format"
                    label="Date Format" />
                
                {{-- Time Format Select --}}
                <x-form.specialized.time-format-select
                    name="time_format"
                    label="Time Format" />
            </div>
        </div>
        
        {{-- Textarea Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Textarea</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Basic Textarea --}}
                <x-form.textarea
                    name="description"
                    label="Description"
                    placeholder="Enter description..."
                    rows="4" />
                
                {{-- Auto-resize Textarea with Character Count --}}
                <x-form.textarea
                    name="bio"
                    label="Bio"
                    placeholder="Tell us about yourself..."
                    maxlength="500"
                    autoResize
                    hint="Brief description about yourself" />
            </div>
        </div>
        
        {{-- Checkboxes & Radio Buttons Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Checkboxes & Radio Buttons</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Single Checkbox --}}
                <div>
                    <x-form.checkbox
                        name="terms"
                        label="I agree to the terms and conditions"
                        required />
                    
                    <x-form.checkbox
                        name="newsletter"
                        label="Subscribe to newsletter"
                        class="mt-4" />
                </div>
                
                {{-- Radio Group --}}
                <x-form.radio-group
                    name="gender"
                    label="Gender"
                    :options="[
                        ['id' => 'male', 'name' => 'Male'],
                        ['id' => 'female', 'name' => 'Female'],
                        ['id' => 'other', 'name' => 'Other']
                    ]"
                    inline />
            </div>
        </div>
        
        {{-- Phone Input Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Phone Input</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.specialized.phone-input
                    name="phone"
                    label="Phone Number"
                    placeholder="Enter phone number"
                    required />
                
                <x-form.specialized.phone-input
                    name="mobile"
                    label="Mobile Number"
                    value="+1234567890" />
            </div>
        </div>
        
        {{-- Different Sizes Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Component Sizes</h2>
            
            <div class="space-y-4">
                <x-form.input 
                    type="text"
                    name="small_input"
                    label="Small Input"
                    placeholder="Small size"
                    size="sm" />
                
                <x-form.input 
                    type="text"
                    name="medium_input"
                    label="Medium Input (Default)"
                    placeholder="Medium size"
                    size="md" />
                
                <x-form.input 
                    type="text"
                    name="large_input"
                    label="Large Input"
                    placeholder="Large size"
                    size="lg" />
            </div>
        </div>
        
        {{-- Form Actions --}}
        <div class="flex justify-end space-x-4">
            <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection 