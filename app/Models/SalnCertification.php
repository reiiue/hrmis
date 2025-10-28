<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalnCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_information_id',
        'signature_of_declarant',
        'government_issued_id',
        'id_no',
        'date_issued',
    ];

    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class);
    }
}
