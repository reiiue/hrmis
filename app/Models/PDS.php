<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDS extends Model
{
    use HasFactory;

    protected $table = 'pds'; // <-- explicitly set the table name

    protected $fillable = [
        'user_id',
        'status',
        'last_action_by',
        'last_action_at',
        'data'
    ];

    protected $casts = [
        'data' => 'array', // JSON column
        'last_action_at' => 'datetime',
    ];

    // The employee who owns this PDS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The HR/Admin who last approved or acted on it
    public function lastActionBy()
    {
        return $this->belongsTo(User::class, 'last_action_by');
    }
}
