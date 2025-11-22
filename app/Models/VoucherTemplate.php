<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'company_name',
        'company_address',
        'company_phone',
        'header_text',
        'footer_text',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
