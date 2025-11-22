<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট ভাউচার - {{ $profitRealization->payment_voucher_number }}</title>
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
            .watermark {
                opacity: 0.08;
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
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: #10b981;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }
        .content {
            position: relative;
            z-index: 1;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
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
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 12px;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 2px;
        }
        .voucher-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 12px;
            background: #f9fafb;
            border-left: 4px solid #10b981;
        }
        .voucher-number {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
        }
        .voucher-date {
            font-size: 14px;
            color: #4b5563;
        }
        .section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            border-bottom: 2px solid #10b981;
            padding-bottom: 8px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px 15px;
            background: #f9fafb;
            border-radius: 4px;
        }
        .info-row:nth-child(even) {
            background: #f3f4f6;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 180px;
        }
        .info-value {
            color: #111827;
            flex: 1;
            text-align: right;
            font-weight: 500;
        }
        .payment-box {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
        }
        .payment-label {
            font-size: 16px;
            opacity: 0.95;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .payment-amount {
            font-size: 42px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .amount-words {
            font-size: 14px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.4);
            font-style: italic;
            opacity: 0.9;
        }
        .original-sale-box {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .balance-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);
            border: 2px solid #f59e0b;
            border-radius: 8px;
        }
        .balance-item {
            text-align: center;
            padding: 10px;
        }
        .balance-label {
            font-size: 12px;
            color: #78350f;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .balance-value {
            font-size: 20px;
            font-weight: bold;
            color: #92400e;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .status-due {
            background: #fee2e2;
            color: #991b1b;
        }
        .signature-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 60px;
            margin-top: 40px;
            padding-top: 15px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin-top: 50px;
            padding-top: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .footer-highlight {
            margin-bottom: 10px;
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
        }
        .print-button {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 14px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
            transition: all 0.3s;
        }
        .print-button:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            box-shadow: 0 6px 8px rgba(5, 150, 105, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <div style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">
            <a href="{{ route(auth()->user()->getRoleNames()->first() . '.dashboard') }}" 
               style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3); transition: all 0.3s; display: inline-block;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 8px rgba(37, 99, 235, 0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(37, 99, 235, 0.3)'">
                🏠 ড্যাশবোর্ডে ফিরুন / Back to Dashboard
            </a>
            <button class="print-button" onclick="window.print()">🖨️ প্রিন্ট করুন / Print</button>
        </div>
    </div>

    <div class="voucher">
        <!-- Watermark -->
        <div class="watermark">PAID</div>
        
        <div class="content">
        }
        .balance-label {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 5px;
        }
        .balance-value {
            font-size: 20px;
            font-weight: bold;
            color: #78350f;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 20px;
        }
        .signature-box {
            text-align: center;
            min-width: 180px;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 8px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .footer-note {
            margin-bottom: 8px;
            font-size: 13px;
            color: #374151;
        }
        .print-button {
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background: #1d4ed8;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(5, 150, 105, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        .content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button class="print-button" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
        <a href="{{ auth()->user()->hasRole('owner') ? route('owner.due-customers') : route('manager.due-customers') }}" 
           style="display: inline-block; background: #6b7280; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; margin-left: 10px;">
            ← বকেয়া তালিকায় ফিরুন
        </a>
    </div>

    <div class="page-container">
        <div class="voucher">
            <div class="watermark">PAID</div>
            
            <div class="content">
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
                <div class="company-name">পেমেন্ট রসিদ</div>
            @endif
        </div>

        <!-- Voucher Title -->
        <div class="voucher-title">
            PAYMENT RECEIPT / পেমেন্ট রসিদ ✓
        </div>

        <!-- Voucher Meta -->
        <div class="voucher-meta">
            <div class="voucher-number">
                রসিদ নং / Receipt No: {{ $profitRealization->payment_voucher_number }}
            </div>
            <div class="voucher-date">
                📅 {{ $profitRealization->payment_date->format('d/m/Y h:i A') }}
            </div>
        </div>

        <!-- Payment Information -->
        <div class="section">
            <div class="section-title">পেমেন্ট তথ্য / Payment Information</div>
            <div class="info-row">
                <span class="info-label">গ্রাহকের নাম / Customer:</span>
                <span class="info-value">{{ $sale->customer_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">ফোন নম্বর / Phone:</span>
                <span class="info-value">{{ $sale->customer_phone ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">পণ্য / Product:</span>
                <span class="info-value">{{ $sale->product->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">গৃহীত / Received By:</span>
                <span class="info-value">{{ $profitRealization->recordedBy->name ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Payment Amount -->
        <div class="payment-box">
            <div class="payment-label">প্রাপ্ত টাকা / Received Amount</div>
            <div class="payment-amount">৳{{ number_format($profitRealization->payment_amount, 2) }}</div>
            <div class="amount-words">
                ({{ numberToBengaliWords($profitRealization->payment_amount) }} টাকা মাত্র)
            </div>
        </div>

        <!-- Original Sale Reference -->
        <div class="original-sale-box">
            <div class="section-title" style="border-color: #3b82f6; color: #1e40af;">মূল বিক্রয়ের তথ্য / Original Sale Reference</div>
            <div class="info-row" style="background: white;">
                <span class="info-label">বিক্রয় ভাউচার / Invoice No:</span>
                <span class="info-value" style="color: #2563eb; font-weight: bold;">{{ $sale->voucher_number }}</span>
            </div>
            <div class="info-row" style="background: white;">
                <span class="info-label">বিক্রয়ের তারিখ / Sale Date:</span>
                <span class="info-value">{{ $sale->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row" style="background: white;">
                <span class="info-label">মোট বিক্রয় মূল্য / Total Amount:</span>
                <span class="info-value" style="font-weight: bold;">৳{{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Balance Summary -->
        <div class="balance-summary">
            <div class="balance-item">
                <div class="balance-label">পূর্বে পরিশোধিত<br><small>Previously Paid</small></div>
                <div class="balance-value">৳{{ number_format($sale->paid_amount - $profitRealization->payment_amount, 2) }}</div>
            </div>
            <div class="balance-item">
                <div class="balance-label">আজ প্রাপ্ত<br><small>Today's Payment</small></div>
                <div class="balance-value" style="color: #047857;">৳{{ number_format($profitRealization->payment_amount, 2) }}</div>
            </div>
            <div class="balance-item">
                <div class="balance-label">বর্তমান বকেয়া<br><small>Remaining Due</small></div>
                <div class="balance-value" style="color: {{ $sale->due_amount > 0 ? '#dc2626' : '#047857' }};">
                    ৳{{ number_format($sale->due_amount, 2) }}
                </div>
            </div>
        </div>

        @if($sale->due_amount > 0)
        <div style="background: #fee2e2; border: 2px solid #dc2626; border-radius: 8px; padding: 15px; text-align: center; margin: 20px 0;">
            <strong style="color: #991b1b; font-size: 16px;">অবশিষ্ট বকেয়া / Remaining Due:</strong>
            <div style="color: #dc2626; font-size: 24px; font-weight: bold; margin-top: 5px;">৳{{ number_format($sale->due_amount, 2) }}</div>
            @if($sale->expected_clear_date)
                <div style="font-size: 13px; color: #7f1d1d; margin-top: 8px;">📅 পরিশোধের তারিখ: {{ $sale->expected_clear_date->format('d/m/Y') }}</div>
            @endif
        </div>
        @else
        <div style="background: #d1fae5; border: 2px solid #059669; border-radius: 8px; padding: 15px; text-align: center; margin: 20px 0;">
            <strong style="color: #065f46; font-size: 20px;">✓ সম্পূর্ণ পরিশোধিত / FULLY PAID</strong>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    প্রাপকের স্বাক্ষর<br>
                    <small style="font-weight: 400;">Receiver's Signature</small>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    প্রদানকারীর স্বাক্ষর<br>
                    <small style="font-weight: 400;">Payer's Signature</small>
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
            <div>এই রসিদটি কম্পিউটার দ্বারা তৈরি এবং স্বাক্ষরের প্রয়োজন নেই।</div>
            <div>This is a computer-generated receipt and does not require a signature.</div>
            <div style="margin-top: 8px; font-weight: 600;">মুদ্রণের সময় / Printed: {{ now()->format('d/m/Y h:i A') }}</div>
        </div>
    </div>
    </div>
</body>
</html>
                </div>

                <!-- Footer -->
                <div class="footer">
                    @if($template && $template->footer_text)
                        <div class="footer-note">{{ $template->footer_text }}</div>
                    @endif
                    <div>এই রসিদটি কম্পিউটার দ্বারা তৈরি এবং স্বাক্ষরের প্রয়োজন নেই।</div>
                    <div style="margin-top: 5px;">This is a computer-generated receipt and does not require a signature.</div>
                    <div style="margin-top: 5px; font-weight: bold;">মুদ্রণের সময়: {{ now()->format('d/m/Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
