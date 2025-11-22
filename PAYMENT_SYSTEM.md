# Payment & Voucher System Documentation

## Overview

The ERP system now includes a comprehensive dual-voucher payment tracking system that generates professional receipts for both sales and payment collections.

## Voucher Types

### 1. Sale Voucher (V-YYYYMMDD-NNNN)
- **Generated:** Automatically when a sale is created
- **Format:** `V-20251122-0001`
- **Purpose:** Original sale invoice/receipt
- **Includes:**
  - Company branding from VoucherTemplate
  - Customer details (name, phone)
  - Product information (name, SKU, quantity, price)
  - Payment summary (total, paid, due)
  - Expected clear date (if applicable)
  - Dual signature sections
  - Bengali + English bilingual text

### 2. Payment Voucher (PV-YYYYMMDD-NNNN)
- **Generated:** When recording a due payment
- **Format:** `PV-20251122-0001`
- **Purpose:** Payment receipt for partial/full due collection
- **Includes:**
  - "PAID" watermark
  - Payment receipt details
  - Original sale reference (voucher number, date, amount)
  - Payment breakdown (previous paid + today's payment + remaining due)
  - Professional green gradient design
  - Amount in words (Bengali)
  - Both receiver and payer signature sections

## Payment Flow

### Step 1: Create Sale
1. Salesman creates a sale
2. Customer may pay full amount or partial amount
3. System generates **Sale Voucher** (V-YYYYMMDD-NNNN)
4. If paid amount < total amount:
   - Due amount calculated automatically
   - Expected clear date can be set
   - Payment status = 'partial' or 'unpaid'

### Step 2: Record Payment (Owner/Manager)
1. Navigate to Due Customers page
2. Search for customer (by phone, name, or voucher)
3. Click "টাকা নিন" (Take Money) button
4. Enter payment amount:
   - Validation: Cannot exceed current due amount
   - Quick buttons available (৳1,000, ৳5,000, ৳10,000, Full Due)
5. Submit payment

### Step 3: Automatic Processing
When payment is recorded, the system:
1. **Generates Payment Voucher Number:**
   - Format: `PV-YYYYMMDD-NNNN`
   - Auto-increments daily counter
   
2. **Updates Sale Record:**
   - `paid_amount` += payment amount
   - `due_amount` recalculated automatically
   - `payment_status` updated (partial → paid if fully paid)
   - `actual_clear_date` set if fully paid

3. **Creates ProfitRealization Record:**
   ```php
   payment_amount: ৳10,000
   payment_voucher_number: PV-20251122-0002
   profit_amount: Calculated proportionally
   payment_date: Current timestamp
   recorded_by: Auth user ID
   ```

4. **Calculates Proportional Profit:**
   ```
   If original sale = ৳50,000 with ৳5,000 profit
   Payment of ৳10,000 realizes:
   Profit = (10,000 / 50,000) × 5,000 = ৳1,000
   ```

5. **Redirects to Payment Voucher:**
   - Professional PDF-ready page
   - Shows all payment details
   - Both vouchers accessible (sale + payment)

## Voucher Access

### From Due Customers Page
```
Customer Row:
├── Voucher Column
│   ├── V-20251122-0001 (blue link) → Original Sale Voucher
│   └── PV-20251122-0001 (green link) → First Payment Voucher
│   └── PV-20251122-0002 (green link) → Second Payment Voucher
│   └── ... (all payment vouchers)
```

### All vouchers are:
- Clickable links
- Open in new tab
- Print-optimized
- Saved permanently in database
- Include shop branding

## Professional Features

### Sale Voucher Design
- **Header:** Company name, address, phone from VoucherTemplate
- **Layout:** Professional two-column grid
- **Colors:** Blue theme for professional look
- **Sections:**
  - Customer Information
  - Product Details (table format)
  - Payment Summary (colored boxes)
  - Signature lines
  - Footer with company message

### Payment Voucher Design
- **Header:** Same company branding
- **Title:** Green "PAYMENT RECEIPT" banner
- **Watermark:** "PAID" text in background
- **Sections:**
  - Payment Information
  - Large green box with payment amount
  - Amount in Bengali words
  - Original sale reference box (blue)
  - Balance summary (yellow/amber boxes)
  - Remaining due alert (red if any, green if fully paid)
  - Dual signatures
  - Bilingual footer

### Print-Ready Features
- **A4 Page Format:** 210mm × 297mm
- **Proper Margins:** 15mm all around
- **Page Break Control:** Single page layout
- **Print CSS:** Hides buttons, optimizes for printing
- **Font:** Bengali-friendly Noto Sans Bengali
- **Professional Typography:** Clear hierarchy
- **Color-coded:** Visual status indicators

## Database Schema

### profit_realizations Table
```sql
id                        BIGINT PRIMARY KEY
sale_id                   BIGINT (FK to sales)
payment_date              DATE
payment_amount            DECIMAL(15,2)
payment_voucher_number    VARCHAR (indexed) ← NEW
profit_amount             DECIMAL(15,2)
recorded_by               BIGINT (FK to users)
notes                     TEXT
created_at                TIMESTAMP
updated_at                TIMESTAMP
```

## Routes

### Owner Routes
```php
GET  /owner/due-customers                    → List due customers
GET  /owner/payment/{sale}/record            → Payment form
POST /owner/payment/{sale}/store             → Process payment
GET  /owner/payment-voucher/{profitRealization} → Payment voucher
GET  /owner/voucher/{sale}/print             → Sale voucher
```

### Manager Routes (Same structure)
```php
GET  /manager/due-customers
GET  /manager/payment/{sale}/record
POST /manager/payment/{sale}/store
GET  /manager/payment-voucher/{profitRealization}
GET  /manager/voucher/{sale}/print
```

## Controller Methods

### OwnerController::storePayment()
```php
1. Validate payment amount
2. Generate payment voucher number (PV-YYYYMMDD-NNNN)
3. Update sale.paid_amount
4. Calculate proportional profit
5. Create ProfitRealization record with voucher number
6. Redirect to payment voucher page
```

### OwnerController::paymentVoucher()
```php
1. Load ProfitRealization with sale, product, user
2. Find owner to get VoucherTemplate
3. Return voucher view with all data
```

## Usage Examples

### Example 1: Full Payment
```
Original Sale: ৳50,000 (Due: ৳50,000)
Payment: ৳50,000
Result:
- Sale fully paid
- Payment status → 'paid'
- Due amount → ৳0
- Actual clear date → today
- Payment voucher shows "FULLY PAID" ✓
```

### Example 2: Partial Payment
```
Original Sale: ৳50,000 (Due: ৳30,000)
Payment: ৳10,000
Result:
- Sale still partial
- Payment status → 'partial'
- Due amount → ৳20,000
- Payment voucher shows remaining ৳20,000 in red
```

### Example 3: Multiple Payments
```
Sale: ৳100,000 total

Payment 1 (Day 1): ৳40,000
- Voucher: PV-20251120-0001
- Due: ৳60,000

Payment 2 (Day 2): ৳30,000
- Voucher: PV-20251121-0001
- Due: ৳30,000

Payment 3 (Day 3): ৳30,000
- Voucher: PV-20251122-0001
- Due: ৳0 (FULLY PAID)

All 3 payment vouchers listed under sale
```

## Benefits

### For Business
1. **Professional Image:** Branded vouchers with shop details
2. **Complete Audit Trail:** Every payment has a receipt
3. **Clear Records:** Both parties have proof
4. **Legal Compliance:** Proper documentation
5. **Customer Trust:** Professional receipts build confidence

### For Users
1. **Easy Access:** Click any voucher number to print
2. **Clear Information:** All details at a glance
3. **Payment History:** See all payments for a sale
4. **Print Ready:** Professional PDF format
5. **Bilingual:** Bengali + English support

### For System
1. **Automatic Numbering:** No manual tracking needed
2. **Data Integrity:** All vouchers saved in database
3. **Profit Tracking:** Accurate realization calculations
4. **Search Enabled:** Find by voucher number
5. **Scalable:** Handles unlimited payments per sale

## Future Enhancements

Potential improvements:
- [ ] Email vouchers to customers
- [ ] SMS voucher links
- [ ] Barcode/QR code on vouchers
- [ ] Bulk payment processing
- [ ] Payment reminders based on expected clear date
- [ ] Export vouchers to PDF file
- [ ] Voucher templates per product category
- [ ] Digital signatures
- [ ] Payment gateway integration

## Technical Notes

### Voucher Numbering Logic
```php
// Daily counter resets
$date = now()->format('Ymd');  // 20251122

// Find last voucher today
$lastVoucher = ProfitRealization::whereDate('created_at', today())
    ->whereNotNull('payment_voucher_number')
    ->orderBy('id', 'desc')
    ->first();

// Increment counter
if ($lastVoucher) {
    $lastNumber = (int) substr($lastVoucher->payment_voucher_number, -4);
    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
} else {
    $newNumber = '0001';
}

$voucherNumber = 'PV-' . $date . '-' . $newNumber;
// Result: PV-20251122-0001
```

### CSS Print Optimization
```css
@media print {
    body { margin: 0; }
    .no-print { display: none; }
    @page { 
        size: A4; 
        margin: 15mm; 
    }
}
```

### Amount in Words (Bengali)
```php
$formatter = new NumberFormatter('bn', NumberFormatter::SPELLOUT);
$words = ucfirst($formatter->format($amount));
// 10000 → "দশ হাজার"
```

## Conclusion

The dual-voucher system provides professional, trackable payment management with automatic receipt generation. Every transaction is documented with branded, printable vouchers that maintain complete audit trails while presenting a professional image to customers.
