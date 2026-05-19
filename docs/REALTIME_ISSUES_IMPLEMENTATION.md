# Real-Time Issues Create Page - Implementation Guide

## Overview

The Issues create page (`/issues/create`) has been updated to use **real-time data** for displaying requisitions, items, and current inventory levels. This ensures that the storekeeper always sees the most up-to-date inventory information when issuing items.

---

## Key Features

### 1. **Dynamic Requisition Loading**

- When a requisition is selected from the dropdown, data is loaded automatically via API
- Displays real-time requisition details:
    - Requested by (requester name)
    - Department
    - Approval date
    - Approved by

### 2. **Real-Time Inventory Data**

- Current stock levels are fetched dynamically for each item
- Stock status badges display:
    - **In Stock** (green) - Stock >= reorder level
    - **Low Stock** (yellow) - Stock between 0 and reorder level
    - **Out of Stock** (red) - Stock = 0
- Stock is fetched from the latest inventory ledger entry

### 3. **Live Inventory Updates**

- Inventory stock levels update **every 5 seconds** automatically
- Updates are displayed with notifications
- Stock quantity changes are logged:
    - "Stock updated for [Item]: [New] units available (was [Old])"
    - "Stock replenished for [Item]: [New] units now available"

### 4. **Validation & Alerts**

- Real-time validation of quantity inputs
- Alerts for:
    - Quantity exceeding requested amount
    - Quantity exceeding available stock
    - Required fields missing

---

## Architecture

### API Endpoints

Three new endpoints provide real-time data:

#### 1. **GET `/api/requisitions/{requisition}/details`**

Returns requisition metadata

**Response:**

```json
{
    "id": 1,
    "requisition_number": "REQ-2026-001",
    "department": "ICT Department",
    "requested_by": "John Doe",
    "approval_date": "Apr 09, 2026",
    "approval_by": "Manager Name",
    "status": "approved",
    "priority": "Normal"
}
```

#### 2. **GET `/api/requisitions/{requisition}/items`**

Returns all items in a requisition with current inventory levels

**Response:**

```json
[
    {
        "id": 1,
        "item_id": 5,
        "item_name": "Office Chairs",
        "item_code": "ITEM-001",
        "requested_quantity": 10,
        "current_stock": 45,
        "unit": "Pieces",
        "stock_status": "in_stock",
        "reorder_level": 20,
        "can_issue": true
    },
    {
        "id": 2,
        "item_id": 6,
        "item_name": "A4 Paper",
        "item_code": "ITEM-002",
        "requested_quantity": 5,
        "current_stock": 2,
        "unit": "Reams",
        "stock_status": "low_stock",
        "reorder_level": 10,
        "can_issue": true
    }
]
```

#### 3. **GET `/api/items/{item}/inventory`**

Returns real-time inventory data for a single item

**Response:**

```json
{
    "item_id": 5,
    "item_name": "Office Chairs",
    "item_code": "ITEM-001",
    "current_stock": 45,
    "reorder_level": 20,
    "unit": "Pieces",
    "status": "in_stock",
    "last_updated": "5 minutes ago"
}
```

### Frontend JavaScript Features

#### Automatic Data Loading

```javascript
// Load data when requisition is selected
select.addEventListener("change", function () {
    if (this.value) {
        loadRequisitionData(this.value);
    }
});
```

#### Real-Time Updates

```javascript
// Update inventory every 5 seconds
setInterval(async function () {
    if (!currentRequisitionId) return;

    const response = await fetch(
        `/api/requisitions/${currentRequisitionId}/items`,
    );
    const items = await response.json();

    // Update badges and notify user
    items.forEach((item) => {
        updateStockBadge(item);
    });
}, 5000);
```

#### Stock Status Indicators

- **Color-coded badges** show stock status at a glance
- **Insufficient stock warning** when stock < requested qty
- **Highlighted rows** show items with inventory concerns

### Backend Changes

#### Files Modified:

1. **IssueController.php**
    - `create()` - Enhanced to return formatted requisition data
    - `store()` - Updated to handle new form structure
    - `getRequisitionDetails()` - New API method
    - `getRequisitionItems()` - New API method
    - `getItemInventory()` - New API method

2. **Routes (web.php)**
    - Added 3 new API endpoints under issues middleware

#### Files Created:

1. **Events/InventoryUpdated.php** - Broadcastable event for inventory changes
2. **Events/RequisitionDataUpdated.php** - Broadcastable event for requisition updates

---

## How It Works

### Step-by-Step Process

1. **User Selects Requisition**
    - Form dropdown triggers change event
    - JavaScript sends fetch request to `/api/requisitions/{id}/details`

2. **Data is Fetched & Displayed**
    - Requisition details populate dynamically
    - Items table is populated with live inventory data
    - All containers become visible

3. **Continuous Monitoring**
    - Every 5 seconds, the system polls `/api/requisitions/{id}/items`
    - Stock quantities are compared to previous values
    - If stock changed, badge updates + notification shown

4. **User Issues Items**
    - Form validates quantity inputs
    - Ensures:
        - At least one item has quantity > 0
        - Quantity doesn't exceed requested amount
        - Quantity doesn't exceed available stock
    - Submits to `/issues/store` with all item data

5. **Backend Processing**
    - Creates Issue record
    - Creates IssueItem records for each item
    - Creates InventoryLedger entries
    - Logs audit trail
    - Redirects to issue show page

---

## Configuration

### Refresh Interval

Default: **5 seconds**

To change, modify in `create.blade.php`:

```javascript
}, 5000); // Change this value (milliseconds)
```

### Broadcast Driver

The system uses Laravel's broadcasting. Current configuration:

- Default: `'null'` (no broadcasting configured)
- Can be configured in `.env`: `BROADCAST_DRIVER=pusher|redis|null`

### For Production with WebSockets:

Set up Pusher or Laravel WebSockets:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

---

## UI Improvements

### Stock Status Badges

- **Green** - Full stock available
- **Yellow/Orange** - Low stock warning
- **Red** - Out of stock

### Alert Messages

- **Success (Green)** - Auto-dismisses after 5 seconds
- **Info (Blue)** - Auto-dismisses after 5 seconds
- **Warning (Orange)** - Stays until dismissed
- **Danger (Red)** - Stays until dismissed

### Loading Indicators

- Spinner shows while data is loading
- Form sections only visible after data loads
- Submit button disabled until requisition selected

---

## Security

### Authorization

- All endpoints require `role:storekeeper` middleware
- Authorization check: `$this->authorize('isStorekeeper')`

### Data Validation

- Item IDs verified against database
- Quantities validated (min:1)
- Requisition status verified (approved only)

### XSS Prevention

- HTML escaping on all user-facing data
- Safe JSON responses

### CSRF Protection

- Form includes `@csrf` token
- All POST requests protected

---

## Error Handling

### API Error Responses

```javascript
if (!response.ok) {
    throw new Error("Failed to load requisition details");
}
```

### User-Facing Error Messages

- Displayed in dismissible alert box
- Technical errors simplified for user
- Helpful guidance provided

---

## Performance Considerations

### Polling Strategy

- 5-second interval balances responsiveness with server load
- Only polls when requisition selected
- Stops polling on page unload

### Data Optimization

- Only necessary fields returned from API
- Uses database relationships to minimize queries
- Pagination not needed (typically < 100 items per requisition)

### Frontend Optimization

- DOM updates only when data changes
- No redundant re-renders
- Event listeners cleaned up on page unload

---

## Testing

### Manual Testing Checklist

- [ ] Requisition dropdown populates correctly
- [ ] Selecting requisition loads details
- [ ] Items table shows with correct data
- [ ] Stock badges display correct status
- [ ] Stock values update every 5 seconds
- [ ] Quantity validation works
- [ ] Form submission succeeds
- [ ] Issue created with correct items
- [ ] Inventory ledger entries created
- [ ] Audit log recorded

### Testing Real-Time Updates

1. Open issues/create page
2. Select a requisition
3. Manually update inventory in database
4. Wait 5 seconds to see stock update in form
5. Verify notification appears

---

## Future Enhancements

### Recommended Next Steps

1. **WebSocket Integration**
    - Replace polling with WebSockets (Laravel WebSockets or Pusher)
    - Real-time updates without delay
    - Better for multiple concurrent users

2. **User Notifications**
    - Toast notifications for stock updates
    - In-app notification bell icon
    - Email alerts for low stock

3. **Historical Tracking**
    - Show stock trend over time
    - Predict stock-outs
    - Seasonal patterns

4. **Advanced Filtering**
    - Filter items by category
    - Search by item name/code
    - Sort by stock status

5. **Batch Operations**
    - Issue multiple requisitions at once
    - Batch edit quantities
    - Print picking slips

---

## Troubleshooting

### Stock Not Updating

- Check interval value (default 5 seconds)
- Verify API endpoints accessible
- Check browser console for errors

### Form Not Loading

- Verify requisition exists and is approved
- Check user role (must be storekeeper)
- Ensure database connection active

### Missing Items

- Verify requisition has items
- Check RequisitionItem records in database
- Ensure items are linked to requisition

### Slow Performance

- Check database query performance
- Monitor API response times
- Consider adding indexes to tables

---

## Reference Links

- **Controller**: [IssueController.php](app/Http/Controllers/IssueController.php)
- **View**: [create.blade.php](resources/views/issues/create.blade.php)
- **Events**: [Events/](app/Events/)
- **Routes**: [routes/web.php](routes/web.php)

---

## Support

For issues or questions about the real-time implementation:

1. Check browser console for JavaScript errors
2. Check Laravel logs in `storage/logs/`
3. Verify API endpoints are accessible
4. Test with postman: `GET /api/requisitions/1/items`
