<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'description',
        'amount',
        'expense_date',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function categories()
    {
        return [
            'rent' => 'ভাড়া',
            'salary' => 'বেতন',
            'equipment' => 'যন্ত্রপাতি',
            'utility' => 'ইউটিলিটি',
            'transport' => 'পরিবহন',
            'maintenance' => 'রক্ষণাবেক্ষণ',
            'marketing' => 'মার্কেটিং',
            'other' => 'অন্যান্য',
        ];
    }

    public function getCategoryNameAttribute()
    {
        $categories = self::categories();
        return $categories[$this->category] ?? $this->category;
    }
}
