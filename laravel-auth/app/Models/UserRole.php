<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'si_user_role';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
