# ISRMS Database Schema & Migrations

## Overview

Complete database structure with 11 tables for the Integrated Store & Requisition Management System.

**Database Name:** `store_management`  
**Created:** April 13, 2026  
**Migration Status:** Ready for Laravel deployment

---

## Database Tables

### 1. **Users Table**

| Field             | Type         | Constraints                                       |
| ----------------- | ------------ | ------------------------------------------------- |
| id                | SERIAL       | PRIMARY KEY                                       |
| name              | VARCHAR(150) | NOT NULL                                          |
| email             | VARCHAR(150) | UNIQUE, NOT NULL                                  |
| password          | VARCHAR(255) | NOT NULL                                          |
| role              | ENUM         | admin, storekeeper, auditor, principal, requester |
| department        | VARCHAR(100) | NULLABLE                                          |
| email_verified_at | TIMESTAMP    | NULLABLE                                          |
| is_active         | BOOLEAN      | DEFAULT true                                      |
| login_attempts    | INT          | DEFAULT 0                                         |
| last_login_at     | TIMESTAMP    | NULLABLE                                          |
| remember_token    | VARCHAR(100) | NULLABLE                                          |
| created_at        | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP                         |
| updated_at        | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP                         |

**Indexes:** email, role, is_active, department

---

### 2. **Items Table**

| Field      | Type         | Constraints               |
| ---------- | ------------ | ------------------------- |
| id         | SERIAL       | PRIMARY KEY               |
| name       | VARCHAR(150) | NOT NULL                  |
| category   | VARCHAR(100) | NULLABLE                  |
| unit       | VARCHAR(50)  | NULLABLE                  |
| min_stock  | INT          | DEFAULT 0                 |
| max_stock  | INT          | DEFAULT 0                 |
| created_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |

**Indexes:** category, name  
**Purpose:** Master list of all inventory items

---

### 3. **SRA Table** (Stores Received Advice)

| Field              | Type        | Constraints               |
| ------------------ | ----------- | ------------------------- |
| id                 | SERIAL      | PRIMARY KEY               |
| sra_number         | VARCHAR(50) | UNIQUE, NULLABLE          |
| supplier_details   | TEXT        | NULLABLE                  |
| created_by         | INT         | FK → users(id)            |
| status             | ENUM        | pending, approved         |
| signed_storekeeper | BOOLEAN     | DEFAULT false             |
| signed_auditor     | BOOLEAN     | DEFAULT false             |
| signed_principal   | BOOLEAN     | DEFAULT false             |
| created_at         | TIMESTAMP   | DEFAULT CURRENT_TIMESTAMP |
| updated_at         | TIMESTAMP   | DEFAULT CURRENT_TIMESTAMP |

**Indexes:** status, sra_number  
**Purpose:** Track received goods from suppliers

---

### 4. **SRA Items Table**

| Field    | Type   | Constraints                    |
| -------- | ------ | ------------------------------ |
| id       | SERIAL | PRIMARY KEY                    |
| sra_id   | INT    | FK → sra(id) ON DELETE CASCADE |
| item_id  | INT    | FK → items(id)                 |
| quantity | INT    | NOT NULL, CHECK > 0            |

**Indexes:** sra_id, item_id (unique together)  
**Purpose:** Line items in each SRA

---

### 5. **Requisitions Table**

| Field        | Type      | Constraints                 |
| ------------ | --------- | --------------------------- |
| id           | SERIAL    | PRIMARY KEY                 |
| requested_by | INT       | FK → users(id)              |
| approved_by  | INT       | FK → users(id), NULLABLE    |
| status       | ENUM      | pending, approved, rejected |
| created_at   | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP   |
| updated_at   | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP   |

**Indexes:** status, requested_by, approved_by  
**Purpose:** Track item requisition requests

---

### 6. **Requisition Items Table**

| Field              | Type   | Constraints                             |
| ------------------ | ------ | --------------------------------------- |
| id                 | SERIAL | PRIMARY KEY                             |
| requisition_id     | INT    | FK → requisitions(id) ON DELETE CASCADE |
| item_id            | INT    | FK → items(id)                          |
| quantity_requested | INT    | NOT NULL, CHECK > 0                     |

**Indexes:** requisition_id, item_id (unique together)  
**Purpose:** Line items in each requisition

---

### 7. **Issues Table**

| Field          | Type      | Constraints                     |
| -------------- | --------- | ------------------------------- |
| id             | SERIAL    | PRIMARY KEY                     |
| requisition_id | INT       | FK → requisitions(id), NULLABLE |
| issued_by      | INT       | FK → users(id)                  |
| received_by    | INT       | FK → users(id), NULLABLE        |
| created_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP       |
| updated_at     | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP       |

**Indexes:** requisition_id, issued_by, received_by  
**Purpose:** Track item issuance from inventory

---

### 8. **Issue Items Table**

| Field           | Type   | Constraints                       |
| --------------- | ------ | --------------------------------- |
| id              | SERIAL | PRIMARY KEY                       |
| issue_id        | INT    | FK → issues(id) ON DELETE CASCADE |
| item_id         | INT    | FK → items(id)                    |
| quantity_issued | INT    | NOT NULL, CHECK > 0               |

**Indexes:** issue_id, item_id (unique together)  
**Purpose:** Line items in each issue

---

### 9. **Inventory Ledger Table**

| Field            | Type      | Constraints               |
| ---------------- | --------- | ------------------------- |
| id               | SERIAL    | PRIMARY KEY               |
| item_id          | INT       | FK → items(id)            |
| transaction_type | ENUM      | RECEIVE, ISSUE            |
| quantity         | INT       | NOT NULL                  |
| balance_after    | INT       | NOT NULL                  |
| reference_type   | ENUM      | SRA, ISSUE                |
| reference_id     | INT       | NULLABLE                  |
| created_at       | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

**Indexes:** item_id, transaction_type, reference_type, created_at  
**Purpose:** Complete audit trail of all transactions

---

### 10. **Notifications Table**

| Field      | Type      | Constraints                      |
| ---------- | --------- | -------------------------------- |
| id         | SERIAL    | PRIMARY KEY                      |
| user_id    | INT       | FK → users(id) ON DELETE CASCADE |
| message    | TEXT      | NOT NULL                         |
| is_read    | BOOLEAN   | DEFAULT false                    |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP        |

**Indexes:** user_id, is_read, created_at  
**Purpose:** System notifications for users

---

### 11. **Audit Logs Table** (🔐 CRITICAL)

| Field      | Type         | Constraints               |
| ---------- | ------------ | ------------------------- |
| id         | SERIAL       | PRIMARY KEY               |
| user_id    | INT          | FK → users(id), NULLABLE  |
| action     | VARCHAR(255) | NOT NULL                  |
| table_name | VARCHAR(100) | NOT NULL                  |
| record_id  | INT          | NULLABLE                  |
| created_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP |

**Indexes:** user_id, action, table_name, created_at, (table_name, record_id)  
**Purpose:** Complete audit trail for compliance

---

## Relationships

```
Users
├── has_many: Sra (created_by)
├── has_many: Requisition (requested_by)
├── has_many: Requisition (approved_by)
├── has_many: Issue (issued_by)
├── has_many: Issue (received_by)
├── has_many: Notification
└── has_many: AuditLog

Items
├── has_many: SRA_Items
├── has_many: Requisition_Items
├── has_many: Issue_Items
└── has_many: Inventory_Ledger

SRA
├── belongs_to: User (created_by)
├── has_many: SRA_Items
└── has_many: Items (through SRA_Items)

Requisition
├── belongs_to: User (requested_by)
├── belongs_to: User (approved_by)
├── has_many: Requisition_Items
├── has_many: Items (through Requisition_Items)
└── has_many: Issue

Issue
├── belongs_to: Requisition
├── belongs_to: User (issued_by)
├── belongs_to: User (received_by)
├── has_many: Issue_Items
└── has_many: Items (through Issue_Items)
```

---

## Key Models

### User Model

```php
// Authentication & Relationships
$user->hasRole($role)
$user->hasAnyRole(['role1', 'role2'])
$user->isActive()
$user->getRoleDisplayName()
$user->srasCreated()
$user->requisitionsRequested()
$user->requisitionsApproved()
$user->issuesIssued()
$user->issuesReceived()
$user->notifications()
$user->auditLogs()
```

### Item Model

```php
// Stock Management
$item->getCurrentStock()
$item->isLowStock()
$item->isOverStock()
$item->sraItems()
$item->requisitionItems()
$item->issueItems()
$item->ledgerEntries()
```

### Sra Model

```php
// Receiving Management
$sra->createdBy()
$sra->sraItems()
$sra->items()
$sra->isFullySigned()
$sra->getApprovalStatus()
```

### Requisition Model

```php
// Request Management
$requisition->requester()
$requisition->approver()
$requisition->requisitionItems()
$requisition->items()
$requisition->issues()
$requisition->isApproved()
$requisition->getTotalItems()
```

### Issue Model

```php
// Issuance Management
$issue->requisition()
$issue->issuedBy()
$issue->receivedBy()
$issue->issueItems()
$issue->items()
$issue->getTotalIssued()
```

### InventoryLedger Model

```php
// Transaction Tracking
$entry->item()
$entry->getTransactionTypeDisplay()
$entry->getReferenceDisplay()
$entry->isReceive()
$entry->isIssue()
```

---

## Database Setup Instructions

### 1. **Configure Database Connection**

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=store_management
DB_USERNAME=root
DB_PASSWORD=
```

Or for PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=store_management
DB_USERNAME=postgres
DB_PASSWORD=
```

### 2. **Run Migrations**

```bash
# Create all tables
php artisan migrate

# Fresh database (destructive - drops all tables)
php artisan migrate:fresh

# Seed database with sample data
php artisan migrate --seed
```

### 3. **Verify Tables Created**

```bash
# List all tables
php artisan tinker
>>> DB::select('SHOW TABLES;')

# Check table structure
>>> DB::select('DESCRIBE users;')
```

---

## Eloquent Query Examples

### Create Item

```php
$item = Item::create([
    'name' => 'Laptop',
    'category' => 'Electronics',
    'unit' => 'Piece',
    'min_stock' => 5,
    'max_stock' => 20,
]);
```

### Create SRA with Items

```php
$sra = Sra::create([
    'sra_number' => 'SRA-2026-001',
    'supplier_details' => 'Supplier ABC Inc.',
    'created_by' => 1,
]);

$sra->sraItems()->create([
    'item_id' => 1,
    'quantity' => 10,
]);
```

### Create Requisition

```php
$req = Requisition::create([
    'requested_by' => 2,
    'status' => 'pending',
]);

$req->requisitionItems()->create([
    'item_id' => 1,
    'quantity_requested' => 5,
]);
```

### Create Issue

```php
$issue = Issue::create([
    'requisition_id' => 1,
    'issued_by' => 1,
    'received_by' => 2,
]);

$issue->issueItems()->create([
    'item_id' => 1,
    'quantity_issued' => 5,
]);
```

### Record Ledger Entry

```php
InventoryLedger::create([
    'item_id' => 1,
    'transaction_type' => 'RECEIVE',
    'quantity' => 10,
    'balance_after' => 15,
    'reference_type' => 'SRA',
    'reference_id' => 1,
]);
```

### Query Reports

```php
// Get all low stock items
$lowStock = Item::where('min_stock', '>', 0)
    ->get()
    ->filter(fn($item) => $item->isLowStock());

// Get pending requisitions
$pending = Requisition::where('status', 'pending')->get();

// Get audit trail for user
$logs = AuditLog::where('user_id', 1)
    ->orderByDesc('created_at')
    ->paginate(50);

// Get inventory balance for item
$balance = InventoryLedger::where('item_id', 1)
    ->latest()
    ->first()
    ->balance_after;
```

---

## Migration Files Created

```
database/migrations/
├── 2014_10_12_000000_create_users_table.php       [UPDATED]
├── 2014_10_12_100000_create_password_resets_table.php
├── 2026_04_13_000001_create_items_table.php       [NEW]
├── 2026_04_13_000002_create_sra_table.php         [NEW]
├── 2026_04_13_000003_create_sra_items_table.php   [NEW]
├── 2026_04_13_000004_create_requisitions_table.php [NEW]
├── 2026_04_13_000005_create_requisition_items_table.php [NEW]
├── 2026_04_13_000006_create_issues_table.php      [NEW]
├── 2026_04_13_000007_create_issue_items_table.php [NEW]
├── 2026_04_13_000008_create_inventory_ledger_table.php [NEW]
├── 2026_04_13_000009_create_notifications_table.php [NEW]
└── 2026_04_13_000010_create_audit_logs_table.php  [NEW]
```

---

## Model Files Created

```
app/Models/
├── User.php                    [UPDATED]
├── Item.php                    [NEW]
├── Sra.php                     [NEW]
├── SraItem.php                 [NEW]
├── Requisition.php             [NEW]
├── RequisitionItem.php         [NEW]
├── Issue.php                   [NEW]
├── IssueItem.php               [NEW]
├── InventoryLedger.php         [NEW]
├── Notification.php            [NEW]
└── AuditLog.php                [NEW]
```

---

## Performance Optimization

### Indexes Created

- **Users:** email, role, is_active, department
- **Items:** category, name
- **SRA:** status, sra_number
- **SRA_Items:** sra_id, item_id (unique)
- **Requisitions:** status, requested_by, approved_by
- **Requisition_Items:** requisition_id, item_id (unique)
- **Issues:** requisition_id, issued_by, received_by
- **Issue_Items:** issue_id, item_id (unique)
- **Inventory_Ledger:** item_id, transaction_type, reference_type, created_at
- **Notifications:** user_id, is_read, created_at
- **Audit_Logs:** user_id, action, table_name, created_at, (table_name, record_id)

### Query Optimization Tips

```php
// Use eager loading to avoid N+1
$items = Item::with('sraItems.sra', 'requisitionItems.requisition')->get();

// Use select() to limit columns
$users = User::select('id', 'name', 'email', 'role')->get();

// Use where() to filter before pagination
$requisitions = Requisition::where('status', 'pending')->paginate(20);
```

---

## Data Integrity Rules

1. **Foreign Key Constraints**
    - Users cannot be deleted if they have SRAs, requisitions, or issues
    - Items cannot be deleted if they're in ledger entries
    - Cascade deletes for junction tables (SRA_Items, Requisition_Items, Issue_Items)

2. **Data Validation**
    - Quantities must be > 0
    - Status fields use ENUM for consistency
    - Role fields limited to 5 options

3. **Audit Trail**
    - All critical operations logged in audit_logs
    - User activity tracked via last_login_at
    - Notifications system for important events

---

## Backup & Recovery

### Backup

```bash
# MySQL backup
mysqldump -u root store_management > backup.sql

# PostgreSQL backup
pg_dump store_management > backup.sql
```

### Restore

```bash
# MySQL restore
mysql -u root store_management < backup.sql

# PostgreSQL restore
psql store_management < backup.sql
```

---

## Testing Database Queries

### Laravel Tinker

```bash
php artisan tinker

>>> $item = Item::first();
>>> $item->sraItems()->count();
>>> Requisition::where('status', 'pending')->count();
>>> AuditLog::latest()->limit(10)->get();
```

---

**Status:** ✅ Ready for Production  
**Last Updated:** April 13, 2026  
**Version:** 1.0.0
