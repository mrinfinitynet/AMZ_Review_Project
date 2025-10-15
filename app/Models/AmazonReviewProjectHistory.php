<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonReviewProjectHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'project_id',
        'account_id',
        'rating',
        'msg',
        'status',
        'type'
    ];

    protected $casts = [
        'review_id' => 'integer',
        'rating' => 'integer'
    ];
}
