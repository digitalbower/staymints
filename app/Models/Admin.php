<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable,SoftDeletes;
    
    protected $guard = 'admin'; 
    
    protected $table = 'admins';

    protected $fillable = [
        'name', 'email', 'password', 'remember_token','user_role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role_id');
    }
    public function hasPermission($permissionName)
    {
        return $this->role 
            && $this->role->permissions->contains('name', $permissionName);
    }
    public function permissionRoles()
    {
        return $this->hasMany(PermissionRole::class, 'role_id');
    }

    protected static function booted()
    {
        static::deleting(function ($admin) {
            if (!$admin->isForceDeleting()) {
                // Soft delete permission admins
                $admin->permissionRoles()->delete();
            }
        });
    }
}
