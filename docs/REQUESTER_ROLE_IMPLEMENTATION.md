# Requester Role - Real-Time Implementation Guide

## Overview

The Requester role has been fully implemented with real-time data across all pages. Requesters can create and manage requisitions with live inventory data, and their dashboard provides real-time status updates on all their requisitions.

---

## Key Features

### 1. **Real-Time Dashboard** (`/dashboard/requester`)

**Features:**

- Live requisition statistics (4 counts):
    - Pending Approval
    - Approved
    - Issued
    - Rejected
- Auto-updating requisitions table (refreshes every 10 seconds)
- Last update timestamp display
- Smooth animations on stat changes

**Real-Time Data:**

- Fetches from `GET /api/requisitions/dashboard/data`
- Updates every 10 seconds automatically
- Shows most recent 10 requisitions

**API Endpoint:**

```
GET /api/requisitions/dashboard/data
Authorization: role:requester
Response: {
  "stats": {
    "pending_requisitions": 5,
    "approved_requisitions": 12,
    "issued_requisitions": 8,
    "rejected_requisitions": 2
  },
  "recent_requisitions": [...]
}
```

### 2. **Create Requisition Page** (`/requisitions/create`)

**Features:**

- Real-time item inventory display
- Live stock status badges (Green/Yellow/Red)
- Item search with instant filtering
- Auto-populating dropdowns
- Dynamic item addition/removal
- Stock updates every 5 seconds
- Alerts for quantity validation

**Real-Time Features:**

- Stock levels update automatically
- Visual feedback on stock changes
- Quantity validation against current stock
- Search filters items in real-time

**API Endpoints:**

```
GET /api/requisitions/items/list
- Returns all items with current stock
- Fields: id, name, code, category, unit, current_stock, status, last_updated

GET /api/items/{item_id}/stock
- Returns single item stock data
- Used for backward compatibility
```

### 3. **Requisitions Index** (`/requisitions`)

**Features:**

- Filter by status (All, Pending, Approved, Rejected, Issued)
- Search by requisition number
- Status badges with approval indicators
- Quick issue action for approved items
- Pagination support

**Requester Specific:**

- Shows only own requisitions
- Can edit pending requisitions
- Can delete pending requisitions
- Cannot edit approved/rejected items

---

## API Endpoints Summary

### Dashboard Data

```
GET /api/requisitions/dashboard/data
- Role: requester
- Returns: stats + recent requisitions
- Update Interval: 10 seconds
- Use: Dashboard real-time updates
```

### My Requisitions List

```
GET /api/requisitions/my-list?status=pending|approved|rejected
- Role: requester
- Returns: Array of requisitions
- Update Interval: On-demand
- Use: Requisitions table updates
```

### All Items with Stock

```
GET /api/requisitions/items/list
- Role: requester, principal, storekeeper
- Returns: Array of items with stock data
- Update Interval: 5 seconds (in create form)
- Use: Requisition creation form
```

### Single Item Stock

```
GET /api/items/{item_id}/stock
- Role: requester
- Returns: Single item with stock data
- Update Interval: On select change
- Use: Backward compatibility
```

---

## Real-Time Features

### Dashboard Updates

```javascript
// Refreshes every 10 seconds
setInterval(() => {
    loadDashboardData(); // Fetches API data
    updateStatsCards(); // Updates UI
    updateRequisitionsTable(); // Refreshes table
    updateLastUpdateTime(); // Updates timestamp
}, 10000);
```

### Stock Updates (Create Form)

```javascript
// Refreshes every 5 seconds
setInterval(() => {
    fetchAllItems(); // Get latest stock
    updateAllStockDisplays(); // Update badges
    showNotifications(); // Alert user if changed
}, 5000);
```

### Features:

- Automatic polling every 5-10 seconds
- Only updates when data changes
- Smooth animations on updates
- Auto-dismissing notifications
- Stops on page unload

---

## User Workflow

### 1. Creating a Requisition

```
1. Requester navigates to /requisitions/create
2. Frontend loads all items with stock data (API call)
3. Requester selects item → Stock badge displays with status
4. Requester types quantity → Form validates
5. Stock updates every 5 seconds automatically
6. Requester adds more items if needed
7. Form submitted → Requisition created
8. Redirected to requisition detail page
```

### 2. Checking Dashboard

```
1. Requester goes to /dashboard/requester
2. Dashboard fetches stats and recent requisitions
3. Stats cards show with animations
4. Table displays recent requisitions
5. Auto-refreshes every 10 seconds
6. Shows last update time
```

### 3. Tracking Requisition Status

```
1. Requisition created → Status: Pending
2. Principal approves → Status: Approved (live update)
3. Storekeeper issues items → Status updates in table
4. Items received → Status shown in dashboard
```

---

## Database Queries

### Getting Requester Stats

```php
// Pending
Requisition::where('requested_by', $user->id)
  ->where('status', 'pending')
  ->count();

// Approved
Requisition::where('requested_by', $user->id)
  ->where('status', 'approved')
  ->count();

// Issued
Requisition::where('requested_by', $user->id)
  ->whereHas('issues')
  ->count();

// Rejected
Requisition::where('requested_by', $user->id)
  ->where('status', 'rejected')
  ->count();
```

### Getting Stock Data

```php
// Get latest stock for item
$lastLedger = InventoryLedger::where('item_id', $itemId)
  ->latest()
  ->first();

$currentStock = $lastLedger ? $lastLedger->balance_after : 0;
```

---

## Forms & Validation

### Requisition Create Form

```
department: readonly (from user profile)
requested_by: readonly (current user)
request_date: required date
purpose: required string
items[].item_id: required exists:items,id
items[].quantity_requested: required integer min:1
```

### Frontend Validation

- At least one item required
- Quantity must be > 0
- Cannot exceed available stock (warning)
- Real-time validation on change

---

## Events & Broadcasting

### RequisitionStatusChanged Event

```php
// Triggered when:
- Requisition approved
- Requisition rejected
- Requisition marked as issued

// Broadcasts on channels:
- 'requisition-updates'
- 'user-{requested_by_id}'

// Payload:
{
  'requisition_id': 1,
  'requisition_number': 'REQ-001',
  'previous_status': 'pending',
  'new_status': 'approved',
  'requested_by': 5,
  'timestamp': '2026-04-29T...'
}
```

---

## Security & Authorization

### Authorization Middleware

- `role:requester` - Only requester role
- `role:requester,principal,storekeeper` - Multiple roles
- Policy-based: `isRequester()` - Own requisitions only

### Data Access

- Requesters see only their own requisitions
- API filters by logged-in user
- No cross-requester data visible

### CSRF Protection

- All POST requests protected with @csrf
- AJAX requests include X-Requested-With header

---

## Permissions by Status

| Action  | Pending | Approved | Rejected | Issued |
| ------- | ------- | -------- | -------- | ------ |
| View    | ✅      | ✅       | ✅       | ✅     |
| Edit    | ✅      | ❌       | ❌       | ❌     |
| Delete  | ✅      | ❌       | ❌       | ❌     |
| Approve | ❌      | ❌       | ❌       | ❌     |
| Reject  | ❌      | ❌       | ❌       | ❌     |

_Note: Approve/Reject only for Principal role_

---

## Performance Optimization

### Database Queries

- Uses eager loading with `.with()`
- Limits results to prevent large datasets
- Indexes on `requested_by` and `status`

### Frontend Optimization

- Only updates changed values
- Stops polling on page unload
- Debounces search input
- Uses event delegation for dynamic rows

### API Response

- Minimal data transfer (~5KB per call)
- JSON response format
- 5-10 second update intervals

---

## Troubleshooting

### Dashboard Not Updating

1. Check browser console for errors
2. Verify `/api/requisitions/dashboard/data` accessible
3. Check user role is `requester`
4. Clear browser cache

### Stock Not Updating in Create Form

1. Verify `/api/requisitions/items/list` accessible
2. Check browser network tab for API calls
3. Verify items exist in database
4. Check InventoryLedger table has entries

### Form Submission Failing

1. Check all required fields filled
2. Verify at least one item selected with qty > 0
3. Check console for validation errors
4. Verify CSRF token present

### Slow Performance

1. Check number of items in select (< 1000 recommended)
2. Monitor API response time
3. Check database query performance
4. Consider pagination for large result sets

---

## Future Enhancements

### Recommended

1. **WebSocket Integration** - Replace polling with real-time updates
2. **Push Notifications** - Alert when approval status changes
3. **Email Notifications** - Approval/rejection emails
4. **Requisition Templates** - Save and reuse common requests
5. **Bulk Operations** - Add multiple items at once

### Advanced

1. **Analytics Dashboard** - Track requisition trends
2. **Budget Tracking** - Monitor spending per department
3. **Item Recommendations** - Suggest items based on history
4. **Approval Workflows** - Custom approval chains
5. **Mobile App** - Native mobile interface

---

## Files Modified/Created

### Controllers

- `app/Http/Controllers/RequisitionController.php` - Added 4 API methods
- `app/Http/Controllers/DashboardController.php` - Updated requester()

### Views

- `resources/views/dashboard/requester.blade.php` - Real-time updates
- `resources/views/requisitions/create.blade.php` - Live stock data

### Events

- `app/Events/RequisitionStatusChanged.php` - Status change broadcasts

### Routes

- `routes/web.php` - Added 4 new API endpoints

---

## API Testing

### cURL Examples

```bash
# Get dashboard data
curl -H "Authorization: Bearer {token}" \
  http://127.0.0.1:8000/api/requisitions/dashboard/data

# Get all items
curl -H "Authorization: Bearer {token}" \
  http://127.0.0.1:8000/api/requisitions/items/list

# Get single item stock
curl -H "Authorization: Bearer {token}" \
  http://127.0.0.1:8000/api/items/5/stock

# Get my requisitions
curl -H "Authorization: Bearer {token}" \
  "http://127.0.0.1:8000/api/requisitions/my-list?status=pending"
```

---

## Development Notes

### Testing Real-Time Updates

1. Open dashboard in one window
2. Manually change inventory in database
3. Wait 10 seconds to see dashboard update
4. Watch for notifications

### Debugging

- Check browser console for JavaScript errors
- Monitor Network tab in DevTools for API calls
- Check Laravel logs in `storage/logs/`
- Use `dd()` for debugging API responses

---

## Related Documentation

- [Real-Time Issues Implementation](REALTIME_ISSUES_IMPLEMENTATION.md)
- [Database Schema](DATABASE_SCHEMA.md)
- [Authentication Guide](AUTHENTICATION_GUIDE.md)
- [Setup Checklist](SETUP_CHECKLIST.md)

---

## Quick Reference

| Item                           | Value          |
| ------------------------------ | -------------- |
| Dashboard Update Interval      | 10 seconds     |
| Stock Update Interval (Create) | 5 seconds      |
| Default Items Per Page         | 20             |
| Recent Requisitions Shown      | 10             |
| Max Search Results             | 50             |
| Stock Status Levels            | 3 (in/low/out) |
