<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonReviewProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'account_id',
        'review_link',
        'review_title',
        'review_description',
        'rating',
        'status',
        'type',
        'book_asin',
    ];
}
