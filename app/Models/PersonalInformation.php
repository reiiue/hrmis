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

        // Relationship to Government IDs
    public function governmentIds()
    {
        return $this->hasMany(GovernmentId::class, 'personal_information_id');
    }

    // Relationship to Spouse
    public function spouse()
    {
        return $this->hasOne(Spouse::class);
    }

    // Relationship to Children
    public function children()
    {
        return $this->hasMany(Child::class);

    // Relationship to Parents
    }public function parents()
    {
        return $this->hasOne(ParentInfo::class);
    }
    
    // Relationship to Educational Background
    public function educationalBackgrounds()
    {
        return $this->hasMany(EducationalBackground::class);   
    }

        /**
     * Relationship: A person can have many Civil Service Eligibilities
     */
    public function civilServiceEligibilities()
    {
        return $this->hasMany(CivilServiceEligibility::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function membershipAssociations()
    {
        return $this->hasMany(MembershipAssociation::class);
    }
}
