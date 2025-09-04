<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipToAuthority extends Model
{
    use HasFactory;

    protected $table = 'relationship_to_authority';

    protected $fillable = [
        'personal_information_id',
        'within_third_degree',
        'within_fourth_degree',
        'details',
    ];

    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_information_id');
    }
}
