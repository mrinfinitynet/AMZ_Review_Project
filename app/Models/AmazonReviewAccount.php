<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonReviewAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_id',
        'account_email',
        'account_password',
        'type',
        'total_review',
        'last_checking'
    ];

    protected $casts = [
        'total_review' => 'integer'
    ];
}
