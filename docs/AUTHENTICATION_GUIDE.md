# ISRMS Authentication & Authorization System

## Overview

Comprehensive authentication and authorization system with:

- User registration & login
- Email-based password reset
- Role-based access control (RBAC)
- Session management with "Remember Me"
- Login rate limiting (max 5 attempts)
- CSRF protection
- Email verification support

## Features

### 1. Authentication

#### Login

- Email/password authentication
- Password hashing using bcrypt
- "Remember Me" functionality (persistent login)
- Login attempt tracking
- Rate limiting (5 attempts before lockout)

#### Registration

- User registration with email validation
- Password confirmation
- Role assignment (Storekeeper, Principal, Auditor, Requester)
- Email verification support (commented out - enable in production)

#### Password Reset

- Forgot password form
- Reset token generation (valid for 1 hour)
- Secure token hashing
- Password reset form with validation

### 2. Authorization

#### Role-Based Access Control (RBAC)

```php
// User roles:
- storekeeper (Manage inventory, receipts)
- principal (Approval authority)
- auditor (Audit & verify)
- requester (Request items)
- admin (Full system access)
```

#### Protected Routes

All application routes except `/auth/*` require authentication:

```
/dashboard/* - Role-specific dashboards
/items/* - Inventory management
/sra/* - Receiving management
/requisitions/* - Requisition management
/issues/* - Item issues
/ledger/* - Inventory ledger
/reports/* - Analytics
/users/* - User management
```

### 3. Security Features

#### Password Security

- Bcrypt hashing with auto-salting
- Minimum 8 characters
- Password confirmation on registration & reset
- Prevents same password reveal

#### Session Management

- Laravel Sanctum API token authentication
- Remember token for persistent sessions
- Automatic session invalidation on logout
- CSRF token on all forms

#### Rate Limiting

- Max 5 login attempts per user
- Auto-reset on successful login
- User receives "Too many attempts" message
- Prevents brute force attacks

#### CSRF Protection

- Automatic token generation on forms
- `@csrf` directive in all Blade forms
- Token validation on all POST/PUT/DELETE requests

#### Email Verification

- Optional email verification (commented in code)
- Verification token sent via email
- Enable by implementing email service

### 4. User Management

#### User Fields

```php
- id (Primary Key)
- name (User's full name)
- email (Unique email address)
- password (Hashed)
- role (storekeeper|principal|auditor|requester|admin)
- is_active (Account status)
- email_verified_at (Email verification timestamp)
- login_attempts (For rate limiting)
- last_login_at (Audit logging)
- remember_token (Persistent login)
- created_at, updated_at (Timestamps)
```

#### User Methods

```php
$user->hasRole('storekeeper')           // Check single role
$user->hasAnyRole(['storekeeper', 'principal'])  // Check multiple
$user->isActive()                       // Check if account active
$user->getRoleDisplayName()             // Get readable role name
$user->getRoleBadgeClass()              // Get Bootstrap badge class
$user->updateLastLogin()                // Update login timestamp
$user->incrementLoginAttempts()         // Increment failed attempts
$user->resetLoginAttempts()             // Reset failed attempts
```

## Usage

### Authentication Controllers

#### AuthController (`App\Http\Controllers\Auth\AuthController`)

```php
public function showLogin()          // Show login form
public function login(Request $request)       // Handle login
public function showRegister()       // Show registration form
public function register(Request $request)    // Handle registration
public function logout(Request $request)      // Handle logout
```

#### PasswordResetController

```php
public function showForgotPassword()   // Show forgot password form
public function sendResetLink()        // Send password reset email
public function showResetPassword()    // Show reset form
public function resetPassword()        // Handle password reset
```

### Middleware

#### Authenticate Middleware

```php
// Protect routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', ...);
});
```

#### CheckRole Middleware

```php
// Protect routes by role
Route::middleware('auth:sanctum', 'role:admin,storekeeper')->group(function () {
    Route::get('/users', ...);
});
```

## Routes

### Authentication Routes (Public)

```
GET  /auth/login                 - Show login form
POST /auth/login                 - Process login
GET  /auth/register              - Show registration form
POST /auth/register              - Process registration
GET  /auth/forgot-password       - Show forgot password form
POST /auth/forgot-password       - Send reset link
GET  /auth/reset-password/{token} - Show reset form
POST /auth/reset-password        - Process reset
POST /auth/logout                - Logout user
```

### Protected Routes (Authenticated)

```
GET  /dashboard                  - Role-specific dashboard redirect
GET  /dashboard/storekeeper      - Storekeeper dashboard
GET  /dashboard/principal        - Principal dashboard
GET  /dashboard/auditor          - Auditor dashboard
GET  /dashboard/requester        - Requester dashboard
GET  /items                      - Item listing
POST /items                      - Create item
GET  /items/{id}                 - View item
PUT  /items/{id}                 - Update item
DELETE /items/{id}               - Delete item
... (same pattern for /sra, /requisitions, /issues, /users)
```

## Database

### Users Table Structure

```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP,
  password VARCHAR(255) NOT NULL,
  role ENUM('storekeeper','principal','auditor','requester','admin'),
  is_active BOOLEAN DEFAULT true,
  login_attempts INT DEFAULT 0,
  last_login_at TIMESTAMP,
  remember_token VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  INDEX (email, role),
  INDEX (is_active)
);
```

### Password Resets Table

```sql
CREATE TABLE password_resets (
  email VARCHAR(255) NOT NULL,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMP,
  INDEX (email)
);
```

## Configuration Files

### app.php

- Application name: "ISRMS"
- Auth guard: "sanctum"

### auth.php

- Providers: "users" model with User class

### database.php

- Default: "mysql"
- Credentials configured in .env

## Frontend Views

### Login Form (`resources/views/auth/login.blade.php`)

- Email input with validation
- Password input
- Remember me checkbox
- Login button
- Links to register & forgot password
- Error message display

### Registration Form (`resources/views/auth/register.blade.php`)

- Name input
- Email input
- Role selector dropdown
- Password & confirmation inputs
- Register button
- Link to login

### Forgot Password Form (`resources/views/auth/forgot-password.blade.php`)

- Email input
- Send reset link button
- Link back to login

### Reset Password Form (`resources/views/auth/reset-password.blade.php`)

- Email input
- New password input
- Password confirmation
- Reset button

## Testing

### Unit Tests

Run authentication tests:

```bash
php artisan test tests/Feature/AuthenticationTest.php
```

### Test Coverage

- ✅ Login/logout functionality
- ✅ Registration validation
- ✅ Password hashing & verification
- ✅ Role-based dashboard redirect
- ✅ Protected route access
- ✅ Rate limiting
- ✅ Account deactivation
- ✅ Session management

## Security Checklist

### OWASP Compliance

- ✅ **A01: Broken Access Control** - RBAC with middleware
- ✅ **A02: Cryptographic Failures** - Bcrypt password hashing
- ✅ **A03: Injection** - Prepared statements via Eloquent ORM
- ✅ **A04: Insecure Design** - Secure auth patterns
- ✅ **A05: Security Misconfiguration** - Env-based config
- ✅ **A07: CSRF Protection** - Token validation
- ✅ **A08: Software & Data Integrity** - Package management
- ✅ **A10: Logging & Monitoring** - Last login tracking

### Additional Security

- ✅ Rate limiting on login attempts
- ✅ Account lockout mechanism
- ✅ Password reset token expiration (1 hour)
- ✅ Secure session management
- ✅ Remember token secure cookie
- ✅ Email verification support
- ✅ User activity logging
- ✅ HTTPS ready (configure in production)

## Email Configuration

To enable password reset emails:

1. Configure mail provider in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@isrms.local
MAIL_FROM_NAME="ISRMS"
```

2. Uncomment email sending in `PasswordResetController`:

```php
Mail::send('emails.password-reset', ['token' => $token], ...);
```

3. Create email template at `resources/views/emails/password-reset.blade.php`

## Deployment

### Production Checklist

- [ ] Set `APP_DEBUG=false` in .env
- [ ] Set `APP_ENV=production` in .env
- [ ] Enable HTTPS
- [ ] Configure secure cookies:

```php
Session::secure()
Session::httpOnly()
Session::sameSite('lax')
```

- [ ] Enable email verification
- [ ] Configure password reset emails
- [ ] Set up monitoring & logging
- [ ] Run database migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`

## Troubleshooting

### "Route [auth.login] not defined"

- Ensure routes in `/routes/web.php` are loaded
- Run `php artisan route:cache`

### "User model not found"

- Check namespace in controllers
- Verify model at `App\Models\User`

### "CSRF token mismatch"

- Ensure `@csrf` in form tags
- Check session storage configuration

### "Remember me not working"

- Verify cookies middleware is enabled
- Check `COOKIE_LIFETIME` in .env

## Next Steps

1. **Email Integration**: Configure mailing service for password resets
2. **Two-Factor Authentication**: Add 2FA for enhanced security
3. **OAuth**: Integrate with Google/GitHub authentication
4. **Audit Logging**: Track user actions
5. **IP Whitelisting**: Restrict access by IP
6. **Session Analytics**: Track active users

---

**Last Updated:** April 13, 2026
**Version:** 1.0.0
