<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'personal_informations';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'date_of_birth',
        'place_of_birth',
        'citizenship',
        'dual_citizenship_type',
        'dual_citizenship_country_id',
        'sex',
        'civil_status',
        'height',
        'weight',
        'blood_type',
        'position',
        'email',
        'telephone_no',
        'mobile_no',
        'agency_employee_no',
    ];

    /**
     * Relationships
     */

    // One-to-One with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Country (if you have a Country model)
    public function dualCitizenshipCountry()
    {
        return $this->belongsTo(Country::class, 'dual_citizenship_country_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
