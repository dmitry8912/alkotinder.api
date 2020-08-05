<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalSettings extends Model
{
    protected $table = 'personal_settings';
    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
