# Inventory & Stock Requisition Management System (ISRMS)

## Complete User Guide & Tutorial

---

## Table of Contents

1. [System Overview](#system-overview)
2. [User Roles Guide](#user-roles-guide)
3. [Role-by-Role Breakdown](#role-by-role-breakdown)
4. [Common Workflows](#common-workflows)
5. [Tips & Best Practices](#tips--best-practices)

---

## System Overview

The ISRMS is designed to manage inventory stock and process Stock Requisition Agreements (SRAs) through a structured workflow with different user roles. Each role has specific responsibilities and permissions based on operational needs.

**System Pillars:**

- **Inventory Management**: CRUD operations on stock items
- **Requisition Process**: Create, edit, and manage requisitions
- **SRA Processing**: Create, edit, and approve Stock Requisition Agreements
- **Audit & Compliance**: Maintain logs and generate reports

---

## User Roles Guide

| Role            | Primary Function     | Key Responsibility                              |
| --------------- | -------------------- | ----------------------------------------------- |
| **Admin**       | System Administrator | Full system access, user management, audit logs |
| **Storekeeper** | Inventory Custodian  | Manage stock, create/edit SRAs, issue items     |
| **Auditor**     | Compliance Officer   | Review and sign SRAs, view reports              |
| **Principal**   | Approval Authority   | Approve requisitions, sign SRAs                 |
| **Requester**   | Item Requestor       | Create requisitions, acknowledge received items |

---

## Role-by-Role Breakdown

### 🔐 ADMIN

**Access Level:** Full System Access

#### ✅ What You Can Do

- **Inventory Management**: Create, read, update, delete all inventory items
- **SRA Operations**: Create, edit, view, and sign Stock Requisition Agreements
- **Requisition Management**: View, create, edit, and approve/reject requisitions
- **Issuing**: Issue items from stock to requesters
- **Ledger & Reports**: Access complete inventory ledger and all reports
- **User Management**: Create, modify, and deactivate user accounts
- **Audit Logs**: View complete system audit trail

#### ❌ Limitations

- None (full administrative access)

#### 📋 Typical Workflow

1. Set up users and assign roles
2. Monitor all system activities via audit logs
3. Create/edit inventory items as needed
4. Oversee all requisition and SRA processes
5. Generate reports for management review

---

### 📦 STOREKEEPER

**Access Level:** Operational - Inventory & SRA Management

#### ✅ What You Can Do

- **Inventory Management**: Create, read, update, delete inventory items
- **View SRAs**: See all Stock Requisition Agreements
- **Create/Edit SRAs**: Create new SRAs and modify existing ones
- **Sign SRAs**: Add your storekeeper signature to SRAs
- **View Requisitions**: View all requisition requests
- **Issuing**: Release items from inventory to fulfill requisitions
- **Ledger & Reports**: Access inventory ledger and operational reports

#### ❌ Limitations

- **Cannot** approve/reject requisitions (Principal only)
- **Cannot** create or edit requisitions (Requester only)
- **Cannot** approve SRAs as Auditor or Principal
- **Cannot** manage user accounts
- **Cannot** view audit logs
- **Cannot** receive/acknowledge issued items (Requester only)

#### 📋 Typical Workflow

1. Monitor inventory levels via ledger
2. Review incoming requisitions from requesters
3. Create SRA when requisition is approved by Principal
4. Edit SRA details if needed before finalization
5. Sign SRA to confirm storekeeper's involvement
6. Issue requested items from stock
7. Update inventory records
8. Generate operational reports

#### 💡 Key Responsibility

You are the guardian of inventory. Ensure accurate stock levels and proper SRA documentation before items leave the store.

---

### 📋 AUDITOR

**Access Level:** Review & Compliance

#### ✅ What You Can Do

- **View SRAs**: Review all Stock Requisition Agreements
- **Sign SRA (Auditor)**: Add auditor signature/approval to SRAs
- **View Requisitions**: See all requisition records
- **Inventory Ledger**: Access ledger for compliance verification
- **Reports**: Generate and view audit reports

#### ❌ Limitations

- **Cannot** create or edit SRAs (Storekeeper only)
- **Cannot** create or edit requisitions (Requester only)
- **Cannot** approve/reject requisitions (Principal only)
- **Cannot** issue items or manage inventory
- **Cannot** sign SRAs as Storekeeper or Principal
- **Cannot** manage users or view full audit logs
- **Cannot** perform any inventory CRUD operations

#### 📋 Typical Workflow

1. Review submitted SRAs for completeness and compliance
2. Verify storekeeper signatures and details
3. Check requisition history and supporting documents
4. Add your auditor signature upon approval
5. Generate compliance reports
6. Flag any discrepancies for investigation

#### 💡 Key Responsibility

Ensure all SRAs and requisitions comply with organizational policies and best practices. Your signature validates the integrity of the process.

---

### 👔 PRINCIPAL

**Access Level:** Decision Maker - Approvals

#### ✅ What You Can Do

- **View SRAs**: See all Stock Requisition Agreements
- **Sign SRA (Principal)**: Add principal signature to finalize SRAs
- **View Requisitions**: Review all requisition requests
- **Approve/Reject Requisitions**: Make approval decisions on requests
- **Inventory Ledger**: Access ledger for oversight
- **Reports**: View reports for management decisions

#### ❌ Limitations

- **Cannot** create or edit requisitions (Requester only)
- **Cannot** create or edit SRAs (Storekeeper only)
- **Cannot** create or edit inventory items
- **Cannot** issue items or acknowledge receipt
- **Cannot** sign SRAs as Storekeeper or Auditor
- **Cannot** manage users or view audit logs
- **Cannot** approve items as Requester

#### 📋 Typical Workflow

1. Review requisition requests from requesters
2. Verify items needed and budget alignment
3. Approve or reject requisitions with comments
4. Review SRAs before final signature
5. Sign SRAs to authorize the agreement
6. Monitor inventory through reports
7. Provide management oversight

#### 💡 Key Responsibility

As the approval authority, your decisions ensure organizational resources are used appropriately. Carefully review requests before approval.

---

### 👤 REQUESTER

**Access Level:** Limited - Create & Receive

#### ✅ What You Can Do

- **View Requisitions**: See your own and all visible requisitions
- **Create/Edit Requisitions**: Submit and modify your own requisition requests
- **View SRAs**: View Stock Requisition Agreements (read-only)
- **Receive Issue (Acknowledgment)**: Confirm receipt of issued items

#### ❌ Limitations

- **Cannot** approve or reject requisitions
- **Cannot** create or edit SRAs
- **Cannot** manage inventory items
- **Cannot** issue items
- **Cannot** view audit logs
- **Cannot** access ledger or reports
- **Cannot** manage user accounts
- **Cannot** sign SRAs

#### 📋 Typical Workflow

1. Submit requisition request with required items and quantities
2. Edit requisition if needed before approval
3. Wait for Principal to review and approve
4. Monitor status of your requisition
5. Receive notification when items are issued
6. Acknowledge receipt of items
7. Confirm all items received as requested

#### 💡 Key Responsibility

Provide clear and accurate requisition requests. Promptly acknowledge receipt when items are delivered. Communicate any discrepancies immediately.

---

## Common Workflows

### 📑 Standard SRA Creation Workflow

```
Requester Creates Requisition
        ↓
   Principal Reviews & Approves
        ↓
   Storekeeper Creates SRA
        ↓
  Storekeeper Signs SRA
        ↓
   Auditor Reviews & Signs SRA
        ↓
   Principal Signs SRA (Finalizes)
        ↓
   Storekeeper Issues Items
        ↓
   Requester Acknowledges Receipt
```

### 📊 Inventory Update Workflow

```
Storekeeper Identifies Stock Need
        ↓
  Storekeeper Updates/Creates Item
        ↓
  Inventory Ledger Reflects Changes
        ↓
Admin Reviews (if needed)
```

### ✅ Requisition Approval Workflow

```
Requester Submits Request
        ↓
  Principal Reviews Details
        ↓
   Principal Approves/Rejects
        ↓
  Storekeeper Processes (if approved)
        ↓
   Items Issued to Requester
        ↓
  Requester Acknowledges Receipt
```

---

## Tips & Best Practices

### For Storekeepers 📦

- **Keep records accurate**: Double-check inventory quantities after each transaction
- **Be thorough with SRAs**: Include all relevant details before sending to auditor
- **Communicate delays**: If you cannot fulfill a requisition, notify the requester early
- **Regular audits**: Periodically reconcile physical stock with system records

### For Auditors 📋

- **Review systematically**: Check dates, signatures, and quantities for consistency
- **Flag early**: Identify issues before SRAs are finalized
- **Document concerns**: Keep notes on any discrepancies found
- **Timely responses**: Process SRAs promptly to avoid workflow delays

### For Principals 👔

- **Set clear criteria**: Communicate approval guidelines to requesters
- **Review thoroughly**: Examine requisitions for reasonableness and compliance
- **Provide feedback**: When rejecting, explain why for process improvement
- **Monitor trends**: Use reports to identify patterns and optimize processes

### For Requesters 👤

- **Be specific**: Clearly describe items needed with exact quantities
- **Plan ahead**: Submit requisitions with sufficient lead time
- **Update promptly**: Acknowledge receipt as soon as items arrive
- **Communicate issues**: Report any discrepancies immediately

### For Admins 🔐

- **Regular audits**: Review audit logs for security and compliance
- **User access review**: Periodically verify users have correct roles
- **System maintenance**: Keep user information and roles current
- **Documentation**: Maintain clear records of system changes

---

## System Limitations & Access Restrictions

### Access Control Strategy

The system uses **Role-Based Access Control (RBAC)** to ensure:

- ✅ Users only see what they need for their role
- ✅ Critical operations require appropriate authorization
- ✅ Audit trails track all system activities
- ✅ Separation of duties prevents conflicts of interest

### Unauthorized Access

If you attempt to access a feature outside your role:

- You will see a **403 Access Denied** error
- Your action will be logged in system audit trails
- Contact your Admin if you believe you need additional access

### What Gets Logged

- Every login and logout
- All create, read, update, delete operations
- User role changes
- Access denial attempts
- Report generation

---

## Getting Help

**Issue with Access or Permissions?**

- Contact your System Administrator with your username and desired action

**Questions About Your Role?**

- Refer to this guide for your specific role section
- Contact your department manager or supervisor

**Technical Issues?**

- Report to your IT Support team with:
    - Your username
    - What you were trying to do
    - What error message you received

---

**Last Updated:** 2026-05-05  
**System:** Inventory & Stock Requisition Management System (ISRMS)
