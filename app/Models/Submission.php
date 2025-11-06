<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'document_type',
    ];

    /**
     * The employee who submitted the document
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The HR/Admin user who receives the document
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
