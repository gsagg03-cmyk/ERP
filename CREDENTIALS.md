# ERP System - Login Credentials

The server is running at: http://localhost:8000

## Demo Login Credentials (Phone-Based Authentication)

Use these credentials to login:

### Super Admin
- **Phone:** 1234567890
- **Password:** password
- **Access:** Can manage all owners, view all reports

### Owner (রহিম সাহেব)
- **Phone:** 01711111111
- **Password:** password
- **Access:** Can manage managers, products, stock, expenses, and reports
- **Features:** Due system enabled, voucher printing, payment collection

### Manager (করিম ম্যানেজার)
- **Phone:** 01722222222
- **Password:** password
- **Access:** Can manage salesmen, products, stock, sales, and reports
- **Features:** Payment collection, voucher printing (stock value hidden)

### Salesman 1 (আলী সেলসম্যান)
- **Phone:** 01733333333
- **Password:** password
- **Access:** Can create sales and view own sales

### Salesman 2 (হাসান সেলসম্যান)
- **Phone:** 01744444444
- **Password:** password
- **Access:** Can create sales and view own sales

---

## Features

✅ Phone number-based authentication (10-15 digits)
✅ 4-level user hierarchy (SuperAdmin → Owner → Manager → Salesman)
✅ Role-based access control with Spatie Permission
✅ Product management with SKU codes
✅ Stock management with purchase price tracking
✅ Sales tracking with automatic profit calculation
✅ Dual profit tracking (মোট লাভ + নগদ লাভ)
✅ Customer due management with payment tracking
✅ Printable vouchers with customizable shop details
✅ Expense management for owners
✅ Comprehensive reports with date filtering
✅ Bengali language interface (বাংলা)
✅ Fully responsive mobile-first design

## Demo Data Included

- **5 Products:** Samsung Galaxy A54, iPhone 14 Pro, Xiaomi Redmi Note 12, AirPods Pro, JBL Speaker
- **7 Sales:** Mix of cash and credit sales across 3 days (Nov 20-22)
- **3 Due Customers:** Total ৳83,000 in outstanding payments
- **Voucher Template:** রহিম ট্রেডার্স shop details for PDF printing

## Notes

- Phone numbers must be 10-15 digits (numbers only)
- All passwords are: **password**
- The system uses SQLite database for easy setup
- Data is automatically seeded on first migration
- Due system can be enabled/disabled per owner by SuperAdmin
- Each sale generates a unique voucher number (V-YYYYMMDD-NNNN format)
