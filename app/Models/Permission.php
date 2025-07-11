<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name'];
    
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'permission_admins');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles');
    }
}
