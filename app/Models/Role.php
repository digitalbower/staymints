<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
