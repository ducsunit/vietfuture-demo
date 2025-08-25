<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_uid',
        'title',
        'content',
    ];

    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class);
    }
}


