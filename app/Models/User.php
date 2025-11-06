<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email', 'password', 'role', 'status'];
    protected $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return 'email'; 
    }

    public function personalInformation()
{
    return $this->hasOne(PersonalInformation::class);
}

public function pds()
{
    return $this->hasOne(PDS::class);
}

public function saln()
{
    return $this->hasOne(SALN::class);
}

// Employee: submissions they sent
public function submissionsSent()
{
    return $this->hasMany(Submission::class, 'sender_id');
}

// HR/Admin: submissions they received
public function submissionsReceived()
{
    return $this->hasMany(Submission::class, 'recipient_id');
}

}
