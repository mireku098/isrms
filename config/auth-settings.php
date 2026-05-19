<?php

/**
 * ISRMS Authentication Configuration
 * Rate Limiting & Security Settings
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Login Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Maximum login attempts before user is temporarily locked out
    |
    */
    'max_login_attempts' => 5,
    'lockout_duration_minutes' => 15,

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    |
    | Configuration for password reset tokens
    |
    */
    'password_reset_token_lifetime_minutes' => 60,
    'password_min_length' => 8,

    /*
    |--------------------------------------------------------------------------
    | Remember Me
    |--------------------------------------------------------------------------
    |
    | Remember token lifetime in days
    |
    */
    'remember_token_lifetime_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    |
    | Session timeout configuration
    |
    */
    'session_lifetime_minutes' => 120,
    'session_cookie_name' => 'ISRMS_SESSION',

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection
    |--------------------------------------------------------------------------
    | 
    | CSRF token configuration
    |
    */
    'csrf_token_name' => '_token',
    'csrf_header_name' => 'X-CSRF-TOKEN',

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    |
    | Enable/Disable email verification requirement
    |
    */
    'require_email_verification' => false,
    'email_verification_timeout_minutes' => 60,

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Enable/Disable 2FA (Future feature)
    |
    */
    'enable_two_factor_auth' => false,

    /*
    |--------------------------------------------------------------------------
    | IP Whitelisting
    |--------------------------------------------------------------------------
    |
    | Restrict login to specific IPs (Optional)
    |
    */
    'enable_ip_whitelisting' => false,
    'whitelisted_ips' => [],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Security headers for HTTPS
    |
    */
    'security_headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles & Permissions
    |--------------------------------------------------------------------------
    |
    | System roles configuration
    |
    */
    'roles' => [
        'storekeeper' => 'Manage inventory and receipts',
        'principal' => 'Approve requisitions and SRAs',
        'auditor' => 'Verify and audit transactions',
        'requester' => 'Request items',
        'admin' => 'Full system administration',
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Restrictions
    |--------------------------------------------------------------------------
    |
    | Automatic account deactivation settings
    |
    */
    'auto_deactivate_inactive_days' => 90,
    'max_password_age_days' => 180,
    'enforce_complex_passwords' => true,

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Track important authentication events
    |
    */
    'enable_audit_logging' => true,
    'log_failed_logins' => true,
    'log_successful_logins' => true,
    'log_password_changes' => true,
    'log_role_changes' => true,
];
