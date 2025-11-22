<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ভাউচার - {{ $sale->voucher_number }}</title>
    <style>
        @media print {
            body { 
                margin: 0;
                padding: 0;
            }
            .no-print { display: none; }
            @page { 
                size: A4;
                margin: 8mm;
            }
            .voucher {
                box-shadow: none;
                border: 2px solid #000;
                page-break-inside: avoid;
                min-height: auto;
                padding: 8mm;
            }
            .signature-section {
                margin-top: 25px;
            }
            .signature-line {
                margin-top: 35px;
            }
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Noto Sans Bengali', 'Kalpurush', Arial, sans-serif;
            background: #f5f5f5;
            padding: 15px;
            line-height: 1.6;
        }
        .voucher {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 2px solid #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .company-details {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.8;
        }
        .header-text {
            font-size: 13px;
            font-style: italic;
            color: #6b7280;
            margin-top: 8px;
            font-weight: 500;
        }
        .voucher-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 8px;
            margin: 12px 0;
            border-radius: 6px;
            letter-spacing: 1px;
        }
        .voucher-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 8px;
            background: #f9fafb;
            border-left: 4px solid #3b82f6;
        }
        .voucher-number {
            font-size: 16px;
            font-weight: bold;
            color: #dc2626;
        }
        .voucher-date {
            font-size: 14px;
            color: #4b5563;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 6px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 10px;
            background: #f9fafb;
            border-radius: 6px;
        }
        .info-item {
            display: flex;
            gap: 8px;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .table th {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #2563eb;
            font-weight: 600;
            font-size: 13px;
        }
        .table td {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            background: white;
            font-size: 13px;
        }
        .table tbody tr:hover {
            background: #f9fafb;
        }
        .total-section {
            margin-top: 15px;
            padding: 12px;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border: 2px solid #3b82f6;
            border-radius: 6px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px dashed #d1d5db;
        }
        .total-row:last-child {
            border-bottom: none;
        }
        .total-row.grand {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            border-top: 3px double #000;
            padding-top: 10px;
            margin-top: 8px;
            border-bottom: none;
        }
        .total-row.due {
            color: #dc2626;
        }
        .signature-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 25px;
            padding-top: 10px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin-top: 35px;
            padding-top: 8px;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .footer-highlight {
            margin-bottom: 6px;
            font-size: 12px;
            color: #1f2937;
            font-weight: 600;
        }
        .print-button {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 14px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
            transition: all 0.3s;
        }
        .print-button:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            box-shadow: 0 6px 8px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .status-due {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <div style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">
            <a href="{{ route(auth()->user()->getRoleNames()->first() . '.dashboard') }}" 
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3); transition: all 0.3s; display: inline-block;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 8px rgba(16, 185, 129, 0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(16, 185, 129, 0.3)'">
                🏠 ড্যাশবোর্ডে ফিরুন / Back to Dashboard
            </a>
            <button class="print-button" onclick="window.print()">🖨️ প্রিন্ট করুন / Print</button>
        </div>
    </div>

    <div class="voucher">
        <!-- Header -->
        <div class="header">
            @if($template)
                <div class="company-name">{{ $template->company_name }}</div>
                <div class="company-details">
                    @if($template->company_address)
                        <div>📍 {{ $template->company_address }}</div>
                    @endif
                    @if($template->company_phone)
                        <div>📞 {{ $template->company_phone }}</div>
                    @endif
                </div>
                @if($template->header_text)
                    <div class="header-text">{{ $template->header_text }}</div>
                @endif
            @else
                <div class="company-name">বিক্রয় ভাউচার</div>
            @endif
        </div>

        <!-- Voucher Title -->
        <div class="voucher-title">
            SALES INVOICE / বিক্রয় চালান
        </div>

        <!-- Voucher Meta -->
        <div class="voucher-meta">
            <div class="voucher-number">
                ভাউচার নং: {{ $sale->voucher_number }}
                @if($sale->due_amount > 0)
                    <span class="status-badge status-due">বকেয়া / DUE</span>
                @else
                    <span class="status-badge status-paid">পরিশোধিত / PAID</span>
                @endif
            </div>
            <div class="voucher-date">
                📅 {{ $sale->created_at->format('d/m/Y h:i A') }}
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">গ্রাহকের তথ্য / Customer Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">নাম / Name:</span>
                    <span class="info-value">{{ $sale->customer_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">ফোন / Phone:</span>
                    <span class="info-value">{{ $sale->customer_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">সেলসম্যান / Salesman:</span>
                    <span class="info-value">{{ $sale->user->name }}</span>
                </div>
                @if($sale->expected_clear_date)
                <div class="info-item">
                    <span class="info-label">পরিশোধের তারিখ:</span>
                    <span class="info-value">{{ $sale->expected_clear_date->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="section">
            <div class="section-title">পণ্যের বিবরণ / Product Details</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>পণ্যের নাম / Product Name</th>
                        <th>কোড / SKU</th>
                        <th style="text-align: center;">পরিমাণ / Qty</th>
                        <th style="text-align: right;">একক মূল্য / Price</th>
                        <th style="text-align: right;">মোট / Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ $sale->product->name }}</strong></td>
                        <td>{{ $sale->product->sku }}</td>
                        <td style="text-align: center;"><strong>{{ $sale->quantity }}</strong></td>
                        <td style="text-align: right;">৳{{ number_format($sale->sell_price, 2) }}</td>
                        <td style="text-align: right;"><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Summary -->
        <div class="total-section">
            <div class="total-row">
                <span><strong>মোট টাকা / Total Amount:</strong></span>
                <span><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></span>
            </div>
            <div class="total-row">
                <span>পরিশোধিত / Paid:</span>
                <span>৳{{ number_format($sale->paid_amount, 2) }}</span>
            </div>
            @if($sale->due_amount > 0)
            <div class="total-row grand due">
                <span>বকেয়া / Due Amount:</span>
                <span>৳{{ number_format($sale->due_amount, 2) }}</span>
            </div>
            @else
            <div class="total-row grand">
                <span>✓ সম্পূর্ণ পরিশোধিত / FULLY PAID</span>
                <span>✓</span>
            </div>
            @endif
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    বিক্রেতার স্বাক্ষর<br>
                    <small style="font-weight: 400;">Seller's Signature</small>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    ক্রেতার স্বাক্ষর<br>
                    <small style="font-weight: 400;">Buyer's Signature</small>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            @if($template && $template->footer_text)
                <div class="footer-highlight">
                    {{ $template->footer_text }}
                </div>
            @endif
            <div>এই ভাউচারটি কম্পিউটার দ্বারা তৈরি এবং স্বাক্ষরের প্রয়োজন নেই।</div>
            <div>This is a computer-generated invoice and does not require a signature.</div>
            <div style="margin-top: 8px; font-weight: 600;">মুদ্রণের সময় / Printed: {{ now()->format('d/m/Y h:i A') }}</div>
        </div>
    </div>
</body>
</html>
