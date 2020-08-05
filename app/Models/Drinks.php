<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drinks extends Model
{
    protected $table = 'drinks';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
