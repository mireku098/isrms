# Role-Based Access Control (RBAC) System Documentation

## 1. Overview
The ISRMS uses a comprehensive RBAC system to control access to dashboard components, navigation menus, and specific application features. This ensures that users can only access information and perform actions relevant to their assigned roles.

## 2. Role-Permission Matrix

| Feature / Module | Admin | Storekeeper | Auditor | Principal | Requester |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Inventory Management (CRUD Items)** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **View SRAs** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Create/Edit SRAs** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Sign SRA (Storekeeper)** | ✅ | ✅ | ❌ | ❌ | ❌ | (By Creation) |
| **Sign SRA (Auditor)** | ✅ | ❌ | ✅ | ❌ | ❌ | |
| **Sign SRA (Principal)** | ✅ | ❌ | ❌ | ✅ | ❌ | |
| **View Requisitions** | ✅ | ✅ | ❌ | ✅ | ✅ |
| **Create/Edit Requisitions** | ✅ | ❌ | ❌ | ❌ | ✅ |
| **Approve/Reject Requisitions** | ✅ | ❌ | ❌ | ✅ | ❌ |
| **Issuing (Issue Items)** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Receive Issue (Acknowledgment)** | ✅ | ❌ | ❌ | ❌ | ✅ |
| **Inventory Ledger** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Reports** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **User Management** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Audit Logs** | ✅ | ❌ | ❌ | ❌ | ❌ |

## 3. Implementation Details

### Server-Side Authorization
Access is protected at two levels:
1. **Middleware**: Routes are protected using the `role` middleware in `routes/web.php`.
   - Example: `Route::middleware(['role:admin'])->group(...)`
2. **Controller-level Authorization**: Controllers use Laravel's `$this->authorize()` method to perform fine-grained checks.
   - Example: `$this->authorize('isAdmin')`

### Client-Side Visibility
UI elements are dynamically shown or hidden using Blade directives:
- `@can('permissionName') ... @endcan`
- `@canany(['permission1', 'permission2']) ... @endcanany`

### Unauthorized Access Handling
- Direct URL access attempts by unauthorized users will trigger a 403 Access Denied response.
- A custom, user-friendly error page is located at `resources/views/errors/403.blade.php`.

## 4. Adding New Roles or Modifying Permissions
To modify the RBAC system:
1. **Update `AuthServiceProvider`**: Add or modify gates in `app/Providers/AuthServiceProvider.php`.
2. **Update `RoleMiddleware`**: Ensure the logic in `app/Http/Middleware/RoleMiddleware.php` accommodates the changes.
3. **Update Routes**: Modify `routes/web.php` middleware parameters.
4. **Update Sidebar**: Adjust visibility in `resources/views/partials/sidebar.blade.php`.
