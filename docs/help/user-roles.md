# User Roles & Permissions

Simple Invoices uses role-based access control to manage what each user can do.

## Available Roles

| Role | Description | Typical User |
|------|-------------|-------------|
| **Administrator** | Full system access — all modules, all settings, all data | System owner, IT admin |
| **Domain Administrator** | Manages users and domains; limited settings access | Department head, manager |
| **User** | Standard access to create/edit invoices, payments, customers | Staff accountant, bookkeeper |
| **Customer** | Portal-only access — view own invoices, make payments | Your clients |
| **Biller** | Access to their own invoices and customers only | External billing entity |

## Role Permissions

### Administrator
- **Full access** to all modules and settings
- Can create, edit, and delete any record
- Can manage users, domains, and system configuration
- Can perform database backups and patches
- Can configure payment gateways and email settings

### Domain Administrator
- Can manage users within their domain
- Can create and edit invoices, payments, customers
- Can create new domains and assign users
- **Limited** access to system-wide settings

### User
- Create, edit, and view invoices
- Record and view payments
- Manage customers and products
- View reports
- **No access** to settings, user management, or configuration

### Customer (Portal)
- View their own invoices
- Download invoice PDFs
- Make online payments
- View payment history
- **No access** to any other customer's data

### Biller
- Access their own invoices
- View customers assigned to them
- Limited to their billing entity

## Managing Users

Go to **Settings → Manage Users** (Administrator) or **Domain Admin → Users** (Domain Administrator):

1. Click **Add User**
2. Set username, email, and password
3. Assign a role
4. Optionally restrict to specific domains
5. Save

## Required Fields

Fields marked with a <span style="color:red">&#42;</span> (red asterisk) are required and must be filled in before the form can be saved.
