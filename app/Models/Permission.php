<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_permission',
        'reason',
        'image',
        'is_approval',
    ];

    protected $casts = [
        'date_permission' => 'date',
        'is_approval' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
